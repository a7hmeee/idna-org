<?php

declare(strict_types=1);

namespace App\Livewire\Seo;

use App\Domains\Seo\Models\SeoSetting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class SeoIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public ?int $editingId = null;

    public string $pageKey = '';

    public string $title = '';

    public string $metaDescription = '';

    public string $metaKeywords = '';

    public string $ogTitle = '';

    public string $ogDescription = '';

    public string $ogImage = '';

    public string $twitterTitle = '';

    public string $twitterDescription = '';

    public string $twitterImage = '';

    public string $canonicalUrl = '';

    public string $robots = 'index,follow';

    public string $author = '';

    public bool $isActive = true;

    public function boot(): void
    {
        if (! auth()->user()->can('seo.view')) {
            abort(403);
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function getSeoSettings(): LengthAwarePaginator
    {
        $query = SeoSetting::query()->latest();

        if ($this->search) {
            $query->where('page_key', 'like', "%{$this->search}%");
        }

        return $query->paginate(15);
    }

    public function openCreateModal(): void
    {
        if (! auth()->user()->can('seo.update')) {
            abort(403);
        }

        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function createSeoSetting(): void
    {
        if (! auth()->user()->can('seo.update')) {
            abort(403);
        }

        $validated = $this->validate([
            'pageKey' => ['required', 'string', 'max:255', 'unique:seo_settings,page_key'],
            'title' => ['required', 'string', 'max:255'],
            'metaDescription' => ['nullable', 'string', 'max:500'],
            'metaKeywords' => ['nullable', 'string', 'max:500'],
            'ogTitle' => ['nullable', 'string', 'max:255'],
            'ogDescription' => ['nullable', 'string', 'max:500'],
            'ogImage' => ['nullable', 'string', 'max:500'],
            'twitterTitle' => ['nullable', 'string', 'max:255'],
            'twitterDescription' => ['nullable', 'string', 'max:500'],
            'twitterImage' => ['nullable', 'string', 'max:500'],
            'canonicalUrl' => ['nullable', 'string', 'max:500'],
            'robots' => ['nullable', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'isActive' => ['boolean'],
        ]);

        SeoSetting::create([
            'page_key' => $validated['pageKey'],
            'title' => $validated['title'],
            'meta_description' => $validated['metaDescription'] ?? null,
            'meta_keywords' => $validated['metaKeywords'] ?? null,
            'og_title' => $validated['ogTitle'] ?? null,
            'og_description' => $validated['ogDescription'] ?? null,
            'og_image' => $validated['ogImage'] ?? null,
            'twitter_title' => $validated['twitterTitle'] ?? null,
            'twitter_description' => $validated['twitterDescription'] ?? null,
            'twitter_image' => $validated['twitterImage'] ?? null,
            'canonical_url' => $validated['canonicalUrl'] ?? null,
            'robots' => $validated['robots'] ?? null,
            'author' => $validated['author'] ?? null,
            'is_active' => $validated['isActive'] ?? true,
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('success', 'تم إعداد SEO بنجاح.');
    }

    public function openEditModal(int $id): void
    {
        if (! auth()->user()->can('seo.update')) {
            abort(403);
        }

        $setting = SeoSetting::find($id);

        if ($setting) {
            $this->editingId = $id;
            $this->pageKey = $setting->page_key;
            $this->title = $setting->title;
            $this->metaDescription = $setting->meta_description ?? '';
            $this->metaKeywords = $setting->meta_keywords ?? '';
            $this->ogTitle = $setting->og_title ?? '';
            $this->ogDescription = $setting->og_description ?? '';
            $this->ogImage = $setting->og_image ?? '';
            $this->twitterTitle = $setting->twitter_title ?? '';
            $this->twitterDescription = $setting->twitter_description ?? '';
            $this->twitterImage = $setting->twitter_image ?? '';
            $this->canonicalUrl = $setting->canonical_url ?? '';
            $this->robots = $setting->robots ?? 'index,follow';
            $this->author = $setting->author ?? '';
            $this->isActive = $setting->is_active;
            $this->showEditModal = true;
        }
    }

    public function updateSeoSetting(): void
    {
        if (! auth()->user()->can('seo.update')) {
            abort(403);
        }

        $validated = $this->validate([
            'pageKey' => ['required', 'string', 'max:255', Rule::unique('seo_settings', 'page_key')->ignore($this->editingId)],
            'title' => ['required', 'string', 'max:255'],
            'metaDescription' => ['nullable', 'string', 'max:500'],
            'metaKeywords' => ['nullable', 'string', 'max:500'],
            'ogTitle' => ['nullable', 'string', 'max:255'],
            'ogDescription' => ['nullable', 'string', 'max:500'],
            'ogImage' => ['nullable', 'string', 'max:500'],
            'twitterTitle' => ['nullable', 'string', 'max:255'],
            'twitterDescription' => ['nullable', 'string', 'max:500'],
            'twitterImage' => ['nullable', 'string', 'max:500'],
            'canonicalUrl' => ['nullable', 'string', 'max:500'],
            'robots' => ['nullable', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'isActive' => ['boolean'],
        ]);

        $setting = SeoSetting::find($this->editingId);

        if ($setting) {
            $setting->update([
                'page_key' => $validated['pageKey'],
                'title' => $validated['title'],
                'meta_description' => $validated['metaDescription'] ?? null,
                'meta_keywords' => $validated['metaKeywords'] ?? null,
                'og_title' => $validated['ogTitle'] ?? null,
                'og_description' => $validated['ogDescription'] ?? null,
                'og_image' => $validated['ogImage'] ?? null,
                'twitter_title' => $validated['twitterTitle'] ?? null,
                'twitter_description' => $validated['twitterDescription'] ?? null,
                'twitter_image' => $validated['twitterImage'] ?? null,
                'canonical_url' => $validated['canonicalUrl'] ?? null,
                'robots' => $validated['robots'] ?? null,
                'author' => $validated['author'] ?? null,
                'is_active' => $validated['isActive'] ?? true,
            ]);
        }

        $this->showEditModal = false;
        $this->resetForm();
        session()->flash('success', 'تم تحديث إعداد SEO بنجاح.');
    }

    public function toggleActive(int $id): void
    {
        if (! auth()->user()->can('seo.update')) {
            abort(403);
        }

        $setting = SeoSetting::find($id);

        if ($setting) {
            $setting->update(['is_active' => ! $setting->is_active]);
            session()->flash('success', 'تم تحديث حالة التنشيط بنجاح.');
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

    private function resetForm(): void
    {
        $this->reset([
            'editingId',
            'pageKey',
            'title',
            'metaDescription',
            'metaKeywords',
            'ogTitle',
            'ogDescription',
            'ogImage',
            'twitterTitle',
            'twitterDescription',
            'twitterImage',
            'canonicalUrl',
            'robots',
            'author',
            'isActive',
        ]);
    }

    public function render()
    {
        return view('livewire.seo.index', [
            'seoSettings' => $this->getSeoSettings(),
        ]);
    }
}
