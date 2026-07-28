<div>
    <x-slot name="title">الوسائط</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">الوسائط</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة الشعار والصور والملفات المرفقة</p>
        </div>
        @can('createMedia', App\Domains\Municipality\Models\Municipality::class)
        <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            إضافة مرفق
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
        @if ($mediaItems->isEmpty())
            <div class="text-center py-16">
                <div class="w-16 h-16 rounded-2xl bg-surface-secondary flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="image" class="w-7 h-7 text-text-muted"></i>
                </div>
                <p class="text-sm font-bold text-text">لا توجد وسائط</p>
                <p class="text-xs text-text-tertiary mt-1">أضف مرفقاً جديداً للبدء.</p>
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
                                        @if (str_starts_with($media->mime_type ?? '', 'image/') && Storage::disk($media->disk)->exists($media->path))
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
                                @if ($media->size)
                                    {{ $media->size >= 1048576 ? number_format($media->size / 1048576, 1) . ' م.ب' : number_format($media->size / 1024, 1) . ' ك.ب' }}
                                @else
                                    <span class="text-text-muted">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-text-secondary whitespace-nowrap">
                                @if ($media->width && $media->height)
                                    {{ $media->width }} × {{ $media->height }}
                                @else
                                    <span class="text-text-muted">—</span>
                                @endif
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
                                    @can('updateMedia', App\Domains\Municipality\Models\Municipality::class)
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
</div>
