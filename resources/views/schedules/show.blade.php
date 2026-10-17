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

            <!-- 1. スケジュール概要 & サマリー（アクセサで動的計算） -->
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

                <!-- 2. 練習メニュー追加フォーム (1列分) -->
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
                        <div>
                            <x-input-label for="duration_minutes" value="所要時間（分）" />
                            <x-text-input id="duration_minutes" name="duration_minutes" type="number" min="1"
                                max="480" class="mt-1 block w-full" :value="old('duration_minutes', 15)" required />
                            @error('duration_minutes')
                                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition>
                                    <x-input-error class="mt-2" :messages="$message" />
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

                <!-- 3. 練習メニュー一覧 (2列分) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 md:col-span-2">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">練習メニュー一覧</h3>

                    @if ($schedule->menuItems->isEmpty())
                        <p class="text-gray-500 text-sm py-4 text-center">まだ練習メニューが登録されていません。<br>左のフォームから追加してください。</p>
                    @else
                        <div id="sortable-menu" class="space-y-3">
                            @foreach ($schedule->menuItems as $item)
                                <div data-id="{{ $item->id }}"
                                    class="flex items-center justify-between p-4 rounded-lg border transition-all duration-150 cursor-grab hover:bg-blue-50/50 hover:border-blue-300 hover:shadow-md {{ $item->is_completed ? 'bg-gray-50 border-gray-200' : 'bg-white border-gray-300 shadow-sm' }}">
                                    <div class="flex items-center space-x-3">
                                        <!--  完了トグルボタン -->
                                        <form action="{{ route('menu_items.toggle', $item) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-semibold transition {{ $item->is_completed ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                                                title="完了/未完了を切り替え">
                                                {{ $item->is_completed ? '✓' : '未' }}
                                            </button>
                                        </form>

                                        <!-- メニュー名 -->
                                        <div>
                                            <p
                                                class="font-bold {{ $item->is_completed ? ' text-gray-400' : 'text-gray-800' }}">
                                                {{ $item->name }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="text-base px-3 py-1 rounded-xl bg-blue-50 text-blue-700 font-semibold">
                                            {{ $item->duration_minutes }} 分
                                        </span>
                                        <div class="flex flex-col space-y-1">

                                        </div>
                                        <!-- 編集ボタン -->
                                        <a href="{{ route('menu_items.edit', $item) }}"
                                            class="p-2 text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition"
                                            title="編集">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <!--  削除ボタン -->
                                        <form action="{{ route('menu_items.destroy', $item) }}" method="POST"
                                            onsubmit="return confirm('このメニューを削除してもよろしいですか？');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition"
                                                title="削除">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        // DOMContentLoadedを待ち受ける
        document.addEventListener('DOMContentLoaded', function() {
            var el = document.getElementById('sortable-menu');

            // 要素が存在するか確実にチェックする
            if (!el) {
                console.log('sortable-menu 要素が見つかりません（メニューが0件の可能性があります）');
                return;
            }

            console.log('sortable-menu 要素を発見しました！Sortableを初期化します。');

            Sortable.create(el, {
                animation: 150,
                onEnd: function(evt) {
                    var itemIds = [];
                    el.querySelectorAll('[data-id]').forEach(function(item) {
                        itemIds.push(item.getAttribute('data-id'));
                    });

                    console.log('並び替え後のIDs:', itemIds);

                    fetch('{{ route('menu-items.reorder', $schedule) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ids: itemIds
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('並び順を非同期で保存しました', data);
                        })
                        .catch(error => {
                            console.error('並び替えの保存に失敗しました', error);
                        });
                }
            });
        });
    </script>
</x-app-layout>
