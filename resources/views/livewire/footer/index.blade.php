<div>
    <x-slot name="title">إدارة التذييل</x-slot>

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">إدارة التذييل</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة عناصر تذييل الموقع (روابط سريعة، الخدمات الإلكترونية، تواصل معنا)</p>
        </div>
        @if ($canCreate)
            <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                <i data-lucide="plus" class="w-4 h-4"></i>
                إضافة عنصر
            </button>
        @endif
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Grouped Columns --}}
    @foreach ($columnKeys as $key => $title)
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                        <i data-lucide="{{ $key === 'quick_links' ? 'link' : ($key === 'services' ? 'monitor' : 'phone') }}" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-lg font-bold text-text">{{ $title }}</h2>
                    @if (isset($items[$key]))
                        <span class="text-xs bg-surface-secondary text-text-tertiary px-2 py-0.5 rounded-full font-semibold">{{ $items[$key]->count() }}</span>
                    @endif
                </div>
                @if ($canCreate)
                    <button wire:click="openCreateModal('{{ $key }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-secondary text-text-secondary text-xs font-semibold hover:bg-primary/10 hover:text-primary transition-colors">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        إضافة
                    </button>
                @endif
            </div>

            <div class="bg-surface rounded-xl border border-border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-border bg-municipal-50/50">
                                <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الاسم</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الرابط</th>
                                <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الأيقونة</th>
                                <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الترتيب</th>
                                <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-text-tertiary">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items[$key] ?? collect() as $item)
                                <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors" wire:key="item-{{ $item->id }}">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                <i data-lucide="{{ $item->icon ?: 'link' }}" class="w-4 h-4 text-primary"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-text">{{ $item->label }}</p>
                                                @if ($item->is_external)
                                                    <span class="text-[10px] bg-info/10 text-info px-1.5 py-0.5 rounded font-medium">خارجي</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-xs text-text-tertiary font-mono">{{ $item->url ?: ($item->route_name ?: '—') }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs text-text-tertiary">{{ $item->icon ?: '—' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs font-semibold text-text-secondary">{{ $item->sort_order }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($canUpdate)
                                            <button wire:click="toggleActive({{ $item->id }})" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors @if($item->is_active) bg-success/10 text-success hover:bg-success/20 @else bg-danger/10 text-danger hover:bg-danger/20 @endif">
                                                <i data-lucide="{{ $item->is_active ? 'eye' : 'eye-off' }}" class="w-3 h-3"></i>
                                                {{ $item->is_active ? 'نشط' : 'غير نشط' }}
                                            </button>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold @if($item->is_active) bg-success/10 text-success @else bg-danger/10 text-danger @endif">
                                                {{ $item->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-1">
                                            @if ($canUpdate)
                                                <button wire:click="openEditModal({{ $item->id }})" class="p-2 rounded-lg hover:bg-municipal-50 text-text-tertiary hover:text-primary transition-all" title="تعديل">
                                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                                </button>
                                            @endif
                                            @if ($canDelete)
                                                <button wire:click="confirmDelete({{ $item->id }})" class="p-2 rounded-lg hover:bg-danger/10 text-text-tertiary hover:text-danger transition-all" title="حذف">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <i data-lucide="inbox" class="w-6 h-6 text-text-tertiary"></i>
                                            <p class="text-sm text-text-tertiary">لا توجد عناصر في هذا القسم</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Create Modal --}}
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeCreateModal"></div>
            <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-5 border-b border-border">
                    <h3 class="text-lg font-bold text-text">إضافة عنصر تذييل جديد</h3>
                    <button wire:click="closeCreateModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form wire:submit="createItem" class="p-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">العمود <span class="text-danger">*</span></label>
                            <select wire:model="columnKey" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('columnKey') border-danger @enderror">
                                @foreach ($columnKeys as $key => $title)
                                    <option value="{{ $key }}">{{ $title }}</option>
                                @endforeach
                            </select>
                            @error('columnKey') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">عنوان العمود <span class="text-danger">*</span></label>
                            <input type="text" wire:model="columnTitle" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('columnTitle') border-danger @enderror" />
                            @error('columnTitle') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الاسم <span class="text-danger">*</span></label>
                        <input type="text" wire:model="label" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('label') border-danger @enderror" placeholder="مثال: الرئيسية" />
                        @error('label') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الرابط</label>
                            <input type="text" wire:model="url" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('url') border-danger @enderror" placeholder="https://example.com" />
                            @error('url') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">اسم المسار</label>
                            <input type="text" wire:model="routeName" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('routeName') border-danger @enderror" placeholder="home" />
                            @error('routeName') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الأيقونة</label>
                            <input type="text" wire:model="icon" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('icon') border-danger @enderror" placeholder="home" />
                            @error('icon') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الترتيب <span class="text-danger">*</span></label>
                            <input type="number" wire:model="sortOrder" min="0" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('sortOrder') border-danger @enderror" />
                            @error('sortOrder') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الهدف</label>
                        <select wire:model="target" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('target') border-danger @enderror">
                            <option value="_self">نفس النافذة</option>
                            <option value="_blank">نافذة جديدة</option>
                        </select>
                        @error('target') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="isActive" class="sr-only" />
                            <div class="relative w-10 h-5 rounded-full transition-colors @if($isActive) bg-primary @else bg-border @endif">
                                <div class="absolute top-0.5 start-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform @if($isActive) translate-x-5 @endif"></div>
                            </div>
                            <span class="text-sm font-semibold text-text">نشط</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="isExternal" class="sr-only" />
                            <div class="relative w-10 h-5 rounded-full transition-colors @if($isExternal) bg-primary @else bg-border @endif">
                                <div class="absolute top-0.5 start-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform @if($isExternal) translate-x-5 @endif"></div>
                            </div>
                            <span class="text-sm font-semibold text-text">رابط خارجي</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                        <button type="button" wire:click="closeCreateModal" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                            <span wire:loading.remove>إنشاء العنصر</span>
                            <span wire:loading>جاري الإنشاء...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Edit Modal --}}
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeEditModal"></div>
            <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-5 border-b border-border">
                    <h3 class="text-lg font-bold text-text">تعديل عنصر التذييل</h3>
                    <button wire:click="closeEditModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form wire:submit="updateItem" class="p-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">العمود <span class="text-danger">*</span></label>
                            <select wire:model="columnKey" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('columnKey') border-danger @enderror">
                                @foreach ($columnKeys as $key => $title)
                                    <option value="{{ $key }}">{{ $title }}</option>
                                @endforeach
                            </select>
                            @error('columnKey') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">عنوان العمود <span class="text-danger">*</span></label>
                            <input type="text" wire:model="columnTitle" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('columnTitle') border-danger @enderror" />
                            @error('columnTitle') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الاسم <span class="text-danger">*</span></label>
                        <input type="text" wire:model="label" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('label') border-danger @enderror" />
                        @error('label') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الرابط</label>
                            <input type="text" wire:model="url" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('url') border-danger @enderror" />
                            @error('url') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">اسم المسار</label>
                            <input type="text" wire:model="routeName" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('routeName') border-danger @enderror" />
                            @error('routeName') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الأيقونة</label>
                            <input type="text" wire:model="icon" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('icon') border-danger @enderror" />
                            @error('icon') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الترتيب <span class="text-danger">*</span></label>
                            <input type="number" wire:model="sortOrder" min="0" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('sortOrder') border-danger @enderror" />
                            @error('sortOrder') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الهدف</label>
                        <select wire:model="target" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('target') border-danger @enderror">
                            <option value="_self">نفس النافذة</option>
                            <option value="_blank">نافذة جديدة</option>
                        </select>
                        @error('target') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="isActive" class="sr-only" />
                            <div class="relative w-10 h-5 rounded-full transition-colors @if($isActive) bg-primary @else bg-border @endif">
                                <div class="absolute top-0.5 start-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform @if($isActive) translate-x-5 @endif"></div>
                            </div>
                            <span class="text-sm font-semibold text-text">نشط</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="isExternal" class="sr-only" />
                            <div class="relative w-10 h-5 rounded-full transition-colors @if($isExternal) bg-primary @else bg-border @endif">
                                <div class="absolute top-0.5 start-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform @if($isExternal) translate-x-5 @endif"></div>
                            </div>
                            <span class="text-sm font-semibold text-text">رابط خارجي</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                        <button type="button" wire:click="closeEditModal" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                            <span wire:loading.remove>حفظ التعديلات</span>
                            <span wire:loading>جاري الحفظ...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Modal --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeDeleteModal"></div>
            <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-md">
                <div class="p-6 text-center">
                    <div class="w-14 h-14 rounded-full bg-danger-light flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="alert-triangle" class="w-7 h-7 text-danger"></i>
                    </div>
                    <h3 class="text-lg font-bold text-text mb-2">حذف عنصر التذييل</h3>
                    <p class="text-sm text-text-tertiary">هل أنت متأكد من حذف هذا العنصر؟ لا يمكن التراجع عن هذا الإجراء.</p>
                </div>
                <div class="flex items-center justify-center gap-3 px-6 pb-6">
                    <button wire:click="closeDeleteModal" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                    <button wire:click="deleteItem" class="px-5 py-2.5 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove>نعم، حذف</span>
                        <span wire:loading>جاري الحذف...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
