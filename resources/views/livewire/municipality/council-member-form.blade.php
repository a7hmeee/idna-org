<div>
    <x-slot name="title">{{ $editingId ? 'تعديل العضو' : 'إضافة عضو' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $editingId ? 'تعديل العضو' : 'إضافة عضو جديد' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">أعضاء المجلس البلدي</p>
        </div>
        <a href="{{ route('dashboard.municipality.council-members') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary text-text-secondary text-sm font-semibold hover:bg-surface-secondary/80 transition-colors">
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            عودة
        </a>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Personal Info Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <h2 class="text-lg font-bold text-text mb-4">المعلومات الشخصية</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" wire:model="full_name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('full_name') border-danger @enderror" placeholder="الاسم الكامل للعضو" />
                            @error('full_name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-text mb-1.5">الرقم الوطني</label>
                                <input type="text" wire:model="national_number" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('national_number') border-danger @enderror" />
                                @error('national_number') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-text mb-1.5">المنصب <span class="text-danger">*</span></label>
                                <select wire:model="position" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('position') border-danger @enderror">
                                    @foreach ($positionOptions as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('position') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-text mb-1.5">المؤهل العلمي</label>
                                <input type="text" wire:model="qualification" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('qualification') border-danger @enderror" />
                                @error('qualification') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-text mb-1.5">المهنة</label>
                                <input type="text" wire:model="profession" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('profession') border-danger @enderror" />
                                @error('profession') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bio Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <h2 class="text-lg font-bold text-text mb-4">السيرة الذاتية</h2>
                    <div>
                        <textarea wire:model="bio" rows="6" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('bio') border-danger @enderror" placeholder="نبذة عن العضو..."></textarea>
                        @error('bio') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Contact Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <h2 class="text-lg font-bold text-text mb-4">معلومات الاتصال</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">رقم الهاتف</label>
                            <input type="text" wire:model="phone" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('phone') border-danger @enderror" dir="ltr" />
                            @error('phone') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
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
                            <label class="block text-sm font-semibold text-text mb-1.5">العنوان</label>
                            <input type="text" wire:model="address" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('address') border-danger @enderror" />
                            @error('address') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Social Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <h2 class="text-lg font-bold text-text mb-4">وسائل التواصل الاجتماعي</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">فيسبوك</label>
                            <input type="url" wire:model="facebook" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('facebook') border-danger @enderror" dir="ltr" placeholder="https://facebook.com/..." />
                            @error('facebook') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">تويتر</label>
                            <input type="url" wire:model="twitter" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('twitter') border-danger @enderror" dir="ltr" placeholder="https://twitter.com/..." />
                            @error('twitter') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">لينكد إن</label>
                            <input type="url" wire:model="linkedin" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('linkedin') border-danger @enderror" dir="ltr" placeholder="https://linkedin.com/..." />
                            @error('linkedin') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Membership Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <h2 class="text-lg font-bold text-text mb-4">بيانات العضوية</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">تاريخ بداية العضوية <span class="text-danger">*</span></label>
                            <input type="date" wire:model="term_start" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('term_start') border-danger @enderror" />
                            @error('term_start') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">تاريخ نهاية العضوية</label>
                            <input type="date" wire:model="term_end" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('term_end') border-danger @enderror" />
                            @error('term_end') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">سنوات الخبرة</label>
                            <input type="number" wire:model="years_of_experience" min="0" max="100" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('years_of_experience') border-danger @enderror" />
                            @error('years_of_experience') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">اللجنة</label>
                            <input type="text" wire:model="committee" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('committee') border-danger @enderror" placeholder="اسم اللجنة" />
                            @error('committee') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Photo Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <h2 class="text-lg font-bold text-text mb-4">الصورة الشخصية</h2>
                    <div class="space-y-4">
                        <div class="w-32 h-32 rounded-xl bg-surface-secondary mx-auto overflow-hidden flex items-center justify-center">
                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover" />
                            @elseif ($existingPhotoUrl)
                                <img src="{{ $existingPhotoUrl }}" class="w-full h-full object-cover" />
                            @else
                                <i data-lucide="user" class="w-12 h-12 text-text-muted"></i>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">رفع صورة</label>
                            <input type="file" wire:model="photo" accept="image/jpeg,image/png,image/gif,image/webp" class="w-full text-sm text-text-secondary file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary hover:file:bg-primary-100 transition-colors" />
                            <div class="flex items-center gap-3 mt-3">
                                <button type="button" wire:click="$dispatch('open-media-picker', { target: 'photo' })" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary border border-border text-sm font-semibold text-text hover:bg-surface transition-colors">
                                    <i data-lucide="images" class="w-4 h-4"></i>
                                    اختيار من الوسائط
                                </button>
                            </div>
                            @error('photo') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                            <p class="text-[10px] text-text-muted mt-1">jpg, jpeg, png, gif, webp. الحد الأقصى 2MB</p>
                        </div>
                        @if ($photo)
                        <button type="button" wire:click="removePhoto" class="text-xs text-danger hover:text-danger/80 font-semibold transition-colors">إزالة الصورة</button>
                        @endif
                    </div>
                </div>

                {{-- Publishing Card --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <h2 class="text-lg font-bold text-text mb-4">إعدادات النشر</h2>
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
            <a href="{{ route('dashboard.municipality.council-members') }}" wire:navigate class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                <span wire:loading.remove wire:target="save">{{ $editingId ? 'حفظ التعديلات' : 'إضافة' }}</span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                </span>
            </button>
        </div>
    </form>

    <livewire:shared.media-picker :target="'photo'" :restrict-collection="'council_members'" />
</div>
