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


public function menuItems()
{
    //メニューアイテムを取得する際に、sort_orderでソートして取得する
    return $this->hasMany(MenuItem::class)->orderBy('sort_order');
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


 public function getAllocatbleMinutesAttribute()
{
        //スケジュールの開始時間と終了時間が設定されている場合にのみ、差分を計算する
        if( $this->start_time && $this->end_time){
            //
            return $this->start_time->diffInMinutes($this->end_time);

        }
       
        return 0;

}

     public function getTotalScheduleMinutesAttribute()
 {
    if($this->start_time && $this->end_time){

        $start = Carbon::parse($this->start_time)->setDate(2026,1,1);
        $end = Carbon::parse($this->end_time)->setDate(2026,1,1);

        return $start->diffInMinutes($end);
    }
    return 0 ;
 }
    
   public function getAvailableMinutesAttribute()
   {
    return max(0, $this->total_schedule_minutes - $this->total_minutes);

   }

    public function getRemainingMinutesAttribute()
{

    return $this->total_minutes - $this->completed_minutes;
}

    public function menuItem()
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order' , 'asc');
    }


}