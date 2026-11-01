<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            練習スケジュール管理
        </h2>
    </x-slot>

    <!-- ▼ ここに min-h-screen bg-gray-100 を追加して背景色を統一しています -->
    <div class="py-12 min-h-screen bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <p class="text-gray-700 mb-6">
                        ログインしました。下のボタンよりスケジュール一覧画面へお進みください。
                    </p>

                    <div>
                        <a href="{{ url('/schedules') }}" class="inline-flex items-center px-5 py-3 bg-gray-800 border border-gray-600 rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 hover:border-gray-500 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            スケジュール一覧を見る
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>