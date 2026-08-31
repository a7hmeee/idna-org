<div>
    @if ($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-border">
                <div>
                    <h3 class="text-lg font-bold text-text">اختيار وسائط</h3>
                    <p class="text-xs text-text-tertiary mt-0.5">اختر صورة موجودة من المكتبة المركزية</p>
                </div>
                <button type="button" wire:click="closeModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-4 border-b border-border">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[200px]">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-muted"></i>
                            <input type="text" wire:model.live="search" placeholder="بحث في العنوان، المسار، النص البديل..." class="w-full bg-surface-secondary border border-border rounded-xl pl-10 pr-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                    </div>
                    <select wire:model.live="filterCollection" class="bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="">جميع المجموعات</option>
                        @foreach ($collectionOptions as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterType" class="bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="">جميع الأنواع</option>
                        <option value="image">صور</option>
                        <option value="video">فيديو</option>
                        <option value="document">مستندات</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4">
                @if ($mediaItems->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-16 h-16 rounded-2xl bg-surface-secondary flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="image" class="w-7 h-7 text-text-muted"></i>
                        </div>
                        <p class="text-sm font-bold text-text">لا توجد وسائط مطابقة</p>
                        <p class="text-xs text-text-tertiary mt-1">جرّب تغيير عوامل التصفية أو البحث.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach ($mediaItems as $media)
                            <button type="button"
                                    wire:click="selectMedia({{ $media->id }})"
                                    wire:key="picker-media-{{ $media->id }}"
                                    class="group relative text-start bg-surface-secondary rounded-xl border border-border overflow-hidden hover:border-primary/50 transition-all focus:outline-none focus:ring-2 focus:ring-primary/30">
                                <div class="aspect-square flex items-center justify-center overflow-hidden">
                                    @if ($media->isImage() && $media->fileExists())
                                        <img src="{{ asset('storage/' . $media->path) }}"
                                             alt="{{ $media->alt ?? $media->title ?? '' }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                             loading="lazy" />
                                    @else
                                        <i data-lucide="file" class="w-8 h-8 text-text-muted"></i>
                                    @endif
                                </div>
                                <div class="p-3">
                                    <p class="text-xs font-semibold text-text truncate" title="{{ $media->title ?? $media->path }}">{{ $media->title ?? basename($media->path) }}</p>
                                    <p class="text-[10px] text-text-tertiary mt-0.5">{{ $collectionOptions[$media->collection] ?? $media->collection }}</p>
                                </div>
                                @if ($media->isUsed())
                                    <div class="absolute top-2 left-2">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-primary-light text-[9px] font-bold text-primary">{{ $media->getUsageCount() }} استخدام</span>
                                    </div>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    @if ($mediaItems->hasPages())
                    <div class="mt-4">
                        <x-ui.pagination :paginator="$mediaItems" />
                    </div>
                    @endif
                @endif
            </div>

            <div class="flex items-center justify-end gap-3 p-4 border-t border-border">
                <button type="button" wire:click="closeModal" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
            </div>
        </div>
    </div>
    @endif
</div>
