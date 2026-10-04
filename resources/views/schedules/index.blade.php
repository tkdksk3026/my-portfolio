HTML
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('練習スケジュール一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">登録済みの練習予定</h3>

                {{-- スケジュールが件もない場合の表示 --}}
                @if($schedules->isEmpty())
                    <p class="text-gray-500">登録されている練習予定はありません。</p>
                @else
                    {{-- スケジュールが存在する場合は一覧表示 --}}
                    <div class="space-y-4">
                        @foreach($schedules as $schedule)
                            <div class="p-4 border rounded-lg bg-gray-50">
                                <div class="text-xs text-gray-500 mb-1">
                                    📅 {{ $schedule->date }} ⏰ {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} 開始
                                </div>
                                <div class="text-lg font-bold text-gray-800">
                                    {{ $schedule->title }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>