<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    
    protected $fillable = [
        'user_id',
        'title',
        'date',
        'start_time',
        'end_time',
    
];  

//日付と時刻をCarbonインスタンスに変換する
    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];


//リレーション：このスケジュールを作ったユーザー
public function user()
{
    
    return $this->belongsTo(User::class);

}

//リレーション：このスケジュールに含まれる練習メニュー一覧
public function menuItems()
{
    
    return $this->hasMany(MenuItem::class);
}

//全メニューの合計時間(単位：分）
//$schedule->total_minutesで取得できるようにする
public function getTotalMinutesAttribute()
{
    //全メニューの合計時間を取得する
    return $this->menuItems->sum('duration_minutes');

}

//完了済みメニューの合計時間(単位：分）
//$schedule->completed_minutesで取得できるようにする
public function getCompletedMinutesAttribute()
{
    //完了済みメニューの合計時間を取得する
    return $this->menuItems->where('is_completed', true)->sum('duration_minutes');
}

}