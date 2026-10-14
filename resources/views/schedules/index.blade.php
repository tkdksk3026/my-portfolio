<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('練習スケジュール一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. 新規スケジュール作成フォーム -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">新しい練習予定を追加</h3>

                <!-- store メソッドへ送信（POSTリクエスト） -->
                <form method="POST" action="{{ route('schedules.store') }}" class="space-y-4">
                    @csrf {{-- CSRF攻撃防止のセキュリティトークン（必須） --}}

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">練習タイトル</label>
                        <input type="text" name="title" id="title" required placeholder="例: 10/10  夜 練習" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">練習日</label>
                            <input type="date" name="date" id="date" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700">開始時間</label>
                            <input type="time" name="start_time" id="start_time" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700">終了時間</label>
                            <input type="time" name="end_time" id="end_time" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-500 focus:outline-none">
                            スケジュールを作成する
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. スケジュール一覧 ＆ 削除機能 -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">登録済みの練習予定</h3>

                @if($schedules->isEmpty())
                    <p class="text-gray-500">登録されている練習予定はありません。</p>
                @else
                    <div class="space-y-4">
                       @foreach($schedules as $schedule)
                            <!-- 👇 flex をやめて、通常のブロック配置＆パディングや高さを整える -->
                            <div class="p-4 border rounded-lg hover:bg-gray-50 space-y-3">
                                
                                <!-- 上段：日時（日付と時間） -->
                                <div class="text-base text-gray-500">
                                    📅 {{ \Illuminate\Support\Str::limit($schedule->date, 10, '') }} 
                                    ⏰ {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} ～ {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </div>
                                
                                <!-- 中段：タイトル ＆ 右側の削除ボタンを綺麗に並べるためのレイアウト -->
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('schedules.show', $schedule) }}" class="text-xl font-bold text-indigo-600 hover:underline">
                                        {{ $schedule->title }}
                                    </a>

                                    <!-- 削除ボタン -->
                                    <form method="POST" action="{{ route('schedules.destroy', $schedule) }}" onsubmit="return confirm('本当にこのスケジュールを削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition relative -translate-y-1" title="削除">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                    
                                    </form>
                                </div>

                            </div>
                        @endforeach

                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
