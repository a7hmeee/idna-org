<div>
    <x-slot name="title">الوسائط</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">الوسائط</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة الشعار والصور والملفات المرفقة</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1 bg-surface-secondary rounded-xl p-1">
                <button wire:click="$set('viewMode', 'grid')" class="p-2 rounded-lg {{ $viewMode === 'grid' ? 'bg-surface text-primary' : 'text-text-tertiary hover:text-text' }}" title="عرض شبكي">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i>
                </button>
                <button wire:click="$set('viewMode', 'list')" class="p-2 rounded-lg {{ $viewMode === 'list' ? 'bg-surface text-primary' : 'text-text-tertiary hover:text-text' }}" title="عرض قائمة">
                    <i data-lucide="list" class="w-4 h-4"></i>
                </button>
            </div>
            @can('createMedia', App\Domains\Municipality\Models\Municipality::class)
            <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                <i data-lucide="plus" class="w-4 h-4"></i>
                إضافة مرفق
            </button>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
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
                <select wire:model.live="filterStatus" class="bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الحالات</option>
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
                <select wire:model.live="filterUsage" class="bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الاستخدامات</option>
                    <option value="used">مستخدمة</option>
                    <option value="unused">غير مستخدمة</option>
                </select>
                <select wire:model.live="sortBy" class="bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="created">الأحدث</option>
                    <option value="name">الاسم</option>
                    <option value="size">الحجم</option>
                    <option value="collection">المجموعة</option>
                </select>
            </div>
        </div>

        @if ($mediaItems->isEmpty())
            <div class="text-center py-16">
                <div class="w-16 h-16 rounded-2xl bg-surface-secondary flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="image" class="w-7 h-7 text-text-muted"></i>
                </div>
                <p class="text-sm font-bold text-text">لا توجد وسائط</p>
                <p class="text-xs text-text-tertiary mt-1">أضف مرفقاً جديداً للبدء.</p>
            </div>
        @else
            @if ($viewMode === 'grid')
                <div class="p-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        @foreach ($mediaItems as $media)
                            <div wire:key="media-{{ $media->id }}" class="group relative bg-surface-secondary rounded-xl border border-border overflow-hidden hover:border-primary/50 transition-all">
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
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="flex items-center gap-1 bg-surface/90 backdrop-blur-sm rounded-lg p-1 shadow-sm">
                                        <button wire:click="openPreviewModal({{ $media->id }})" class="p-1.5 rounded-md hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="معاينة">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @can('updateMedia', App\Domains\Municipality\Models\Municipality::class)
                                        <button wire:click="openEditModal({{ $media->id }})" class="p-1.5 rounded-md hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @endcan
                                        <button wire:click="copyUrl({{ $media->id }})" class="p-1.5 rounded-md hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="نسخ الرابط">
                                            <i data-lucide="link" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                </div>
                                @if ($media->isUsed())
                                    <div class="absolute top-2 left-2">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-primary-light text-[9px] font-bold text-primary">{{ $media->getUsageCount() }} استخدام</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-border bg-background/50">
                                <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">المرفق</th>
                                <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">المجموعة</th>
                                <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">الحجم</th>
                                <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">الأبعاد</th>
                                <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">الحالة</th>
                                <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mediaItems as $media)
                            <tr wire:key="media-{{ $media->id }}" class="border-b border-border last:border-0 hover:bg-background/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-surface-secondary flex items-center justify-center overflow-hidden shrink-0">
                                            @if ($media->isImage() && $media->fileExists())
                                                <img src="{{ asset('storage/' . $media->path) }}"
                                                     alt="{{ $media->alt ?? $media->title ?? '' }}"
                                                     class="w-full h-full object-cover"
                                                     loading="lazy" />
                                            @else
                                                <i data-lucide="file" class="w-5 h-5 text-text-muted"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-text truncate max-w-[200px]">{{ $media->title ?? $media->path }}</p>
                                            @if ($media->alt)
                                            <p class="text-[11px] text-text-tertiary truncate max-w-[200px]">{{ $media->alt }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-surface-secondary text-[11px] font-semibold text-text-secondary whitespace-nowrap">{{ $collectionOptions[$media->collection] ?? $media->collection }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-text-secondary whitespace-nowrap">
                                    {{ $media->formatted_size }}
                                </td>
                                <td class="px-6 py-4 text-sm text-text-secondary whitespace-nowrap">
                                    {{ $media->formatted_dimensions }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($media->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-success-light text-[11px] font-semibold text-success">نشط</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-surface-secondary text-[11px] font-semibold text-text-muted">غير نشط</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <button wire:click="openPreviewModal({{ $media->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="معاينة">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                        <button wire:click="openDetailsModal({{ $media->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="التفاصيل">
                                            <i data-lucide="info" class="w-4 h-4"></i>
                                        </button>
                                        <button wire:click="openUsageModal({{ $media->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="أين تُستخدم">
                                            <i data-lucide="git-branch" class="w-4 h-4"></i>
                                        </button>
                                        <button wire:click="copyUrl({{ $media->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="نسخ الرابط">
                                            <i data-lucide="link" class="w-4 h-4"></i>
                                        </button>
                                        @can('updateMedia', App\Domains\Municipality\Models\Municipality::class)
                                        <button wire:click="replaceMedia({{ $media->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="استبدال">
                                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                        </button>
                                        <button wire:click="openEditModal({{ $media->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </button>
                                        @endcan
                                        @can('deleteMedia', App\Domains\Municipality\Models\Municipality::class)
                                        <button wire:click="confirmDelete({{ $media->id }})" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors" title="حذف">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($mediaItems->hasPages())
            <div class="px-6 py-4 border-t border-border">
                <x-ui.pagination :paginator="$mediaItems" />
            </div>
            @endif
        @endif
    </div>

    @if ($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeForm"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-border">
                <h3 class="text-lg font-bold text-text">{{ $editingId ? 'تعديل المرفق' : 'إضافة مرفق' }}</h3>
                <button wire:click="closeForm" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form wire:submit="save" class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">المجموعة <span class="text-danger">*</span></label>
                    <select wire:model.live="collection" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('collection') border-danger @enderror">
                        @foreach ($collectionOptions as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('collection') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الملف <span class="text-danger">*</span></label>
                    <div class="relative">
                        <input type="file"
                               wire:model.live="file"
                               accept="image/*,.pdf,.doc,.docx"
                               class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark cursor-pointer @error('file') border-danger @enderror" />
                        <div wire:loading wire:target="file" class="absolute left-3 top-1/2 -translate-y-1/2">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin text-primary"></i>
                        </div>
                    </div>
                    @error('file') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror

                    @if ($previewUrl)
                    <div class="mt-3 relative inline-block">
                        @if (str_ends_with($previewUrl, '.pdf') || str_ends_with($previewUrl, '.doc') || str_ends_with($previewUrl, '.docx'))
                            <div class="flex items-center gap-2 p-3 rounded-xl bg-surface-secondary">
                                <i data-lucide="file-text" class="w-6 h-6 text-text-muted"></i>
                                <span class="text-sm text-text-secondary">{{ $file?->getClientOriginalName() ?? 'ملف' }}</span>
                            </div>
                        @else
                            <img src="{{ $previewUrl }}"
                                 alt="Preview"
                                 class="w-32 h-32 rounded-xl object-cover border border-border"
                                 loading="lazy" />
                        @endif
                        <button type="button"
                                wire:click="removeFilePreview"
                                class="absolute -top-2 -right-2 p-1 rounded-full bg-danger text-white shadow-sm hover:bg-danger/90 transition-colors"
                                title="إزالة">
                            <i data-lucide="x" class="w-3 h-3"></i>
                        </button>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">العنوان</label>
                        <input type="text" wire:model="title" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('title') border-danger @enderror" />
                        @error('title') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">النص البديل</label>
                        <input type="text" wire:model="alt" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('alt') border-danger @enderror" />
                        @error('alt') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">ترتيب العرض</label>
                        <input type="number" wire:model="displayOrder" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('displayOrder') border-danger @enderror" />
                        @error('displayOrder') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-2">نشط</label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="isActive" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20" />
                            <span class="text-sm text-text-secondary">مفعل</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                    <button type="button" wire:click="closeForm" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                        <span wire:loading.remove wire:target="save">{{ $editingId ? 'حفظ التعديلات' : 'إضافة' }}</span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                            جاري الرفع...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeDeleteModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-md">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-danger-light flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-danger"></i>
                </div>
                <h3 class="text-lg font-bold text-text mb-2">حذف المرفق</h3>
                <p class="text-sm text-text-tertiary">هل أنت متأكد من حذف هذا المرفق؟</p>
                <p class="text-xs text-text-muted mt-2">سيتم حذف الملف بشكل دائم من التخزين.</p>
            </div>
            <div class="flex items-center justify-center gap-3 px-6 pb-6">
                <button wire:click="closeDeleteModal" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                <button wire:click="delete" class="px-5 py-2.5 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors" wire:loading.attr="disabled">
                    <span wire:loading.remove>نعم، حذف</span>
                    <span wire:loading class="inline-flex items-center gap-2">
                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        جاري الحذف...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if ($showWarningModal && $warningId)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeWarningModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-md">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-danger-light flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-danger"></i>
                </div>
                <h3 class="text-lg font-bold text-text mb-2">تحذير: الوسائط قيد الاستخدام</h3>
                <p class="text-sm text-text-tertiary">هذه الوسائط مستخدمة في {{ $warningId ? Media::find($warningId)?->getUsageCount() : 0 }} مكان.</p>
                <p class="text-xs text-text-muted mt-2">حذفها قد يؤثر على الأماكن التي تستخدمها. احذف فقط إذا كنت متأكداً.</p>
            </div>
            <div class="flex items-center justify-center gap-3 px-6 pb-6">
                <button wire:click="closeWarningModal" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                <button wire:click="openUsageModal({{ $warningId }})" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-primary hover:bg-primary-light transition-colors">عرض الأماكن</button>
                <button wire:click="deleteAnyway" class="px-5 py-2.5 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors" wire:loading.attr="disabled">
                    <span wire:loading.remove>حذف على أي حال</span>
                    <span wire:loading class="inline-flex items-center gap-2">
                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        جاري الحذف...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if ($showDetailsModal && $selectedMedia)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeDetailsModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-border">
                <h3 class="text-lg font-bold text-text">تفاصيل المرفق</h3>
                <button wire:click="closeDetailsModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-5">
                <div class="flex items-start gap-6">
                    <div class="w-32 h-32 rounded-xl bg-surface-secondary flex items-center justify-center overflow-hidden shrink-0">
                        @if ($selectedMedia->isImage() && $selectedMedia->fileExists())
                            <img src="{{ asset('storage/' . $selectedMedia->path) }}"
                                 alt="{{ $selectedMedia->alt ?? $selectedMedia->title ?? '' }}"
                                 class="w-full h-full object-cover"
                                 loading="lazy" />
                        @else
                            <i data-lucide="file" class="w-10 h-10 text-text-muted"></i>
                        @endif
                    </div>
                    <div class="flex-1 space-y-3">
                        <div>
                            <label class="text-xs font-bold text-text-tertiary">العنوان</label>
                            <p class="text-sm text-text">{{ $selectedMedia->title ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-text-tertiary">المسار</label>
                            <p class="text-sm text-text font-mono" dir="ltr">{{ $selectedMedia->path }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-text-tertiary">المجموعة</label>
                                <p class="text-sm text-text">{{ $collectionOptions[$selectedMedia->collection] ?? $selectedMedia->collection }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-text-tertiary">النوع</label>
                                <p class="text-sm text-text">{{ $selectedMedia->mime_type ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-text-tertiary">الحجم</label>
                                <p class="text-sm text-text">{{ $selectedMedia->formatted_size }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-text-tertiary">الأبعاد</label>
                                <p class="text-sm text-text">{{ $selectedMedia->formatted_dimensions }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-text-tertiary">النص البديل</label>
                            <p class="text-sm text-text">{{ $selectedMedia->alt ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-text-tertiary">تاريخ الإنشاء</label>
                            <p class="text-sm text-text">{{ $selectedMedia->created_at?->format('Y-m-d H:i') ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 p-5 border-t border-border">
                <button wire:click="closeDetailsModal" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إغلاق</button>
                <a href="{{ asset('storage/' . $selectedMedia->path) }}" target="_blank" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-primary hover:bg-primary-light transition-colors">فتح في نافذة جديدة</a>
            </div>
        </div>
    </div>
    @endif

    @if ($showUsageModal && $usageMedia)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeUsageModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-border">
                <h3 class="text-lg font-bold text-text">أين تُستخدم هذه الصورة؟</h3>
                <button wire:click="closeUsageModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-5">
                @php $usageLocations = $usageMedia->getUsageLocations(); @endphp
                @if (empty($usageLocations))
                    <div class="text-center py-8">
                        <div class="w-12 h-12 rounded-full bg-surface-secondary flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="check-circle" class="w-6 h-6 text-success"></i>
                        </div>
                        <p class="text-sm font-semibold text-text">لم يتم استخدام هذه الصورة</p>
                        <p class="text-xs text-text-tertiary mt-1">يمكنك حذفها بأمان.</p>
                    </div>
                @else
                    <p class="text-sm text-text-tertiary mb-4">تُستخدم في {{ count($usageLocations) }} مكان:</p>
                    <div class="space-y-2">
                        @foreach ($usageLocations as $location)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-secondary">
                                <div class="w-8 h-8 rounded-lg bg-primary-light flex items-center justify-center shrink-0">
                                    <i data-lucide="git-branch" class="w-4 h-4 text-primary"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-text">{{ $location['context'] }}</p>
                                    <p class="text-[11px] text-text-tertiary">Model: {{ $location['model'] }} | Field: {{ $location['field'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="flex items-center justify-end gap-3 p-5 border-t border-border">
                <button wire:click="closeUsageModal" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إغلاق</button>
            </div>
        </div>
    </div>
    @endif

    @if ($showPreviewModal && $previewMedia)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closePreviewModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-4xl max-h-[90vh] overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-border">
                <h3 class="text-lg font-bold text-text">معاينة</h3>
                <button wire:click="closePreviewModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="flex items-center justify-center p-8 bg-surface-secondary min-h-[400px]">
                @if ($previewMedia->isImage() && $previewMedia->fileExists())
                    <img src="{{ asset('storage/' . $previewMedia->path) }}"
                         alt="{{ $previewMedia->alt ?? $previewMedia->title ?? '' }}"
                         class="max-w-full max-h-[70vh] object-contain rounded-xl"
                         loading="lazy" />
                @else
                    <div class="text-center">
                        <i data-lucide="file" class="w-16 h-16 text-text-muted mx-auto mb-3"></i>
                        <p class="text-sm text-text">{{ basename($previewMedia->path) }}</p>
                    </div>
                @endif
            </div>
            <div class="flex items-center justify-between p-4 border-t border-border">
                <div class="text-sm text-text-tertiary">{{ $previewMedia->title ?? $previewMedia->path }}</div>
                <div class="flex items-center gap-2">
                    <a href="{{ asset('storage/' . $previewMedia->path) }}" target="_blank" class="px-4 py-2 rounded-xl text-sm font-semibold text-primary hover:bg-primary-light transition-colors">فتح في نافذة جديدة</a>
                    <button wire:click="copyUrl({{ $previewMedia->id }})" class="px-4 py-2 rounded-xl text-sm font-semibold text-primary hover:bg-primary-light transition-colors">نسخ الرابط</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
        Livewire.on('copy-to-clipboard', (url) => {
            navigator.clipboard.writeText(url);
        });
    </script>
    @endpush
</div>
