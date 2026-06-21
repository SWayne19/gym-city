<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Override;

class Payment extends Model
{
    protected $fillable = ['membership_id', 'amount', 'payment_method', 'status', 'paid_at'];

    #[Override]
    protected static function boot()
    {
        parent::boot();
        static::saved(function ($payment) {
            if ($payment->status === 'paid') {
                $membership = $payment->membership;

                if ($membership) {
                    $package = $membership->package;

                    $startDate = Carbon::today();
                    $endDate = $package ? $startDate->copy()->addDays($package->duration_days) : $startDate;

                    $membership->updateQuietly([
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                        'status' => 'active',
                    ]);
                }
            }
        });
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
}
