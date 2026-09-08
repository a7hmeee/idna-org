<div>
    <x-slot name="title">تحسين محركات البحث (SEO)</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">تحسين محركات البحث (SEO)</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة إعدادات SEO لجميع صفحات الموقع</p>
        </div>
        @can('seo.update')
        <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            إضافة إعداد
        </button>
        @endcan
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="p-4 border-b border-border">
            <div class="relative">
                <i data-lucide="search" class="absolute start-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-text-muted"></i>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث بمفتاح الصفحة..." class="w-full bg-surface-secondary border border-border rounded-xl px-10 py-2.5 text-sm text-text placeholder-text-muted focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">مفتاح الصفحة</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">العنوان</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">وصف الميتا</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الروبوتات</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($seoSettings as $setting)
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors" wire:key="seo-{{ $setting->id }}">
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs text-primary font-semibold">{{ $setting->page_key }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-text">{{ $setting->title }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-xs text-text-tertiary line-clamp-2 max-w-[200px]">{{ $setting->meta_description ?? '—' }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="toggleActive({{ $setting->id }})" class="transition-colors @if($setting->is_active) text-success @else text-text-tertiary @endif">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                                        @if($setting->is_active) bg-success/10 text-success @else bg-surface-secondary text-text-tertiary @endif">
                                        @if($setting->is_active) نشط @else غير نشط @endif
                                    </span>
                                </button>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs text-text-tertiary">{{ $setting->robots ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @can('seo.update')
                                    <button wire:click="openEditModal({{ $setting->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i data-lucide="search" class="w-12 h-12 text-text-tertiary/40"></i>
                                    <p class="text-sm text-text-tertiary">لا توجد إعدادات SEO</p>
                                    <p class="text-xs text-text-tertiary">لم يتم العثور على أي إعدادات تحسين محركات البحث.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($seoSettings->hasPages())
            <div class="p-4 border-t border-border">
                <x-ui.pagination :paginator="$seoSettings" />
            </div>
        @endif
    </div>

    {{-- Create Modal --}}
    @if ($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeCreateModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-border sticky top-0 bg-surface rounded-t-2xl z-10">
                <h3 class="text-lg font-bold text-text">إضافة إعداد SEO جديد</h3>
                <button wire:click="closeCreateModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form wire:submit="createSeoSetting" class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">مفتاح الصفحة <span class="text-danger">*</span></label>
                        <input type="text" wire:model="pageKey" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('pageKey') border-danger @enderror" placeholder="مثال: homepage" />
                        @error('pageKey') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">العنوان <span class="text-danger">*</span></label>
                        <input type="text" wire:model="title" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('title') border-danger @enderror" placeholder="عنوان الصفحة" />
                        @error('title') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">وصف الميتا</label>
                    <textarea wire:model="metaDescription" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all resize-none @error('metaDescription') border-danger @enderror" placeholder="وصف مختصر للصفحة"></textarea>
                    @error('metaDescription') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">كلمات الميتا المفتاحية</label>
                    <input type="text" wire:model="metaKeywords" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="كلمة1, كلمة2, كلمة3" />
                </div>

                <div class="border border-border rounded-xl p-4 space-y-3">
                    <p class="text-xs font-bold text-text-secondary uppercase">Open Graph</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">OG العنوان</label>
                            <input type="text" wire:model="ogTitle" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">OG الصورة</label>
                            <input type="text" wire:model="ogImage" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="رابط الصورة" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">OG الوصف</label>
                        <textarea wire:model="ogDescription" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all resize-none"></textarea>
                    </div>
                </div>

                <div class="border border-border rounded-xl p-4 space-y-3">
                    <p class="text-xs font-bold text-text-secondary uppercase">Twitter Card</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">Twitter العنوان</label>
                            <input type="text" wire:model="twitterTitle" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">Twitter الصورة</label>
                            <input type="text" wire:model="twitterImage" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="رابط الصورة" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">Twitter الوصف</label>
                        <textarea wire:model="twitterDescription" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all resize-none"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الرابط المعياري</label>
                        <input type="text" wire:model="canonicalUrl" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="https://..." />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الروبوتات</label>
                        <input type="text" wire:model="robots" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">المؤلف</label>
                        <input type="text" wire:model="author" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="isActive" id="create-is-active" class="w-4 h-4 text-primary border-border rounded focus:ring-primary/20" />
                    <label for="create-is-active" class="text-sm font-semibold text-text">نشط</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                    <button type="button" wire:click="closeCreateModal" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">حفظ</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Edit Modal --}}
    @if ($showEditModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeEditModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-border sticky top-0 bg-surface rounded-t-2xl z-10">
                <h3 class="text-lg font-bold text-text">تعديل إعداد SEO</h3>
                <button wire:click="closeEditModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form wire:submit="updateSeoSetting" class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">مفتاح الصفحة <span class="text-danger">*</span></label>
                        <input type="text" wire:model="pageKey" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('pageKey') border-danger @enderror" />
                        @error('pageKey') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">العنوان <span class="text-danger">*</span></label>
                        <input type="text" wire:model="title" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('title') border-danger @enderror" />
                        @error('title') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">وصف الميتا</label>
                    <textarea wire:model="metaDescription" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">كلمات الميتا المفتاحية</label>
                    <input type="text" wire:model="metaKeywords" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>

                <div class="border border-border rounded-xl p-4 space-y-3">
                    <p class="text-xs font-bold text-text-secondary uppercase">Open Graph</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">OG العنوان</label>
                            <input type="text" wire:model="ogTitle" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">OG الصورة</label>
                            <input type="text" wire:model="ogImage" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">OG الوصف</label>
                        <textarea wire:model="ogDescription" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all resize-none"></textarea>
                    </div>
                </div>

                <div class="border border-border rounded-xl p-4 space-y-3">
                    <p class="text-xs font-bold text-text-secondary uppercase">Twitter Card</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">Twitter العنوان</label>
                            <input type="text" wire:model="twitterTitle" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">Twitter الصورة</label>
                            <input type="text" wire:model="twitterImage" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">Twitter الوصف</label>
                        <textarea wire:model="twitterDescription" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all resize-none"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الرابط المعياري</label>
                        <input type="text" wire:model="canonicalUrl" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الروبوتات</label>
                        <input type="text" wire:model="robots" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">المؤلف</label>
                        <input type="text" wire:model="author" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="isActive" id="edit-is-active" class="w-4 h-4 text-primary border-border rounded focus:ring-primary/20" />
                    <label for="edit-is-active" class="text-sm font-semibold text-text">نشط</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                    <button type="button" wire:click="closeEditModal" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
