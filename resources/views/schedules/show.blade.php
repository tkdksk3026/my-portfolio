<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $schedule->title }}
            </h2>
            <!-- 一覧に戻るボタン -->
            <a href="{{ route('schedules.index') }}" 
               class="px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-md hover:bg-gray-600">
                ← 一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- スケジュール基本情報カード -->
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-2">練習概要</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p>📅 <strong>練習日:</strong> {{ $schedule->date }}</p>
                    <p>⏰ <strong>開始時間:</strong> {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</p>
                </div>
            </div>

            <!-- 次のフェーズでメニュー一覧や時間計算を配置するエリア -->
            <div class="p-6 bg-white shadow sm:rounded-lg border-2 border-dashed border-gray-300 text-center text-gray-500 py-12">
                ここに練習メニュー（MenuItem）と時間計算ダッシュボードを追加していきます！
            </div>

        </div>
    </div>
</x-app-layout>