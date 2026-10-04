<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//一覧画面：練習スケジュールおを取得して画面に渡す
class ScheduleController extends Controller
{
    
    public function index()
    {
    //ログイン中のユーザー情報を取得
    $user = auth()->user();

    //ログイン中のユーザーに紐づくスケジュールを取得
    $schedules = $user->schedules()
        ->orderBy('date', 'desx')
        ->get();

    //取得したスケジュールを画面に渡す
    return view('schedules.index',[
        'schedules'=> schedules
    ]);
    }
}
