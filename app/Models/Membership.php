<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Override;

class Membership extends Model
{
    protected $fillable = ['user_id', 'package_id', 'start_date', 'end_date', 'status'];

    #[Override]
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($membership){
            $membership->status = $membership->status ?? 'pending';
            $membership->start_date = null;
            $membership->end_date = null;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
