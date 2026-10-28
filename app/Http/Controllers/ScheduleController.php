<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * ログインユーザーのスケジュール一覧を表示
     */
    public function index()
    {

        $user = auth()->user();

        $schedules = $user->schedules()
            ->orderBy('date', 'desc')
            ->get();

        return view('schedules.index', [
            'schedules' => $schedules,
        ]);
    }

    /**
     * 新規スケジュールを登録
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ], [
            'end_time.after' => '終了時間は開始時間より後の時間を指定してください！',
        ]);

        auth()->user()->schedules()->create($validated);

        return redirect()->route('schedules.index');
    }

    /**
     * 特定のスケジュール詳細を表示
     */
    public function show(Schedule $schedule)
    {

        if ($schedule->user_id !== auth()->id()) {
            abort(403);
        }

        $schedule->load(['menuItems' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }]);

        return view('schedules.show', [
            'schedule' => $schedule,
        ]);
    }

    /**
     * スケジュール編集画面を表示
     */
    public function edit(Schedule $schedule)
    {
        if ($schedule->user_id !== auth()->id()) {
            abort(403);
        }

        return view('schedules.edit', [
            'schedule' => $schedule,
        ]);
    }

    /**
     * スケジュールを更新
     */
    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ], [
            'end_time.after' => '終了時間は開始時間より後の時間を指定してください！',
        ]);

        // 更新前の段階で時間の整合性をチェック
        $start = Carbon::parse($validated['start_time']);
        $end = Carbon::parse($validated['end_time']);
        $newTotalMinutes = $start->diffInMinutes($end);
        $currentMenusTotal = $schedule->total_minutes;

        if ($newTotalMinutes < $currentMenusTotal) {
            return back()
                ->withInput()
                ->withErrors([
                    'end_time' => '既存のメニューの合計時間('.$currentMenusTotal.'分)を下回る時間に変更できません！',
                ]);
        }

        $schedule->update($validated);

        return redirect()->route('schedules.index')
            ->with('status', 'スケジュールを更新しました！');
    }

    /**
     * スケジュールを削除
     */
    public function destroy(Schedule $schedule)
    {

        if ($schedule->user_id !== auth()->id()) {
            abort(403);
        }

        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('status', 'スケジュールを削除しました！');
    }
}
