<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Package;
use App\Models\Payment;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // 1. Active ဖြစ်နေသော မန်ဘာအရေအတွက်
        $activeMembersCount = Membership::where('status', 'active')->count();

        // 2. ယခုရက်သတ္တပတ် (7 Days) အတွင်း သက်တမ်းကုန်မည့် မန်ဘာအရေအတွက်
        $expiringThisWeekCount = Membership::where('status', 'active')
            ->whereBetween('end_date', [Carbon::today(), Carbon::today()->addDays(7)])
            ->count();

        // 3. စနစ်ထဲတွင်ရှိသော စုစုပေါင်း Gym Packages အရေအတွက်
        $totalPackagesCount = Package::count();

        // 4. စုစုပေါင်း ကောက်ခံရရှိထားသော ဝင်ငွေ (Revenue)
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');

        // 5. လတ်တလော ငွေသွင်းထားသည့် Transaction သမိုင်းကြောင်း (Recent Revenue Logs) ၅ ခု
        $recentPayments = Payment::with(['membership.user', 'membership.package'])
            ->where('status', 'paid')
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'active_members' => $activeMembersCount,
                'expiring_this_week' => $expiringThisWeekCount,
                'total_packages' => $totalPackagesCount,
                'total_revenue' => (float) $totalRevenue,
            ],
            'recentPayments' => $recentPayments,
        ]);
    }
}
