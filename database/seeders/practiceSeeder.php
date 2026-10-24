<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Schedule;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Hash;

class practiceSeeder extends Seeder
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

        $schedule = Schedule::create([
            'user_id' => $user->id,
            'title' => '10/28 夜 練習',
            'date' => '2026-10-28',
            'start_time' => '19:00',
            'end_time' => '21:00',
        ]);

        $menus = [
            ['name' => 'ウォーミングアップ・ストレッチ', 'duration_minutes' => 10, 'sort_order' => 1, 'is_completed' => true],
            ['name' => 'パス', 'duration_minutes' => 15, 'sort_order' => 2, 'is_completed' => false],
            ['name' => 'シートレシーブ', 'duration_minutes' => 15, 'sort_order' => 3, 'is_completed' => false],
            ['name' => 'サーブカット', 'duration_minutes' => 10, 'sort_order' => 4, 'is_completed' => false],
            ['name' => 'スパイク', 'duration_minutes' => 20, 'sort_order' => 5, 'is_completed' => false],
            ['name' => 'ゲーム', 'duration_minutes' => 50, 'sort_order' => 6, 'is_completed' => false],

        ];

        foreach ($menus as $menu) {
            $schedule->menuItems()->create($menu);

        }
    }
}
