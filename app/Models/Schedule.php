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

//終了予定時刻(開始時間+合計分数）
//$schedule->end_timeで取得できるようにする
public function getEndTimeAttribute()
{
    //開始時間がnullの場合はnullを返す
    if(!$this->start_time){
        return null;
    }
    //Carbonを使って開始時間に合計分数を足して終了予定時刻を計算する
    return Carbon::parse($this->start_time)->addMinutes($this->total_minutes)->format('H:i');
}
}