<div>

    {{-- ============================================ --}}
    {{-- 1. PAGE CAROUSEL (inherited from Services) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'services',
        'pageTitle' => $category->name,
        'pageSubtitle' => $category->description ?? null,
        'pageBadge' => ($category->services_count ?? 0) . ' خدمة متاحة',
        'pageBadgeIcon' => $category->icon ?? 'layers',
        'compact' => true,
    ])

    {{-- ============================================ --}}
    {{-- 2. SEARCH & FILTERS --}}
    {{-- ============================================ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm" style="box-shadow:0 1px 2px rgba(0,0,0,0.03),0 1px 3px rgba(0,0,0,0.04);">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 relative">
                        <i data-lucide="search" class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        <input type="text" wire:model.live.debounce.400ms="search"
                               aria-label="ابحث ضمن{{ ' ' . $category->name }}"
                               placeholder="ابحث ضمن {{ $category->name }}..."
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pr-10 pl-4 text-sm text-gray-900 outline-none transition-all duration-200"
                               onfocus="this.style.borderColor='#0F6A3D';this.style.boxShadow='0 0 0 3px rgba(15,106,61,0.1)'"
                               onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                    </div>

                    <select wire:model.live="sortField"
                            class="bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm text-gray-900 outline-none transition-all duration-200 min-w-[160px]"
                            onfocus="this.style.borderColor='#0F6A3D';this.style.boxShadow='0 0 0 3px rgba(15,106,61,0.1)'"
                            onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                        <option value="sort_order">الترتيب الافتراضي</option>
                        <option value="name">ترتيب أبجدي</option>
                        <option value="views_count">الأكثر مشاهدة</option>
                        <option value="created_at">الأحدث</option>
                    </select>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                    <p class="text-xs text-gray-400 m-0">
                        @if (strlen($search) >= 2)
                            نتائج البحث عن "{{ $search }}": {{ $services->total() ?? 0 }} خدمة
                        @else
                            <span class="font-bold text-gray-700">{{ $services->total() ?? 0 }}</span> خدمة متاحة في {{ $category->name }}
                        @endif
                    </p>
                    @if (strlen($search) >= 2)
                        <button wire:click="$set('search', '')"
                                class="text-xs font-bold text-primary bg-primary/5 hover:bg-primary/10 px-3 py-1.5 rounded-lg transition-all border-none cursor-pointer">
                            مسح البحث
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- 4. SERVICES GRID --}}
    {{-- ============================================ --}}
    <section class="pb-16 lg:pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($services->isEmpty())
                <div class="text-center py-16 px-6 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="w-16 h-16 rounded-2xl bg-primary/5 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="file-x" class="w-8 h-8 text-primary"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        @if (strlen($search) >= 2)
                            لا توجد نتائج للبحث
                        @else
                            لا توجد خدمات متاحة حالياً ضمن هذا التصنيف
                        @endif
                    </h3>
                    <p class="text-sm text-gray-400 max-w-sm mx-auto mb-6">
                        @if (strlen($search) >= 2)
                            لم نتمكن من العثور على خدمات تطابق بحثك. جرّب كلمات بحث مختلفة.
                        @else
                            لا توجد خدمات متاحة ضمن هذا التصنيف حالياً. يرجى العودة لاحقاً.
                        @endif
                    </p>
                    @if (strlen($search) >= 2)
                        <button wire:click="$set('search', '')"
                                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary text-white text-sm font-bold border-none cursor-pointer hover:bg-primary-dark transition-all">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            مسح البحث
                        </button>
                    @else
                        <a href="{{ route('public.services.index') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary text-white text-sm font-bold no-underline hover:bg-primary-dark transition-all">
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            العودة إلى بوابة الخدمات
                        </a>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($services as $service)
                        <x-services.service-card
                            :service="$service"
                            :route="route('public.services.show', ['category' => $category->slug, 'service' => $service->slug])"
                            :showCategory="false"
                        />
                    @endforeach
                </div>

                @if ($services->hasPages())
                    <div class="mt-10">
                        <x-ui.pagination :paginator="$services" />
                    </div>
                @endif
            @endif
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- 5. PORTAL CTA --}}
    {{-- ============================================ --}}
    <x-services.portal-cta :portalUrl="$portalUrl ?? null" />

</div>
