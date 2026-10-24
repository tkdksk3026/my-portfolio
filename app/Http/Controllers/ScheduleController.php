<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
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
            'end_time'  => ['required', 'date_format:H:i', 'after:start_time'],
        ],[
            'end_time.after' =>'終了時間は開始時間より後の時間を指定してください',

        ]);




        auth()->user()->schedules()->create([
            'title' => $request->title,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,

        ]);

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
     * スケジュールを削除
     */
    public function destroy(Schedule $schedule)
    {

        if ($schedule->user_id !== auth()->id()) {
            abort(403);
        }

        $schedule->delete();

        return redirect()->route('schedules.index');

    }
}
