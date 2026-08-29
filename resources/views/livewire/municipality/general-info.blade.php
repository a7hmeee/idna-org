<div>
    <x-slot name="title">المعلومات العامة</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">المعلومات العامة</h1>
            <p class="text-sm text-text-tertiary mt-1">البيانات الأساسية للبلدية</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-6">
        <livewire:municipality.about-image />
    </div>

    <form wire:submit="save" class="bg-surface rounded-xl border border-border p-6 space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">الاسم بالعربية <span class="text-danger">*</span></label>
                <input type="text" wire:model="nameAr" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('nameAr') border-danger @enderror" />
                @error('nameAr') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">الاسم بالإنجليزية <span class="text-danger">*</span></label>
                <input type="text" wire:model="nameEn" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('nameEn') border-danger @enderror" />
                @error('nameEn') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-text mb-1.5">وصف مختصر</label>
            <textarea wire:model="shortDescription" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('shortDescription') border-danger @enderror"></textarea>
            @error('shortDescription') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-text mb-1.5">وصف كامل</label>
            <textarea wire:model="fullDescription" rows="4" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('fullDescription') border-danger @enderror"></textarea>
            @error('fullDescription') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">الرؤية</label>
                <textarea wire:model="vision" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('vision') border-danger @enderror"></textarea>
                @error('vision') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">الرسالة</label>
                <textarea wire:model="mission" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('mission') border-danger @enderror"></textarea>
                @error('mission') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-text mb-1.5">الأهداف (سطر لكل هدف)</label>
            <textarea wire:model="objectives" rows="4" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('objectives') border-danger @enderror" placeholder="الهدف الأول&#10;الهدف الثاني&#10;الهدف الثالث"></textarea>
            @error('objectives') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">تاريخ التأسيس</label>
                <input type="date" wire:model="foundationDate" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('foundationDate') border-danger @enderror" />
                @error('foundationDate') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">عدد السكان</label>
                <input type="number" wire:model="population" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('population') border-danger @enderror" />
                @error('population') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">المساحة (كم²)</label>
                <input type="number" step="0.01" wire:model="area" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('area') border-danger @enderror" />
                @error('area') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">رمز البلدية</label>
                <input type="text" wire:model="municipalityCode" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('municipalityCode') border-danger @enderror" />
                @error('municipalityCode') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">خط العرض</label>
                <input type="number" step="0.0000001" wire:model="latitude" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('latitude') border-danger @enderror" />
                @error('latitude') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-text mb-1.5">خط الطول</label>
                <input type="number" step="0.0000001" wire:model="longitude" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('longitude') border-danger @enderror" />
                @error('longitude') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                <span wire:loading.remove>حفظ المعلومات</span>
                <span wire:loading><i data-lucide="loader-2" class="w-4 h-4 animate-spin inline-block"></i> جاري الحفظ...</span>
            </button>
        </div>
    </form>
</div>
