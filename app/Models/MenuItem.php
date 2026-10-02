<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'schedule_id',
        'name',
        'duration_minutes',
        'is_completed',
        'sort_order',
    ];

    public function schedule(){

    return $this->belongsTo(Schedule::class);
    }
}
