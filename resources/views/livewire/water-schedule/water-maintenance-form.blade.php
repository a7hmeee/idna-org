<div>
    <x-slot name="title">{{ $maintenanceId ? 'تعديل صيانة' : 'إضافة صيانة' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $maintenanceId ? 'تعديل صيانة' : 'إضافة صيانة' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة فترات صيانة المياه</p>
        </div>
        <a href="{{ route('dashboard.water-schedule.maintenance') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors inline-flex items-center gap-2" wire:navigate>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            <span>العودة</span>
        </a>
    </div>

    <div class="bg-surface rounded-xl border border-border p-6 max-w-2xl">
        <form wire:submit="save">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">العنوان</label>
                    <input type="text" wire:model="title" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: صيانة على الخط الرئيسي" />
                    @error('title') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الوصف</label>
                    <textarea wire:model="description" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="وصف الصيانة..."></textarea>
                    @error('description') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">تبدأ في</label>
                        <input type="datetime-local" wire:model="startsAt" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('startsAt') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">تنتهي في</label>
                        <input type="datetime-local" wire:model="endsAt" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('endsAt') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">المناطق المتأثرة</label>
                    @foreach ($affectedAreas as $index => $area)
                        <div class="flex items-center gap-2 mb-2" wire:key="area-{{ $index }}">
                            <input type="text" wire:model="affectedAreas.{{ $index }}" class="flex-1 bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="اسم المنطقة" />
                            <button type="button" wire:click="removeAffectedArea({{ $index }})" class="p-2.5 rounded-xl bg-danger/10 text-danger hover:bg-danger/20 transition-colors">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    @endforeach
                    <button type="button" wire:click="addAffectedArea" class="text-sm text-primary font-semibold hover:underline inline-flex items-center gap-1">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        <span>إضافة منطقة</span>
                    </button>
                    @error('affectedAreas') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الحالة</label>
                        <select wire:model="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="active">نشط</option>
                            <option value="completed">منتهي</option>
                            <option value="cancelled">ملغى</option>
                        </select>
                    </div>
                    <div class="flex items-end pb-2.5">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="isPublic" id="isPublic" class="rounded border-border text-primary focus:ring-primary/20" />
                            <label for="isPublic" class="text-sm font-semibold text-text">ظاهر للعموم</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $maintenanceId ? 'تحديث' : 'إضافة' }}</span>
                    <span wire:loading>جاري الحفظ...</span>
                </button>
                <a href="{{ route('dashboard.water-schedule.maintenance') }}" class="px-6 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors" wire:navigate>إلغاء</a>
            </div>
        </form>
    </div>
</div>
