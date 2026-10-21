<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Schedule;

class MenuItemReorder extends Component
{
    public Schedule $schedule;

    public function mount(Schedule $schedule)
    {
        $this->schedule = $schedule;

    }

    /**
     * ドラッグ＆ドロップ後にLivewireから自動で呼び出される並び替え処理
     */
    public function updateItemOrder($orderedIds)
    {
        if ($this->schedule->user_id !== auth()->id()) {
            abort(403);
        }
        // 順番をデータベースに保存
        foreach ($orderedIds as $index => $id) {
            $this->schedule->menuItems()
                ->where('id', $id)
                ->update(['sort_order' => $index + 1]);
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
