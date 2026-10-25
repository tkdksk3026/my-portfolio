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

    // 日付と時刻をCarbonインスタンスに変換
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

    /**
     * 全メニューの合計時間（分）
     */
    public function getTotalMinutesAttribute()
    {
        return $this->menuItems->sum('duration_minutes');
    }

    /**
     * 完了済みメニューの合計時間（分）
     */
    public function getCompletedMinutesAttribute()
    {
        return $this->menuItems->where('is_completed', true)->sum('duration_minutes');
    }

    /**
     * スケジュールの（開始〜終了）の総枠時間（分）
     */
    public function getTotalScheduleMinutesAttribute()
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }

        return $this->start_time->diffInMinutes($this->end_time);
    }

    /**
     * 利用可能な空き時間（分）
     */
    public function getAvailableMinutesAttribute()
    {
        return max(0, $this->total_schedule_minutes - $this->total_minutes);
    }

    /**
     * 未完了メニューの残り時間（分）
     */
    public function getRemainingMinutesAttribute()
    {
        return $this->total_minutes - $this->completed_minutes;
    }
}
