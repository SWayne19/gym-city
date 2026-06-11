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
        static::saved(function ($payment){
            if($payment->status === 'paid'){

                $membership = $payment->membership;

                if($membership){
                    $startDate = Carbon::today();
                    $membership->start_date = $startDate->format('Y-m-d'); 
                }

                $package = $membership->package;

                if($package){
                    $membership->end_date = $startDate->copy()->addDays($package->duration_days)->format('Y-m-d');
                }

                $membership->status = 'active';

                $membership->saveQuietly();
            }
        });
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
}
