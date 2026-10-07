<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $schedule->title }}
            </h2>
            <!-- 一覧に戻るボタン -->
            <a href="{{ route('schedules.index') }}" 
               class="px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-md hover:bg-gray-600 transition">
                ← 一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. 時間計算ダッシュボード -->
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">📊 タイムスケジュール概要</h3>
                
                <!-- 3つのカードを横並びにするレスポンシブ・グリッド -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    
                    <!--  練習時間帯（開始時刻 〜 終了予定時刻） -->
                    <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-lg">
                        <div class="text-xs text-indigo-600 font-bold uppercase tracking-wider mb-1">練習時間帯</div>
                        <div class="text-xl font-extrabold text-indigo-900">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                            <span class="text-sm font-normal text-gray-600">〜</span>
                            {{ $schedule->end_time ?? '--:--' }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">📅 {{ $schedule->date }}</div>
                    </div>

                    <!-- ② 合計練習時間 -->
                    <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg">
                        <div class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">予定 合計時間</div>
                        <div class="text-2xl font-extrabold text-blue-900">
                            {{ $schedule->total_minutes }} <span class="text-sm font-normal">分</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">全メニューの合計</div>
                    </div>

                    <!-- ③ 消化済み（完了）時間 -->
                    <div class="p-4 bg-green-50 border border-green-100 rounded-lg">
                        <div class="text-xs text-green-600 font-bold uppercase tracking-wider mb-1">消化（完了）済</div>
                        <div class="text-2xl font-extrabold text-green-900">
                            {{ $schedule->completed_minutes }} <span class="text-sm font-normal">/ {{ $schedule->total_minutes }} 分</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">チェックしたメニューの合計</div>
                    </div>

                </div>
            </div>

            <!-- 2. 練習メニュー追加＆一覧表示エリア（Phase 6で実装） -->
            <div class="p-6 bg-white shadow sm:rounded-lg border-2 border-dashed border-gray-300 text-center text-gray-500 py-12">
                ここに練習メニュー（MenuItem）の追加フォームと一覧（完了チェック機能）を作っていきます！
            </div>

        </div>
    </div>
</x-app-layout>