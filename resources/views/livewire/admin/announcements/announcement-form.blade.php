<div>
    <x-slot name="title">{{ $announcementId ? 'تعديل إعلان' : 'إضافة إعلان' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $announcementId ? 'تعديل إعلان' : 'إضافة إعلان' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة إعلانات البلدية</p>
        </div>
        <a href="{{ route('dashboard.announcements') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors inline-flex items-center gap-2" wire:navigate>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            <span>العودة</span>
        </a>
    </div>

    <form wire:submit="save">
        <div class="space-y-6">

            {{-- Card 1: Basic Info --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">المعلومات الأساسية</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-text mb-1.5">عنوان الإعلان *</label>
                        <input type="text" wire:model="title" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: تعليق الدوام يوم الخميس" />
                        @error('title') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">النوع *</label>
                        <select wire:model="type" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            @foreach ($types as $t)
                                <option value="{{ $t->value }}">{{ $t->label() }}</option>
                            @endforeach
                        </select>
                        @error('type') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الأولوية *</label>
                        <select wire:model="priority" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            @foreach ($priorities as $p)
                                <option value="{{ $p->value }}">{{ $p->label() }}</option>
                            @endforeach
                        </select>
                        @error('priority') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 2: Content --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">محتوى الإعلان</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الملخص *</label>
                        <textarea wire:model="summary" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="نبذة مختصرة عن الإعلان"></textarea>
                        @error('summary') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">المحتوى *</label>
                        <textarea wire:model="content" rows="8" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="المحتوى الكامل للإعلان"></textarea>
                        @error('content') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 3: Image --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">صورة الإعلان</h2>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الصورة الرئيسية</label>
                    <input type="file" wire:model="image" accept=".jpg,.jpeg,.png,.webp" class="w-full text-sm text-text file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
                    <div class="flex items-center gap-3 mt-3">
                        <button type="button" wire:click="$dispatch('open-media-picker', { target: 'cover' })" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary border border-border text-sm font-semibold text-text hover:bg-surface transition-colors">
                            <i data-lucide="images" class="w-4 h-4"></i>
                            اختيار من الوسائط
                        </button>
                        @if ($existingImage)
                            <button type="button" wire:click="removeImage" class="text-xs text-danger hover:underline">إزالة الصورة</button>
                        @endif
                    </div>
                    @if ($existingImage)
                        <div class="flex items-center gap-2 mt-2">
                            <a href="{{ asset('storage/' . $existingImage) }}" target="_blank" class="text-xs text-primary underline">عرض الصورة</a>
                            <button type="button" wire:click="removeImage" class="text-xs text-danger hover:underline">حذف</button>
                        </div>
                    @endif
                    @error('image') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Card 4: Publishing --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">النشر والظهور</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">تاريخ النشر *</label>
                        <input type="datetime-local" wire:model="publishAt" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('publishAt') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الحالة</label>
                        <select wire:model="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            @foreach ($statuses as $s)
                                <option value="{{ $s->value }}">{{ $s->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end pb-2.5">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="isFeatured" id="isFeatured" class="rounded border-border text-primary focus:ring-primary/20" />
                            <label for="isFeatured" class="text-sm font-semibold text-text">إعلان مميز</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex items-center gap-3">
            <button type="submit" class="px-8 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $announcementId ? 'تحديث الإعلان' : 'إضافة الإعلان' }}</span>
                <span wire:loading>جاري الحفظ...</span>
            </button>
            <a href="{{ route('dashboard.announcements') }}" class="px-8 py-3 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors" wire:navigate>إلغاء</a>
        </div>
    </form>

    <livewire:shared.media-picker :target="'cover'" :restrict-collection="'announcements'" />
</div>
