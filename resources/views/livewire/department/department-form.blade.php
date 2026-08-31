<div>
    <x-slot name="title">{{ $editingId ? 'تعديل الدائرة' : 'إضافة دائرة' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $editingId ? 'تعديل الدائرة' : 'إضافة دائرة جديدة' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">الدوائر والأقسام البلدية</p>
        </div>
        <a href="{{ route('dashboard.departments') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary text-text-secondary text-sm font-semibold hover:bg-surface-secondary/80 transition-colors">
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            عودة
        </a>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Basic Info Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i data-lucide="info" class="w-5 h-5 text-primary"></i>
                        <h2 class="text-lg font-bold text-text">المعلومات الأساسية</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">اسم الدائرة <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('name') border-danger @enderror" placeholder="اسم الدائرة" />
                            @error('name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الأيقونة</label>
                            <input type="text" wire:model="icon" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('icon') border-danger @enderror" placeholder="اسم أيقونة Lucide (مثال: building-2)" />
                            @error('icon') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">وصف مختصر</label>
                            <input type="text" wire:model="short_description" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('short_description') border-danger @enderror" placeholder="وصف مختصر للدائرة" />
                            @error('short_description') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الوصف</label>
                            <textarea wire:model="description" rows="4" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('description') border-danger @enderror" placeholder="وصف كامل للدائرة"></textarea>
                            @error('description') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Manager Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i data-lucide="user" class="w-5 h-5 text-primary"></i>
                        <h2 class="text-lg font-bold text-text">بيانات المدير</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">اسم المدير</label>
                            <input type="text" wire:model="manager_name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('manager_name') border-danger @enderror" placeholder="اسم مدير الدائرة" />
                            @error('manager_name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">منصب المدير</label>
                            <input type="text" wire:model="manager_position" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('manager_position') border-danger @enderror" placeholder="مثال: مدير الدائرة" />
                            @error('manager_position') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Contact Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i data-lucide="phone" class="w-5 h-5 text-primary"></i>
                        <h2 class="text-lg font-bold text-text">معلومات التواصل</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">رقم الهاتف</label>
                            <input type="text" wire:model="phone" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('phone') border-danger @enderror" dir="ltr" />
                            @error('phone') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">التحويلة</label>
                            <input type="text" wire:model="extension" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('extension') border-danger @enderror" dir="ltr" />
                            @error('extension') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الجوال</label>
                            <input type="text" wire:model="mobile" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('mobile') border-danger @enderror" dir="ltr" />
                            @error('mobile') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">البريد الإلكتروني</label>
                            <input type="email" wire:model="email" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('email') border-danger @enderror" dir="ltr" />
                            @error('email') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">موقع المكتب</label>
                            <input type="text" wire:model="office_location" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('office_location') border-danger @enderror" placeholder="مثال: الطابق الثاني، مبنى البلدية" />
                            @error('office_location') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">ساعات الدوام</label>
                            <input type="text" wire:model="working_hours" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('working_hours') border-danger @enderror" placeholder="مثال: 8:00 ص - 3:00 م" />
                            @error('working_hours') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Vision & Mission Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i data-lucide="target" class="w-5 h-5 text-primary"></i>
                        <h2 class="text-lg font-bold text-text">الرؤية والرسالة</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الرؤية</label>
                            <textarea wire:model="vision" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('vision') border-danger @enderror" placeholder="رؤية الدائرة"></textarea>
                            @error('vision') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الرسالة</label>
                            <textarea wire:model="mission" rows="3" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('mission') border-danger @enderror" placeholder="رسالة الدائرة"></textarea>
                            @error('mission') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Responsibilities Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i data-lucide="list-checks" class="w-5 h-5 text-primary"></i>
                        <h2 class="text-lg font-bold text-text">المهام والمسؤوليات</h2>
                    </div>
                    <div>
                        <textarea wire:model="responsibilities" rows="6" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('responsibilities') border-danger @enderror" placeholder="المهام والمسؤوليات..."></textarea>
                        @error('responsibilities') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Cover Image Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i data-lucide="image" class="w-5 h-5 text-primary"></i>
                        <h2 class="text-lg font-bold text-text">صورة الغلاف</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="w-full h-36 rounded-xl bg-surface-secondary overflow-hidden flex items-center justify-center">
                            @if ($cover_image)
                                <img src="{{ $cover_image->temporaryUrl() }}" class="w-full h-full object-cover" />
                            @elseif ($existingCoverImageUrl)
                                <img src="{{ $existingCoverImageUrl }}" class="w-full h-full object-cover" />
                            @else
                                <i data-lucide="image" class="w-12 h-12 text-text-muted"></i>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">رفع صورة</label>
                            <input type="file" wire:model="cover_image" accept="image/jpeg,image/png,image/gif,image/webp" class="w-full text-sm text-text-secondary file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary hover:file:bg-primary-100 transition-colors" />
                            <div class="flex items-center gap-3 mt-3">
                                <button type="button" wire:click="$dispatch('open-media-picker', { target: 'cover' })" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary border border-border text-sm font-semibold text-text hover:bg-surface transition-colors">
                                    <i data-lucide="images" class="w-4 h-4"></i>
                                    اختيار من الوسائط
                                </button>
                            </div>
                            @error('cover_image') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                            <p class="text-[10px] text-text-muted mt-1">jpg, jpeg, png, gif, webp. الحد الأقصى 2MB</p>
                        </div>
                        @if ($cover_image)
                        <button type="button" wire:click="removeCoverImage" class="text-xs text-danger hover:text-danger/80 font-semibold transition-colors">إزالة الصورة</button>
                        @endif
                    </div>
                </div>

                {{-- Publishing Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i data-lucide="settings" class="w-5 h-5 text-primary"></i>
                        <h2 class="text-lg font-bold text-text">إعدادات النشر</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الحالة <span class="text-danger">*</span></label>
                            <select wire:model="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('status') border-danger @enderror">
                                @foreach ($statusOptions as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">ترتيب العرض</label>
                            <input type="number" wire:model="display_order" min="0" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('display_order') border-danger @enderror" />
                            @error('display_order') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-3 pt-2">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_public" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20" />
                                <span class="text-sm font-semibold text-text">ظهور للعامة</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_featured" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20" />
                                <span class="text-sm font-semibold text-text">مميز</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-border">
            <a href="{{ route('dashboard.departments') }}" wire:navigate class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                <span wire:loading.remove wire:target="save">{{ $editingId ? 'حفظ التعديلات' : 'إضافة' }}</span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                </span>
            </button>
        </div>
    </form>

    <livewire:shared.media-picker :target="'cover'" :restrict-collection="'departments'" />
</div>
