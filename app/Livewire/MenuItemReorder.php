<?php

namespace App\Livewire;

use App\Models\Schedule;
use Livewire\Component;

/**
 * スケジュールのメニュー項目並
 */
class MenuItemReorder extends Component
{
    public Schedule $schedule;

    /**
     * コンポーネントの初期化時に実行され、スケジュールを受け取る
     */
    public function mount(Schedule $schedule)
    {
        $this->schedule = $schedule;

    }

    /**
     * フロントエンド（Livewire sort）からの並び替えイベントを受け取り、順序を更新
     */
    public function updateItemOrder($orderedIds)
    {
        if ($this->schedule->user_id !== auth()->id()) {
            abort(403);
        }

        // 順番をデータベースに保存
        foreach ($orderedIds as $index => $item) {
            // 'value' キーからIDを取得する
            $id = $item['value'] ?? null;

            if ($id) {
                $this->schedule->menuItems()
                    ->where('id', $id)
                    ->update(['sort_order' => $index + 1]);
            }
        }

        $this->schedule->refresh();
    }

    public function render()
    {
        return view('livewire.menu-item-reorder', [
            'menuItems' => $this->schedule->menuItems()->orderBy('sort_order')->get(),
        ]);
    }
}
