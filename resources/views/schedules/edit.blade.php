<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('練習スケジュール編集') }}
            </h2>
            <a href="{{ route('schedules.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">スケジュールを編集</h3>

                <!-- 更新用フォーム (PUTメソッドを指定) -->
                <form method="POST" action="{{ route('schedules.update', $schedule) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">練習タイトル</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $schedule->title) }}"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title')
                            <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ endError: true }">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">練習日</label>
                            <input type="date" name="date"
                                value="{{ old('date', $schedule->date->format('Y-m-d')) }}" id="date" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('date')
                                <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700">開始時間</label>
                            <input type="time" name="start_time"
                                value="{{ old('start_time', $schedule->start_time->format('H:i')) }}" id="start_time"
                                required @input="endError = false"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('start_time')
                                <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700">終了時間</label>
                            <input type="time" name="end_time"
                                value="{{ old('end_time', $schedule->end_time->format('H:i')) }}" id="end_time"
                                required @input="endError = false"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                            @error('end_time')
                                <div class="mt-1" x-show="endError" x-transition>
                                    <p style="color: red; font-size: 0.875rem;">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-start space-x-3">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            更新する
                        </button>
                        <a href="{{ route('schedules.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm">
                            キャンセル
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
