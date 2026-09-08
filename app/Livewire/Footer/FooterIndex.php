<?php

declare(strict_types=1);

namespace App\Livewire\Footer;

use App\Domains\Footer\Models\FooterItem;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class FooterIndex extends Component
{
    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $columnKey = 'quick_links';

    public string $columnTitle = '';

    public string $label = '';

    public string $url = '';

    public string $routeName = '';

    public string $icon = '';

    public int $sortOrder = 0;

    public bool $isActive = true;

    public bool $isExternal = false;

    public string $target = '_self';

    public bool $canView = false;

    public bool $canCreate = false;

    public bool $canUpdate = false;

    public bool $canDelete = false;

    private const COLUMN_KEYS = [
        'quick_links' => 'روابط سريعة',
        'services' => 'الخدمات الإلكترونية',
        'contact' => 'تواصل معنا',
    ];

    public function boot(): void
    {
        $this->canView = auth()->user()->can('footer.view');
        $this->canCreate = auth()->user()->can('footer.create');
        $this->canUpdate = auth()->user()->can('footer.update');
        $this->canDelete = auth()->user()->can('footer.delete');

        if (! $this->canView) {
            abort(403);
        }
    }

    public function getItems(): Collection
    {
        return FooterItem::query()
            ->orderBy('column_key')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('column_key');
    }

    public function openCreateModal(string $columnKey = 'quick_links'): void
    {
        if (! $this->canCreate) {
            abort(403);
        }

        $this->resetForm();
        $this->columnKey = $columnKey;
        $this->columnTitle = self::COLUMN_KEYS[$columnKey] ?? $columnKey;
        $this->showCreateModal = true;
    }

    public function createItem(): void
    {
        if (! $this->canCreate) {
            abort(403);
        }

        $validated = $this->validate([
            'columnKey' => ['required', 'string', 'max:255', Rule::in(array_keys(self::COLUMN_KEYS))],
            'columnTitle' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:500'],
            'routeName' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sortOrder' => ['required', 'integer', 'min:0'],
            'isActive' => ['boolean'],
            'isExternal' => ['boolean'],
            'target' => ['required', 'string', Rule::in(['_self', '_blank'])],
        ]);

        FooterItem::create([
            'column_key' => $validated['columnKey'],
            'column_title' => $validated['columnTitle'],
            'label' => $validated['label'],
            'url' => $validated['url'] ?: null,
            'route_name' => $validated['routeName'] ?: null,
            'icon' => $validated['icon'] ?: null,
            'sort_order' => $validated['sortOrder'],
            'is_active' => $validated['isActive'],
            'is_external' => $validated['isExternal'],
            'target' => $validated['target'],
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('success', 'تم إنشاء العنصر بنجاح.');
    }

    public function openEditModal(int $id): void
    {
        if (! $this->canUpdate) {
            abort(403);
        }

        $item = FooterItem::find($id);

        if ($item) {
            $this->editingId = $id;
            $this->columnKey = $item->column_key;
            $this->columnTitle = $item->column_title;
            $this->label = $item->label;
            $this->url = $item->url ?? '';
            $this->routeName = $item->route_name ?? '';
            $this->icon = $item->icon ?? '';
            $this->sortOrder = $item->sort_order;
            $this->isActive = $item->is_active;
            $this->isExternal = $item->is_external;
            $this->target = $item->target;
            $this->showEditModal = true;
        }
    }

    public function updateItem(): void
    {
        if (! $this->canUpdate) {
            abort(403);
        }

        $validated = $this->validate([
            'columnKey' => ['required', 'string', 'max:255', Rule::in(array_keys(self::COLUMN_KEYS))],
            'columnTitle' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:500'],
            'routeName' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sortOrder' => ['required', 'integer', 'min:0'],
            'isActive' => ['boolean'],
            'isExternal' => ['boolean'],
            'target' => ['required', 'string', Rule::in(['_self', '_blank'])],
        ]);

        $item = FooterItem::find($this->editingId);

        if ($item) {
            $item->update([
                'column_key' => $validated['columnKey'],
                'column_title' => $validated['columnTitle'],
                'label' => $validated['label'],
                'url' => $validated['url'] ?: null,
                'route_name' => $validated['routeName'] ?: null,
                'icon' => $validated['icon'] ?: null,
                'sort_order' => $validated['sortOrder'],
                'is_active' => $validated['isActive'],
                'is_external' => $validated['isExternal'],
                'target' => $validated['target'],
            ]);
        }

        $this->showEditModal = false;
        $this->resetForm();
        session()->flash('success', 'تم تحديث العنصر بنجاح.');
    }

    public function confirmDelete(int $id): void
    {
        if (! $this->canDelete) {
            abort(403);
        }

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteItem(): void
    {
        if (! $this->canDelete) {
            abort(403);
        }

        $item = FooterItem::find($this->deletingId);

        if ($item) {
            $item->delete();
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', 'تم حذف العنصر بنجاح.');
    }

    public function toggleActive(int $id): void
    {
        if (! $this->canUpdate) {
            abort(403);
        }

        $item = FooterItem::find($id);

        if ($item) {
            $item->update(['is_active' => ! $item->is_active]);
            session()->flash('success', 'تم تغيير حالة العنصر بنجاح.');
        }
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
    }

    private function resetForm(): void
    {
        $this->reset([
            'columnKey', 'columnTitle', 'label', 'url', 'routeName', 'icon',
            'sortOrder', 'isActive', 'isExternal', 'target', 'editingId', 'deletingId',
        ]);

        $this->columnKey = 'quick_links';
        $this->isActive = true;
        $this->isExternal = false;
        $this->target = '_self';
        $this->sortOrder = 0;
    }

    public function render()
    {
        return view('livewire.footer.index', [
            'items' => $this->getItems(),
            'columnKeys' => self::COLUMN_KEYS,
        ]);
    }
}
