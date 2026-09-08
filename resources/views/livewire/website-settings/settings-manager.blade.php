<div>
    <x-slot name="title">إعدادات الموقع</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">إعدادات الموقع</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة إعدادات الموقع الإلكتروني</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @forelse ($groupedSettings as $group => $settings)
        <div class="mb-6">
            <h2 class="text-lg font-bold text-text mb-3">{{ $group }}</h2>
            <div class="bg-surface rounded-xl border border-border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-border bg-municipal-50/50">
                                <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">المفتاح</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الاسم</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">القيمة</th>
                                <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">النوع</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($settings as $setting)
                                <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors" wire:key="setting-{{ $setting->id }}">
                                    <td class="px-4 py-3">
                                        <span class="font-mono text-xs text-text-tertiary">{{ $setting->key }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-text">{{ $setting->label ?? $setting->key }}</span>
                                        @if ($setting->description)
                                            <p class="text-xs text-text-tertiary mt-0.5">{{ $setting->description }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($setting->type === 'boolean')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $setting->value ? 'bg-success/10 text-success' : 'bg-surface-secondary text-text-tertiary' }}">
                                                {{ $setting->value ? 'مفعّل' : 'معطّل' }}
                                            </span>
                                        @else
                                            <span class="text-sm text-text">{{ $setting->value ?: '—' }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs text-text-tertiary">{{ $setting->type }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            @can('website_settings.update')
                                                <button wire:click="editSetting({{ $setting->id }})" class="p-1.5 rounded-lg hover:bg-municipal-50 text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-20 h-20 rounded-2xl bg-surface-secondary flex items-center justify-center mb-4">
                <i data-lucide="settings" class="w-9 h-9 text-text-muted"></i>
            </div>
            <p class="text-base font-bold text-text">لا توجد إعدادات</p>
            <p class="text-sm text-text-tertiary mt-1">لم يتم العثور على أي إعدادات للموقع</p>
        </div>
    @endforelse

    @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div class="bg-surface rounded-2xl border border-border p-6 w-full max-w-md mx-4 shadow-dropdown">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <i data-lucide="settings" class="w-5 h-5 text-primary"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-text">تعديل الإعداد</h3>
                        <p class="text-xs text-text-tertiary">{{ $editingKey }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-text-tertiary mb-1.5">القيمة</label>
                        @if ($editingType === 'boolean')
                            <button type="button" wire:click="$set('editingValue', '{{ $editingValue === '1' ? '' : '1' }}')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $editingValue === '1' ? 'bg-success' : 'bg-border' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $editingValue === '1' ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                            <span class="text-xs text-text-tertiary mr-2">{{ $editingValue === '1' ? 'مفعّل' : 'معطّل' }}</span>
                        @else
                            <input type="text" wire:model="editingValue" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @endif
                        @error('editingValue')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button wire:click="closeEditModal" class="px-4 py-2 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors">إلغاء</button>
                    <button wire:click="saveSetting" class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove>حفظ التغييرات</span>
                        <span wire:loading>جاري الحفظ...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
