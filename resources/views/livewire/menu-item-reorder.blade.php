<div>
    @if ($menuItems->isEmpty())
        <p class="text-gray-500 text-sm py-4 text-center">
            まだ練習メニューが登録されていません。<br>左のフォームから追加してください。
        </p>
    @else
        <!-- wire:sortable で並び替えのイベントを指定 -->
        <div wire:sortable="updateItemOrder" class="space-y-3">
            @foreach ($menuItems as $item)
                <div wire:sortable.item="{{ $item->id }}" wire:key="menu-item-{{ $item->id }}"
                    class="flex items-center justify-between p-4 rounded-lg border transition-colors duration-150 cursor-grab hover:bg-blue-50/50 hover:border-blue-300 hover:shadow-md {{ $item->is_completed ? 'bg-gray-50 border-gray-200' : 'bg-white border-gray-300 shadow-sm' }}">
                    
                    <div class="flex items-center space-x-3">
                        <!-- 完了トグルボタン -->
                        <form action="{{ route('menu_items.toggle', $item) }}" method="POST" class="flex items-center m-0">
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
                            <p class="font-bold {{ $item->is_completed ? ' text-gray-400' : 'text-gray-800' }}">
                                {{ $item->name }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <span class="text-base px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 font-semibold">
                            {{ $item->duration_minutes }} 分
                        </span>

                        <!-- 編集ボタン -->
                        <a href="{{ route('menu_items.edit', $item) }}"
                            class="p-2 text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition"
                            title="編集">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </a>

                        <!-- 削除ボタン -->
                        <form action="{{ route('menu_items.destroy', $item) }}" method="POST"
                            onsubmit="return confirm('このメニューを削除してもよろしいですか？');"class="flex items-center m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="p-2 text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition"
                                title="削除">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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