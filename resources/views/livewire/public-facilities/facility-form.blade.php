<div>
    <x-slot name="title">{{ $facilityId ? 'تعديل مرفق' : 'إضافة مرفق' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $facilityId ? 'تعديل مرفق' : 'إضافة مرفق' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة مرافق البلدية العامة</p>
        </div>
        <a href="{{ route('dashboard.facilities') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors inline-flex items-center gap-2" wire:navigate>
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
                        <label class="block text-sm font-semibold text-text mb-1.5">اسم المرفق *</label>
                        <input type="text" wire:model="name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: حديقة البلدية" />
                        @error('name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">التصنيف</label>
                        <select wire:model="facilityCategoryId" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="">اختر التصنيف</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">العنوان *</label>
                        <input type="text" wire:model="address" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: شارع القدس - إذنا" />
                        @error('address') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 2: Description --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">الوصف</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">نبذة *</label>
                        <textarea wire:model="summary" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="نبذة مختصرة عن المرفق"></textarea>
                        @error('summary') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الوصف التفصيلي *</label>
                        <textarea wire:model="description" rows="6" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="الوصف الكامل للمرفق"></textarea>
                        @error('description') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 3: Contact Info --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">معلومات التواصل</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">رقم الهاتف</label>
                        <input type="text" wire:model="phone" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="022..." />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">البريد الإلكتروني</label>
                        <input type="email" wire:model="email" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="info@idhna.ps" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-text mb-1.5">ساعات العمل</label>
                        <input type="text" wire:model="workingHours" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: الأحد - الخميس: 8:00 ص - 4:00 م" />
                    </div>
                </div>
            </div>

            {{-- Card 4: Images --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">الصور</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الصورة الرئيسية</label>
                        <input type="file" wire:model="coverImage" accept=".jpg,.jpeg,.png,.webp" class="w-full text-sm text-text file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
                        <div class="flex items-center gap-3 mt-3">
                            <button type="button" wire:click="$dispatch('open-media-picker', { target: 'cover' })" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary border border-border text-sm font-semibold text-text hover:bg-surface transition-colors">
                                <i data-lucide="images" class="w-4 h-4"></i>
                                اختيار من الوسائط
                            </button>
                        </div>
                        @if ($existingCoverImage && !$coverImage)
                            <div class="mt-2 flex items-center gap-2">
                                <img src="{{ Storage::disk('public')->url($existingCoverImage) }}" class="w-20 h-20 rounded-lg object-cover border border-border" />
                                <span class="text-xs text-text-tertiary">الصورة الحالية</span>
                            </div>
                        @endif
                        @error('coverImage') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">معرض الصور</label>
                        <input type="file" wire:model="galleryUploads" multiple accept=".jpg,.jpeg,.png,.webp" class="w-full text-sm text-text file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
                        <div class="flex items-center gap-3 mt-3">
                            <button type="button" wire:click="$dispatch('open-media-picker', { target: 'gallery' })" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary border border-border text-sm font-semibold text-text hover:bg-surface transition-colors">
                                <i data-lucide="images" class="w-4 h-4"></i>
                                إضافة من الوسائط
                            </button>
                        </div>
                        @if (count($existingGallery) > 0)
                            <div class="mt-2 flex items-center gap-2 flex-wrap">
                                @foreach ($existingGallery as $img)
                                    <img src="{{ Storage::disk('public')->url($img) }}" class="w-16 h-16 rounded-lg object-cover border border-border" />
                                @endforeach
                            </div>
                        @endif
                        @error('galleryUploads.*') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 5: Services --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">الخدمات الموجودة داخل المرفق</h2>
                @foreach ($services as $index => $service)
                    <div class="flex items-center gap-2 mb-2" wire:key="service-{{ $index }}">
                        <input type="text" wire:model="services.{{ $index }}" class="flex-1 bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: قاعة اجتماعات" />
                        <button type="button" wire:click="removeService({{ $index }})" class="p-2.5 rounded-xl bg-danger/10 text-danger hover:bg-danger/20 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endforeach
                <button type="button" wire:click="addService" class="text-sm text-primary font-semibold hover:underline inline-flex items-center gap-1 mt-2">
                    <i data-lucide="plus" class="w-3 h-3"></i>
                    <span>إضافة خدمة</span>
                </button>
            </div>

            {{-- Card 6: Features --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">المميزات</h2>
                @foreach ($features as $index => $feature)
                    <div class="flex items-center gap-2 mb-2" wire:key="feature-{{ $index }}">
                        <input type="text" wire:model="features.{{ $index }}" class="flex-1 bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: مكيف" />
                        <button type="button" wire:click="removeFeature({{ $index }})" class="p-2.5 rounded-xl bg-danger/10 text-danger hover:bg-danger/20 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endforeach
                <button type="button" wire:click="addFeature" class="text-sm text-primary font-semibold hover:underline inline-flex items-center gap-1 mt-2">
                    <i data-lucide="plus" class="w-3 h-3"></i>
                    <span>إضافة ميزة</span>
                </button>
            </div>

            {{-- Card 7: Rules --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">التعليمات</h2>
                @foreach ($rules as $index => $rule)
                    <div class="flex items-center gap-2 mb-2" wire:key="rule-{{ $index }}">
                        <input type="text" wire:model="rules.{{ $index }}" class="flex-1 bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: يمنع التدخين" />
                        <button type="button" wire:click="removeRule({{ $index }})" class="p-2.5 rounded-xl bg-danger/10 text-danger hover:bg-danger/20 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endforeach
                <button type="button" wire:click="addRule" class="text-sm text-primary font-semibold hover:underline inline-flex items-center gap-1 mt-2">
                    <i data-lucide="plus" class="w-3 h-3"></i>
                    <span>إضافة تعليمة</span>
                </button>
            </div>

            {{-- Card 8: Publishing --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">النشر</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الحالة</label>
                        <select wire:model="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            @foreach ($statuses as $s)
                                <option value="{{ $s->value }}">{{ $s->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-6 pb-2.5">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="isPublic" id="isPublic" class="rounded border-border text-primary focus:ring-primary/20" />
                            <label for="isPublic" class="text-sm font-semibold text-text">ظاهر للعموم</label>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="isFeatured" id="isFeatured" class="rounded border-border text-primary focus:ring-primary/20" />
                            <label for="isFeatured" class="text-sm font-semibold text-text">مرفق مميز</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex items-center gap-3">
            <button type="submit" class="px-8 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $facilityId ? 'تحديث المرفق' : 'إضافة المرفق' }}</span>
                <span wire:loading>جاري الحفظ...</span>
            </button>
            <a href="{{ route('dashboard.facilities') }}" class="px-8 py-3 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors" wire:navigate>إلغاء</a>
        </div>
    </form>

    <livewire:shared.media-picker :target="'cover'" :restrict-collection="'facilities'" />
    <livewire:shared.media-picker :target="'gallery'" :restrict-collection="'facility_gallery'" />
</div>
