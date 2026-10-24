<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'date',
        'start_time',
        'end_time',

    ];

    // 日付と時刻をCarbonインスタンスに変換する
    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menuItems()
    {       
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    // 全メニューの合計時間(単位：分）
    // $schedule->total_minutesで取得できるようにする
    public function getTotalMinutesAttribute()
    {
        // 全メニューの合計時間を取得する
        return $this->menuItems->sum('duration_minutes');

    }

    // 完了済みメニューの合計時間(単位：分）
    // $schedule->completed_minutesで取得できるようにする
    public function getCompletedMinutesAttribute()
    {
        // 完了済みメニューの合計時間を取得する
        return $this->menuItems->where('is_completed', true)->sum('duration_minutes');
    }

    // スケジュールの総時間（日付を統一して時間・分のみで計算）
    // $schedule->schedule_minutesで取得できるようにする
    public function getTotalScheduleMinutesAttribute()
    {

        if ($this->start_time && $this->end_time) {

            $start = Carbon::parse($this->start_time)->setDate(2026, 1, 1);
            $end = Carbon::parse($this->end_time)->setDate(2026, 1, 1);

            return $start->diffInMinutes($end);
        }

        return 0;
    }

    // 利用可能な残り時間（総スケジュール時間から全メニューの合計時間を引いた値）
    // $schedule->available_minutesで取得できるようにする
    public function getAvailableMinutesAttribute()
    {
        return max(0, $this->total_schedule_minutes - $this->total_minutes);

    }

    // 未完了の残り時間（全メニューの合計時間から完了済みメニューの合計時間を引いた値）
    //// $schedule->remaining_minutesで取得できるようにする
    public function getRemainingMinutesAttribute()
    {

        return $this->total_minutes - $this->completed_minutes;
    }

 
}
