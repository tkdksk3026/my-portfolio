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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                            <div class="p-4 border rounded-lg flex items-center justify-between hover:bg-gray-50">
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">
                                        📅 {{ $schedule->date }} ⏰ {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} 開始
                                   </div>
                                   
                                        <a href="{{ route('schedules.show', $schedule) }}" class="text-lg font-bold text-indigo-600 hover:underline">
                                           {{ $schedule->title }}
                                            </a>
                                    </div>
                                </div>

                                <div>
                                    <!-- 削除フォーム（destroy メソッドへ DELETE リクエストを送信） -->
                                    <form method="POST" action="{{ route('schedules.destroy', $schedule) }}" onsubmit="return confirm('本当にこのスケジュールを削除しますか？');">
                                        @csrf
                                        @method('DELETE') {{-- DELETEリクエストに疑似変換（必須） --}}
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-500">
                                            削除
                                        </button>
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
