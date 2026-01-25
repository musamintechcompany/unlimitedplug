<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function getChartData(Request $request)
    {
        $period = $request->input('period', 30);
        
        $labels = [];
        $orders = [];
        $users = [];
        
        // Generate date range
        $dateRange = $this->getDateRange($period);
        
        foreach ($dateRange as $date) {
            $labels[] = $date['label'];
            
            // Orders
            $orders[] = Order::whereBetween('created_at', [$date['start'], $date['end']])
                ->count();
            
            // New Users
            $users[] = User::whereBetween('created_at', [$date['start'], $date['end']])
                ->count();
        }
        
        return response()->json([
            'labels' => $labels,
            'orders' => $orders,
            'users' => $users
        ]);
    }
    
    public function getVisitorChartData(Request $request)
    {
        $period = $request->input('period', 30);
        
        $labels = [];
        $total = [];
        $unique = [];
        
        $dateRange = $this->getDateRange($period);
        
        foreach ($dateRange as $date) {
            $labels[] = $date['label'];
            
            $total[] = Visitor::whereBetween('created_at', [$date['start'], $date['end']])->count();
            $unique[] = Visitor::whereBetween('created_at', [$date['start'], $date['end']])
                ->distinct('visitor_id')
                ->count('visitor_id');
        }
        
        return response()->json([
            'labels' => $labels,
            'total' => $total,
            'unique' => $unique
        ]);
    }
    
    public function getCurrencyBalances()
    {
        $balances = Order::where('status', 'completed')
            ->selectRaw('currency, SUM(total_amount) as total')
            ->groupBy('currency')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->currency => $item->total];
            });
        
        return $balances;
    }
    
    private function getDateRange($period)
    {
        $dates = [];
        $now = Carbon::now();
        
        if ($period <= 30) {
            // Daily data
            for ($i = $period - 1; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i);
                $dates[] = [
                    'label' => $date->format('M d'),
                    'start' => $date->copy()->startOfDay(),
                    'end' => $date->copy()->endOfDay()
                ];
            }
        } elseif ($period <= 90) {
            // Weekly data
            $weeks = ceil($period / 7);
            for ($i = $weeks - 1; $i >= 0; $i--) {
                $startDate = $now->copy()->subWeeks($i)->startOfWeek();
                $endDate = $now->copy()->subWeeks($i)->endOfWeek();
                $dates[] = [
                    'label' => $startDate->format('M d'),
                    'start' => $startDate->copy(),
                    'end' => $endDate->copy()
                ];
            }
        } else {
            // Monthly data
            $months = ceil($period / 30);
            for ($i = $months - 1; $i >= 0; $i--) {
                $date = $now->copy()->subMonths($i);
                $dates[] = [
                    'label' => $date->format('M Y'),
                    'start' => $date->copy()->startOfMonth(),
                    'end' => $date->copy()->endOfMonth()
                ];
            }
        }
        
        return $dates;
    }
}
