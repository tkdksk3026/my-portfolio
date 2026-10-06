<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

//一覧画面：練習スケジュールおを取得して画面に渡す
class ScheduleController extends Controller
{
    
    public function index()
    {
    //ログイン中のユーザー情報を取得
    $user = auth()->user();

    //ログイン中のユーザーに紐づくスケジュールを取得
    $schedules = $user->schedules()
        ->orderBy('date', 'desc')
        ->get();

    //取得したスケジュールを画面に渡す
    return view('schedules.index',[
        'schedules'=> $schedules
    ]);
    }

        // 新しい練習スケジュールをデータベースに保存する
    public function store(Request $request)
    {
        //入力バリデーション
        $request->validate([
            'title' => 'required|string|max:255', //必須・文字列
            'date' => 'required|date', //必須・日付
            'start_time' => 'required', //必須
    ]);
        //ログインユーザーに紐づけてDBへ登録
        auth()->user()->schedules()->create([
            'title' => $request->title,
            'date' => $request->date,
            'start_time' => $request->start_time,

        ]);

        //保存が完了したらスケジュール一覧画面へリダイレクト
        return redirect()->route('schedules.index');
    
    }
    
    public function show(Schedule $schedule)
    {
        //セキュリティチェック
        if($schedule->user_id !== auth()->id()){
            abort(403);

        }
        //ビューに選択されたスケジュールデータを渡す
        return view('schedules.show',[
            'schedule' => $schedule
        ]);
    }

    public function destroy(Schedule $schedule)
    {
        //セキュリティチェック(他人のスケジュール削除した場合拒否する)
        if($schedule->user_id !== auth()->id()) {
            abort(403);
        }

        //データベースから削除
        $schedule->delete();

        //削除が完了したらスケジュール一覧画面へリダイレクト
        return redirect()->route('schedules.index');

    }

}
