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

        //リレーションを使って、紐づくスケジュールに練習アイテムを保存する
        $schedule->menuItems()->create([
            'name' => $validated['name'],
            'duration_minutes' => $validated['duration_minutes'],
            'is_completed' => false,
        ]);

        return redirect()
            ->route('schedules.show', $schedule)
            ->with('status', '練習メニューを追加しました！');

    }
}
