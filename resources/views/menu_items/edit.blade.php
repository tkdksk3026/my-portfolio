<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                メニューの編集
            </h2>
            <a href="{{ route('schedules.show', $menuItem->schedule) }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                詳細に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('menu_items.update', $menuItem) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <!-- メニュー名 -->
                    <div>
                        <x-input-label for="name" value="メニュー名" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            :value="old('name', $menuItem->name)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <!-- 所要時間（分） -->
                    <div>
                        <x-input-label for="duration_minutes" value="所要時間（分）" />
                        <x-text-input id="duration_minutes" name="duration_minutes" type="number" min="1"
                            max="480" class="mt-1 block w-full" :value="old('duration_minutes', $menuItem->duration_minutes)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('duration_minutes')" />
                    </div>

                    <div class="pt-4 flex items-center justify-start space-x-3">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            更新する
                        </button>
                        <a href="{{ route('schedules.show', $menuItem->schedule) }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm">
                            キャンセル
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
