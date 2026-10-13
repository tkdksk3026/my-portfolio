<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Schedule;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    //練習アイテムの保存処理
    public function store(Request $request, Schedule $schedule)
    {
        //バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1|max:480',
        ]);

        $maxSortOrder = $schedule->menuItems()->max('sort_order')?? 0;

        //リレーションを使って、紐づくスケジュールに練習アイテムを保存する
        $schedule->menuItems()->create([
            'name' => $validated['name'],
            'duration_minutes' => $validated['duration_minutes'],
            'is_completed' => false,
            'sort_order' => $maxSortOrder + 1,
        ]);

        return redirect()
            ->route('schedules.show', $schedule)
            ->with('status', '練習メニューを追加しました！');

    }

    public function moveUp(MenuItem $menuItem)
    {
        // 現在のメニューアイテムの前にあるメニューアイテムを取得
        $previousItem = MenuItem::where('schedule_id', $menuItem->schedule_id)
        
            ->where('sort_order', '<', $menuItem->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previousItem){
            
            $tempOrder = $menuItem->sort_order;
            $menuItem->sort_order = $previousItem->sort_order;
            $previousItem->sort_order = $tempOrder;

            $menuItem->save();
            $previousItem->save();
        }

        return redirect()->route('schedules.show', $menuItem->schedule_id);

    }

    public function moveDown(MenuItem $menuItem)
    {

        $nextItem = MenuItem::where('schedule_id', $menuItem->schedule_id)
            ->where('sort_order', '>', $menuItem->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if($nextItem){

            $temp = $menuItem->sort_order;
            $menuItem->sort_order = $nextItem->sort_order;
            $nextItem->sort_order = $temp;

            $menuItem->save();
            $nextItem->save();
        }

        return redirect()->route('schedules.show', $menuItem->schedule_id);

    }

    public function destroy(MenuItem $menuItem)
    {
        $scheduleId = $menuItem->schedule_id;

        $menuItem->delete();

        return redirect()
            ->route('schedules.show', $scheduleId)
            ->with('status', '練習メニューを削除しました！');
    }
}