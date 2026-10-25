<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Schedule;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    /**
     * 練習メニューを新規登録
     */
    public function store(Request $request, Schedule $schedule)
    {
        if ($schedule->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:480'],
        ]);

        $maxLimit = $schedule->total_schedule_minutes;
        $currentTotal = $schedule->total_minutes;
        $newDuration = (int) $validated['duration_minutes'];

        if (($currentTotal + $newDuration) > $maxLimit) {
            return back()
                ->withInput()
                ->withErrors(['duration_minutes' => '予定時間('.$maxLimit.'分)を超えてしまいます!(残り枠: '.$schedule->available_minutes.'分)']);
        }

        $maxSortOrder = $schedule->menuItems()->max('sort_order') ?? 0;

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

    /**
     * 完了・未完了の切り替え
     */
    public function toggle(MenuItem $menuItem)
    {
        if ($menuItem->schedule->user_id !== auth()->id()) {
            abort(403);
        }

        $menuItem->is_completed = ! $menuItem->is_completed;
        $menuItem->save();

        return redirect()->route('schedules.show', $menuItem->schedule);
    }

    /**
     * 練習メニューの削除
     */
    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->schedule->user_id !== auth()->id()) {
            abort(403);
        }

        $menuItem->delete();

        return redirect()->back()->with('status', '練習メニューを削除しました！');
    }

    /**
     * 編集画面の表示
     */
    public function edit(MenuItem $menuItem)
    {
        if ($menuItem->schedule->user_id !== auth()->id()) {
            abort(403);
        }

        return view('menu_items.edit', ['menuItem' => $menuItem]);
    }

    /**
     * 練習メニューの更新
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $schedule = $menuItem->schedule;

        if ($schedule->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:480'],
        ]);

        $otherItemsDuration = $schedule->menuItems()
            ->where('id', '!=', $menuItem->id)
            ->sum('duration_minutes');

        $maxLimit = $schedule->total_schedule_minutes;
        $newDuration = (int) $request->input('duration_minutes', 0);
        $expectedTotal = $otherItemsDuration + $newDuration;

        if ($expectedTotal > $maxLimit) {
            $availableForThis = max(0, $maxLimit - $otherItemsDuration);

            return back()
                ->withInput()
                ->withErrors([
                    'duration_minutes' => '予定時間('.$maxLimit.'分)を超えてしまいます!(設定可能な上限: '.$availableForThis.'分)',
                ]);
        }

        $menuItem->update($validated);

        return redirect()
            ->route('schedules.show', $schedule)
            ->with('status', '練習メニューを更新しました！');
    }
}
