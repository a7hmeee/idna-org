<?php

declare(strict_types=1);

namespace App\Livewire\Notifications;

use App\Domains\Notifications\Models\DashboardNotification;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class NotificationCenter extends Component
{
    use WithPagination;

    public int $unreadCount = 0;

    public bool $showDetailModal = false;

    public ?DashboardNotification $selectedNotification = null;

    public function boot(): void
    {
        if (! auth()->user()->can('notifications.view')) {
            abort(403);
        }
    }

    public function mount(): void
    {
        $this->loadUnreadCount();
    }

    public function loadUnreadCount(): void
    {
        $this->unreadCount = DashboardNotification::getUnreadCount(auth()->id());
    }

    public function getNotifications()
    {
        return DashboardNotification::forUser(auth()->id())
            ->latestFirst()
            ->paginate(15);
    }

    public function viewDetail(int $id): void
    {
        $notification = DashboardNotification::find($id);

        if ($notification && $notification->user_id === auth()->id()) {
            $this->selectedNotification = $notification;
            $this->showDetailModal = true;

            if (! $notification->is_read) {
                $notification->markAsRead();
                $this->loadUnreadCount();
            }
        }
    }

    public function markAsRead(int $id): void
    {
        $notification = DashboardNotification::find($id);

        if ($notification && $notification->user_id === auth()->id()) {
            $notification->markAsRead();
            $this->loadUnreadCount();
            session()->flash('success', 'تم تحديد الإشعار كمقروء.');
        }
    }

    public function markAllAsRead(): void
    {
        DashboardNotification::markAllAsRead(auth()->id());
        $this->loadUnreadCount();
        session()->flash('success', 'تم تحديد جميع الإشعارات كمقروءة.');
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedNotification = null;
    }

    public function render()
    {
        return view('livewire.notifications.center', [
            'notifications' => $this->getNotifications(),
        ]);
    }
}
