<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    
    protected $fillable = [
        'user_id',
        'title',
        'date',
        'start_time',
    
];

public function menuItems(){
    
    return $this->hasMany(MenuItem::class);
}

}