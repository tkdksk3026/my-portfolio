<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $schedule->title }} （{{ $schedule->date->format('Y/m/d') }}）
            </h2>
            <a href="{{ route('schedules.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- ステータス表示メッセージ -->
            @if (session('status'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1000)" x-show="show" x-transition.duration.500ms
                    class="bg-pink-100 border border-pink-400 text-pink-600 px-4 py-3 rounded relative mb-4 flex justify-between items-center">
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- スケジュール概要 & サマリー（アクセサで動的計算） -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <span class="text-xs text-gray-500 block">開始時刻</span>
                        <span class="text-xl font-bold text-blue-700">{{ $schedule->start_time->format('H:i') }}</span>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <span class="text-xs text-gray-500 block">終了時刻</span>
                        <span
                            class="text-xl font-bold text-blue-700">{{ $schedule->end_time ? $schedule->end_time->format('H:i') : '--:--' }}</span>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <span class="text-xs text-red-500 block">予定（残）時間</span>
                        <span
                            class="text-xl font-bold {{ $schedule->remaining_minutes < 0 ? 'text-red-700' : 'text-red-500' }}">
                            {{ $schedule->remaining_minutes }} 分
                        </span>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <span class="text-xs text-green-600 block">消化（済）時間</span>
                        <span class="text-xl font-bold text-green-600">{{ $schedule->completed_minutes }} 分</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- 練習メニュー追加フォーム  -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 md:col-span-1 h-fit">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">メニューを追加</h3>

                    <form action="{{ route('menu_items.store', $schedule) }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- メニュー名 -->
                        <div>
                            <x-input-label for="name" value="メニュー名" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name')" placeholder="例: パス , スパイク" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- 所要時間（分） -->                  
                            <div x-data="{ showError: true }">
                                <x-input-label for="duration_minutes" value="所要時間（分）" />
                               
                                <x-text-input id="duration_minutes" name="duration_minutes" type="number"
                                    min="1" max="480" class="mt-1 block w-full" :value="old('duration_minutes', 15)"
                                    @input="showError = false" required />

                                @error('duration_minutes')
                                    <div class="mt-2" x-show="showError" x-transition>
                                        <x-input-error :messages="$message" />
                                    </div>
                                @enderror
                            </div>

                        <div class="pt-2">
                            <x-primary-button class="w-full justify-center">
                                追加する
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- 練習メニュー一覧 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 md:col-span-2">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">練習メニュー 一覧</h3>

                    <!-- Livewireコンポーネントを呼び出す -->
                    <livewire:menu-item-reorder :schedule="$schedule" />
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
