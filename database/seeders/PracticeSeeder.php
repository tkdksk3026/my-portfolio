<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PracticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'テストユーザー',
                'password' => Hash::make('password'),
            ]
        );

        // 1つ目のスケジュール
        $schedule1 = Schedule::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => '2026-10-25',
                'title' => '10/25 休日 昼 練習',
            ],
            [
                'start_time' => '13:00',
                'end_time' => '16:00',
            ]
        );

        // すでにスケジュールが存在しない（または新規作成された）場合のみメニューを登録
        if ($schedule1->menuItems()->count() === 0) {
            $menus1 = [
                ['name' => 'ウォーミングアップ', 'duration_minutes' => 15, 'sort_order' => 1, 'is_completed' => true],
                ['name' => 'パス', 'duration_minutes' => 15, 'sort_order' => 2, 'is_completed' => true],
                ['name' => 'シートレシーブ', 'duration_minutes' => 20, 'sort_order' => 3, 'is_completed' => true],
                ['name' => 'サーブカット', 'duration_minutes' => 20, 'sort_order' => 4, 'is_completed' => true],
                ['name' => '二段トス', 'duration_minutes' => 30, 'sort_order' => 5, 'is_completed' => true],
                ['name' => 'スパイク', 'duration_minutes' => 20, 'sort_order' => 6, 'is_completed' => true],
                ['name' => 'ゲーム', 'duration_minutes' => 60, 'sort_order' => 7, 'is_completed' => true],
            ];

            foreach ($menus1 as $menu) {
                $schedule1->menuItems()->create($menu);
            }
        }

        // 2つ目のスケジュール
        $schedule2 = Schedule::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => '2026-11-13',
                'title' => '11/13 平日 夜 練習',
            ],
            [
                'start_time' => '19:00',
                'end_time' => '21:00',
            ]
        );

        if ($schedule2->menuItems()->count() === 0) {
            $menus2 = [
                ['name' => 'ウォーミングアップ', 'duration_minutes' => 10, 'sort_order' => 1, 'is_completed' => false],
                ['name' => 'パス', 'duration_minutes' => 20, 'sort_order' => 2, 'is_completed' => false],
                ['name' => 'シートレシーブ', 'duration_minutes' => 30, 'sort_order' => 3, 'is_completed' => false],
                ['name' => 'ゲーム', 'duration_minutes' => 60, 'sort_order' => 4, 'is_completed' => false],
            ];

            foreach ($menus2 as $menu) {
                $schedule2->menuItems()->create($menu);
            }
        }
    }
}