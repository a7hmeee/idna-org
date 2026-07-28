<div>
    <x-slot name="title">الحقول المخصصة</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">الحقول المخصصة</h1>
            <p class="text-sm text-text-tertiary mt-1">بيانات إضافية مخصصة للبلدية</p>
        </div>
        @can('createCustomField', App\Domains\Municipality\Models\Municipality::class)
        <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            إضافة حقل
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
        @if ($fields->isEmpty())
            <div class="text-center py-16">
                <div class="w-16 h-16 rounded-2xl bg-surface-secondary flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="list-checks" class="w-7 h-7 text-text-muted"></i>
                </div>
                <p class="text-sm font-bold text-text">لا توجد حقول مخصصة</p>
                <p class="text-xs text-text-tertiary mt-1">أضف حقلاً مخصصاً جديداً للبدء.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border bg-background/50">
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">المفتاح</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">القيمة</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">النوع</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">الحالة</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">الترتيب</th>
                            <th class="text-start px-6 py-4 text-xs font-bold text-text-tertiary">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fields as $field)
                        <tr wire:key="field-{{ $field->id }}" class="border-b border-border last:border-0 hover:bg-background/30 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-text">{{ $field->key }}</td>
                            <td class="px-6 py-4 text-sm text-text-secondary max-w-[250px] truncate">{{ $field->value }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-surface-secondary text-[11px] font-semibold text-text-secondary">{{ $fieldTypes[$field->type] ?? $field->type }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($field->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-success-light text-[11px] font-semibold text-success">نشط</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-surface-secondary text-[11px] font-semibold text-text-muted">غير نشط</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-text-secondary">{{ $field->display_order }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    @can('updateCustomField', App\Domains\Municipality\Models\Municipality::class)
                                    <button wire:click="openEditModal({{ $field->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    @endcan
                                    @can('deleteCustomField', App\Domains\Municipality\Models\Municipality::class)
                                    <button wire:click="confirmDelete({{ $field->id }})" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors" title="حذف">
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
    </div>

    @if ($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeForm"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-border">
                <h3 class="text-lg font-bold text-text">{{ $editingId ? 'تعديل الحقل' : 'إضافة حقل' }}</h3>
                <button wire:click="closeForm" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form wire:submit="save" class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">المفتاح <span class="text-danger">*</span></label>
                        <input type="text" wire:model="key" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('key') border-danger @enderror" placeholder="example_key" />
                        @error('key') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">النوع <span class="text-danger">*</span></label>
                        <select wire:model="type" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('type') border-danger @enderror">
                            @foreach ($fieldTypes as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">القيمة <span class="text-danger">*</span></label>
                    <textarea wire:model="value" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('value') border-danger @enderror"></textarea>
                    @error('value') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
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
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $editingId ? 'حفظ التعديلات' : 'إضافة' }}</span>
                        <span wire:loading><i data-lucide="loader-2" class="w-4 h-4 animate-spin inline-block"></i></span>
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
                <h3 class="text-lg font-bold text-text mb-2">حذف الحقل</h3>
                <p class="text-sm text-text-tertiary">هل أنت متأكد من حذف هذا الحقل المخصص؟</p>
            </div>
            <div class="flex items-center justify-center gap-3 px-6 pb-6">
                <button wire:click="closeDeleteModal" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                <button wire:click="delete" class="px-5 py-2.5 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors">نعم، حذف</button>
            </div>
        </div>
    </div>
    @endif
</div>
