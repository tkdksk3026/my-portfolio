<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Schedule;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    // 練習アイテムの保存処理
    public function store(Request $request, Schedule $schedule)
    {
        // バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1|max:480',
        ]);

        $maxLimit = $schedule->total_schedule_minutes;
        $currentTotal = $schedule->total_minutes;
        $newFuration = (int) $validated['duration_minutes'];

        if (($currentTotal + $newFuration) > $maxLimit) {
            return back()
                ->withInput()
                ->withErrors(['duration_minutes' => '予定時間('.$maxLimit.'分)を超えてしまいます!(残り枠: '.$schedule->available_minutes.'分)']);

        }

        $maxSortOrder = $schedule->menuItems()->max('sort_order') ?? 0;

        // リレーションを使って、紐づくスケジュールに練習アイテムを保存する
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

    public function toggle(MenuItem $menuItem)
    {
        // メニューアイテムの完了状態を切り替える
        $menuItem->is_completed = ! $menuItem->is_completed;
        // 変更を保存する
        $menuItem->save();

        // スケジュールの詳細ページにリダイレクトする
        return redirect()->route('schedules.show', $menuItem->schedule_id);
    }

    public function reorder(Request $requesst, Schedule $schedule)
    {
        $ids = $request->input('ids');
        if ($ids) {
            foreach ($ids as $index => $id) {
                $schedule->menuItems()->where('id', $id)->update([
                    'sort_order' => $index + 1,

                ]);
            }
        }

        return response()->json(['success' => true]);

    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->schedule->user_id !== auth()->id()) {
            abort(403);
        }

        $menuItem->delete();

        return redirect()->back()->with('status', '練習メニューを削除しました！');
    }
}
