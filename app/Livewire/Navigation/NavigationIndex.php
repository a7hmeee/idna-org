<?php

declare(strict_types=1);

namespace App\Livewire\Navigation;

use App\Domains\Navigation\Models\NavigationItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class NavigationIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $section = '';

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $label = '';

    public string $url = '';

    public string $routeName = '';

    public ?array $routeParams = null;

    public string $icon = '';

    public ?int $parentId = null;

    public int $sortOrder = 0;

    public bool $isActive = true;

    public bool $isExternal = false;

    public string $target = '_self';

    public string $sectionField = 'main';

    public bool $openInNewTab = false;

    public bool $canView = false;

    public bool $canCreate = false;

    public bool $canUpdate = false;

    public bool $canDelete = false;

    public function boot(): void
    {
        $this->canView = auth()->user()->can('navigation.view');
        $this->canCreate = auth()->user()->can('navigation.create');
        $this->canUpdate = auth()->user()->can('navigation.update');
        $this->canDelete = auth()->user()->can('navigation.delete');

        if (! $this->canView) {
            abort(403);
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSection(): void
    {
        $this->resetPage();
    }

    public function getItems(): LengthAwarePaginator
    {
        $query = NavigationItem::query()->with('children');

        if ($this->search !== '') {
            $query->where('label', 'like', "%{$this->search}%");
        }

        if ($this->section !== '') {
            $query->where('section', $this->section);
        }

        $query->whereNull('parent_id')->orderBy('sort_order');

        return $query->paginate(perPage: 25, pageName: 'page');
    }

    public function openCreateModal(?int $parentId = null): void
    {
        if (! $this->canCreate) {
            abort(403);
        }

        $this->resetForm();
        $this->parentId = $parentId;
        $this->showCreateModal = true;
    }

    public function createItem(): void
    {
        if (! $this->canCreate) {
            abort(403);
        }

        $validated = $this->validate([
            'label' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:500'],
            'routeName' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sortOrder' => ['required', 'integer', 'min:0'],
            'isActive' => ['boolean'],
            'isExternal' => ['boolean'],
            'target' => ['required', 'string', Rule::in(['_self', '_blank'])],
            'sectionField' => ['required', 'string', Rule::in(['main', 'top_bar', 'mobile'])],
            'openInNewTab' => ['boolean'],
        ]);

        NavigationItem::create([
            'label' => $validated['label'],
            'url' => $validated['url'] ?: null,
            'route_name' => $validated['routeName'] ?: null,
            'route_params' => null,
            'icon' => $validated['icon'] ?: null,
            'parent_id' => $this->parentId,
            'sort_order' => $validated['sortOrder'],
            'is_active' => $validated['isActive'],
            'is_external' => $validated['isExternal'],
            'target' => $validated['target'],
            'section' => $validated['sectionField'],
            'open_in_new_tab' => $validated['openInNewTab'],
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

        $item = NavigationItem::find($id);

        if ($item) {
            $this->editingId = $id;
            $this->label = $item->label;
            $this->url = $item->url ?? '';
            $this->routeName = $item->route_name ?? '';
            $this->icon = $item->icon ?? '';
            $this->parentId = $item->parent_id;
            $this->sortOrder = $item->sort_order;
            $this->isActive = $item->is_active;
            $this->isExternal = $item->is_external;
            $this->target = $item->target;
            $this->sectionField = $item->section;
            $this->openInNewTab = $item->open_in_new_tab;
            $this->showEditModal = true;
        }
    }

    public function updateItem(): void
    {
        if (! $this->canUpdate) {
            abort(403);
        }

        $validated = $this->validate([
            'label' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:500'],
            'routeName' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sortOrder' => ['required', 'integer', 'min:0'],
            'isActive' => ['boolean'],
            'isExternal' => ['boolean'],
            'target' => ['required', 'string', Rule::in(['_self', '_blank'])],
            'sectionField' => ['required', 'string', Rule::in(['main', 'top_bar', 'mobile'])],
            'openInNewTab' => ['boolean'],
        ]);

        $item = NavigationItem::find($this->editingId);

        if ($item) {
            $item->update([
                'label' => $validated['label'],
                'url' => $validated['url'] ?: null,
                'route_name' => $validated['routeName'] ?: null,
                'icon' => $validated['icon'] ?: null,
                'sort_order' => $validated['sortOrder'],
                'is_active' => $validated['isActive'],
                'is_external' => $validated['isExternal'],
                'target' => $validated['target'],
                'section' => $validated['sectionField'],
                'open_in_new_tab' => $validated['openInNewTab'],
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

        $item = NavigationItem::find($this->deletingId);

        if ($item) {
            $item->children()->delete();
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

        $item = NavigationItem::find($id);

        if ($item) {
            $item->update(['is_active' => ! $item->is_active]);
            session()->flash('success', 'تم تغيير حالة العنصر بنجاح.');
        }
    }

    public function moveUp(int $id): void
    {
        if (! $this->canUpdate) {
            abort(403);
        }

        $item = NavigationItem::find($id);

        if ($item && $item->sort_order > 0) {
            $sibling = NavigationItem::where('parent_id', $item->parent_id)
                ->where('section', $item->section)
                ->where('sort_order', $item->sort_order - 1)
                ->first();

            if ($sibling) {
                $sibling->update(['sort_order' => $item->sort_order]);
                $item->update(['sort_order' => $item->sort_order - 1]);
            }
        }
    }

    public function moveDown(int $id): void
    {
        if (! $this->canUpdate) {
            abort(403);
        }

        $item = NavigationItem::find($id);

        if ($item) {
            $sibling = NavigationItem::where('parent_id', $item->parent_id)
                ->where('section', $item->section)
                ->where('sort_order', $item->sort_order + 1)
                ->first();

            if ($sibling) {
                $sibling->update(['sort_order' => $item->sort_order]);
                $item->update(['sort_order' => $item->sort_order + 1]);
            }
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
            'label', 'url', 'routeName', 'icon', 'parentId',
            'sortOrder', 'isActive', 'isExternal', 'target',
            'sectionField', 'openInNewTab', 'editingId', 'deletingId',
        ]);

        $this->isActive = true;
        $this->isExternal = false;
        $this->target = '_self';
        $this->sectionField = 'main';
        $this->openInNewTab = false;
        $this->sortOrder = 0;
    }

    public function render()
    {
        return view('livewire.navigation.index', [
            'items' => $this->getItems(),
            'parentItems' => NavigationItem::whereNull('parent_id')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
