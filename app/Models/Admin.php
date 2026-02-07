<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'email_verified_at',
        'login_verification_code',
        'login_verification_code_expires_at',
        'password_reset_code',
        'password_reset_code_expires_at',
        'username',
        'phone',
        'theme',
        'two_factor_method',
        'welcome_email_sent_at',
        'created_by',
        'deleted_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'login_verification_code_expires_at' => 'datetime',
            'password_reset_code_expires_at' => 'datetime',
            'welcome_email_sent_at' => 'datetime',
            'created_by' => 'array',
            'deleted_by' => 'array',
            'password' => 'hashed',
        ];
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    public static function generateUsername($name)
    {
        $baseUsername = 'admin_' . strtolower(str_replace(' ', '_', $name));
        $username = $baseUsername;
        $counter = 1;
        
        while (self::where('username', $username)->exists()) {
            $username = $baseUsername . '_' . $counter;
            $counter++;
        }
        
        return $username;
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($admin) {
            if (empty($admin->username)) {
                $admin->username = self::generateUsername($admin->name);
            }
        });
    }
}
