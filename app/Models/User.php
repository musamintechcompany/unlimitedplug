<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationCode;
use App\Mail\WelcomeEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phone',
        'date_of_birth',
        'gender',
        'language',
        'country',
        'state',
        'city',
        'postal_code',
        'address',
        'theme',
        'status',
        'email_verification_code',
        'verification_code_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verification_code_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function generateVerificationCode(): string
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->update([
            'email_verification_code' => $code,
            'verification_code_expires_at' => now()->addMinutes(10),
        ]);
        return $code;
    }

    public function verifyCode(string $code): bool
    {
        $isValid = $this->email_verification_code === $code && 
                   $this->verification_code_expires_at && 
                   $this->verification_code_expires_at->isFuture();
        
        if ($isValid) {
            $this->update([
                'email_verified_at' => now(),
                'status' => 'active',
                'email_verification_code' => null,
                'verification_code_expires_at' => null,
            ]);
        }
        
        return $isValid;
    }

    public function sendEmailVerificationNotification()
    {
        $code = $this->generateVerificationCode();
        Mail::to($this->email)->send(new EmailVerificationCode($code));
    }

    public function digitalAssets()
    {
        return $this->hasMany(DigitalAsset::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, Order::class);
    }

    public static function generateUsername($name)
    {
        $baseUsername = strtolower(str_replace(' ', '', $name));
        $username = $baseUsername;
        $counter = 1;
        
        while (self::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            if (empty($user->username)) {
                $user->username = self::generateUsername($user->name);
            }
        });
    }
}
