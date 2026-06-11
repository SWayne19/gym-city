<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['name', 'price', 'duration_days', 'description'];

    // one to many
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
}
