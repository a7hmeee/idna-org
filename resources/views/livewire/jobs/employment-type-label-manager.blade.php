<div>
    <x-slot name="title">تسميات أنواع التوظيف</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">تسميات أنواع التوظيف</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة تسميات أنواع التوظيف</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">المفتاح</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الاسم بالعربي</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الاسم بالإنجليزي</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الترتيب</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($labels as $label)
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors" wire:key="label-{{ $label->id }}">
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs text-text-tertiary">{{ $label->type_key }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-text">{{ $label->label_ar }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs text-text-tertiary">{{ $label->label_en ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs text-text-tertiary">{{ $label->sort_order }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $label->is_active ? 'bg-success/10 text-success' : 'bg-surface-secondary text-text-tertiary' }}">
                                    {{ $label->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    @can('jobs.update')
                                        <button wire:click="editLabel({{ $label->id }})" class="p-1.5 rounded-lg hover:bg-municipal-50 text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i data-lucide="briefcase" class="w-12 h-12 text-text-tertiary/40"></i>
                                    <p class="text-sm text-text-tertiary">لا توجد تسميات أنواع توظيف</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div class="bg-surface rounded-2xl border border-border p-6 w-full max-w-md mx-4 shadow-dropdown">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <i data-lucide="briefcase" class="w-5 h-5 text-primary"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-text">تعديل تسمية نوع التوظيف</h3>
                        <p class="text-xs text-text-tertiary">تحديث معلومات تسمية نوع التوظيف</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1.5">الاسم بالعربي</label>
                        <input type="text" wire:model="labelAr" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('labelAr')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="text-xs font-semibold text-text-tertiary">الحالة</label>
                        <button type="button" wire:click="$set('isActive', !{{ $isActive ? 'true' : 'false' }})" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $isActive ? 'bg-success' : 'bg-border' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $isActive ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                        <span class="text-xs text-text-tertiary">{{ $isActive ? 'نشط' : 'غير نشط' }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button wire:click="closeEditModal" class="px-4 py-2 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors">إلغاء</button>
                    <button wire:click="saveLabel" class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove>حفظ التغييرات</span>
                        <span wire:loading>جاري الحفظ...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
