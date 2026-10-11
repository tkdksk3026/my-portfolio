<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $schedule->title }} （{{ $schedule->date->format('Y/m/d') }}）
            </h2>
            <a href="{{ route('schedules.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                ← 一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- ステータス表示メッセージ -->
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <!-- 1. スケジュール概要 & サマリー（アクセサで動的計算） -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <span class="text-xs text-gray-500 block">開始時刻</span>
                        <span class="text-xl font-bold text-blue-700">{{ $schedule->start_time->format('H:i') }}</span>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <span class="text-xs text-gray-500 block">終了時刻</span>
                        <span class="text-xl font-bold text-blue-700">{{ $schedule->end_time ? $schedule->end_time->format('H:i') : '--:--' }}</span>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <span class="text-xs text-red-500 block">残り時間</span>
                        <span class="text-xl font-bold text-red-500">{{ $schedule->total_minutes }} 分</span>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <span class="text-xs text-green-600 block">消化時間</span>
                        <span class="text-xl font-bold text-green-600">{{ $schedule->completed_minutes }} 分</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- 2. 練習メニュー追加フォーム (1列分) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 md:col-span-1 h-fit">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">メニューを追加</h3>
                    
                    <form action="{{ route('menu_items.store', $schedule) }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- メニュー名 -->
                        <div>
                            <x-input-label for="name" value="メニュー名" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" placeholder="例: パス練習, スパイク打ち" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- 所要時間（分） -->
                        <div>
                            <x-input-label for="duration_minutes" value="所要時間（分）" />
                            <x-text-input id="duration_minutes" name="duration_minutes" type="number" min="1" max="480" class="mt-1 block w-full" :value="old('duration_minutes', 15)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('duration_minutes')" />
                        </div>

                        <div class="pt-2">
                            <x-primary-button class="w-full justify-center">
                                追加する
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- 3. 練習メニュー一覧 (2列分) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 md:col-span-2">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">練習メニュー一覧</h3>

                    @if ($schedule->menuItems->isEmpty())
                        <p class="text-gray-500 text-sm py-4 text-center">まだ練習メニューが登録されていません。<br>左のフォームから追加してください。</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($schedule->menuItems as $item)
                                <div class="flex items-center justify-between p-4 rounded-lg border {{ $item->is_completed ? 'bg-gray-50 border-gray-200' : 'bg-white border-gray-300 shadow-sm' }}">
                                    <div class="flex items-center space-x-3">
                                        <!-- ステータスアイコン（見た目のみ） -->
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold {{ $item->is_completed ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $item->is_completed ? '✓' : '未' }}
                                        </span>
                                        <div>
                                            <p class="font-bold {{ $item->is_completed ? 'line-through text-gray-400' : 'text-gray-800' }}">
                                                {{ $item->name }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold">
                                            {{ $item->duration_minutes }} 分
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-app-layout>