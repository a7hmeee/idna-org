<div>
    <x-slot name="title">{{ $projectId ? 'تعديل مشروع' : 'إضافة مشروع' }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $projectId ? 'تعديل مشروع' : 'إضافة مشروع' }}</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة مشاريع البلدية</p>
        </div>
        <a href="{{ route('dashboard.projects') }}" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors inline-flex items-center gap-2" wire:navigate>
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
                        <label class="block text-sm font-semibold text-text mb-1.5">اسم المشروع (عربي) *</label>
                        <input type="text" wire:model="nameAr" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: مشروع تطوير وسط البلدة" />
                        @error('nameAr') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">اسم المشروع (إنجليزي)</label>
                        <input type="text" wire:model="nameEn" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="Project name" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">التصنيف *</label>
                        <select wire:model="category" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            @foreach ($categories as $c)
                                <option value="{{ $c->value }}">{{ $c->label() }}</option>
                            @endforeach
                        </select>
                        @error('category') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">حالة المشروع *</label>
                        <select wire:model="projectStatus" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            @foreach ($projectStatuses as $ps)
                                <option value="{{ $ps->value }}">{{ $ps->label() }}</option>
                            @endforeach
                        </select>
                        @error('projectStatus') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الموقع</label>
                        <input type="text" wire:model="location" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: حي الشرق، إذنا" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">نسبة الإنجاز *</label>
                        <div class="flex items-center gap-3">
                            <input type="range" wire:model="implementationPercentage" min="0" max="100" class="flex-1 accent-primary" />
                            <span class="text-sm font-bold text-primary min-w-[3ch] text-center">{{ $implementationPercentage }}%</span>
                        </div>
                        @error('implementationPercentage') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 2: Description --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">الوصف</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">نبذة</label>
                        <textarea wire:model="summary" rows="2" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="نبذة مختصرة عن المشروع"></textarea>
                        @error('summary') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الوصف</label>
                        <textarea wire:model="description" rows="8" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="الوصف الكامل للمشروع"></textarea>
                        @error('description') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Card 3: Dates & Budget --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">التواريخ والميزانية</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">تاريخ البداية</label>
                        <input type="date" wire:model="startDate" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('startDate') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">تاريخ الانتهاء المتوقع</label>
                        <input type="date" wire:model="expectedCompletionDate" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('expectedCompletionDate') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">تاريخ الانتهاء الفعلي</label>
                        <input type="date" wire:model="actualCompletionDate" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                        @error('actualCompletionDate') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الميزانية</label>
                        <input type="number" step="0.01" min="0" wire:model="budget" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="0.00" />
                        @error('budget') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">عملة الميزانية</label>
                        <select wire:model="budgetCurrency" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="ILS">شيكل</option>
                            <option value="USD">دولار</option>
                            <option value="EUR">يورو</option>
                            <option value="JOD">دينار</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الجهة المنفذة</label>
                        <input type="text" wire:model="contractor" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="اسم المقاول أو الشركة" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الجهة الممولة</label>
                        <input type="text" wire:model="fundingEntity" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="مثال: وزارة الحكم المحلي" />
                    </div>
                </div>
            </div>

            {{-- Card 4: Cover Image --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">الصورة الرئيسية</h2>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">صورة المشروع</label>
                    <input type="file" wire:model="coverImage" accept=".jpg,.jpeg,.png,.webp" class="w-full text-sm text-text file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
                    <div class="flex items-center gap-3 mt-3">
                        <button type="button" wire:click="$dispatch('open-media-picker', { target: 'cover' })" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary border border-border text-sm font-semibold text-text hover:bg-surface transition-colors">
                            <i data-lucide="images" class="w-4 h-4"></i>
                            اختيار من الوسائط
                        </button>
                        @if ($existingCoverImage)
                            <button type="button" wire:click="removeCoverImage" class="text-xs text-danger hover:underline">إزالة الصورة</button>
                        @endif
                    </div>
                    @if ($existingCoverImage)
                        <div class="flex items-center gap-2 mt-2">
                            <a href="{{ asset('storage/' . $existingCoverImage) }}" target="_blank" class="text-xs text-primary underline">عرض الصورة</a>
                            <button type="button" wire:click="removeCoverImage" class="text-xs text-danger hover:underline">حذف</button>
                        </div>
                    @endif
                    @error('coverImage') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Card 5: Gallery --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">معرض الصور</h2>
                @if (!empty($existingGallery))
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                        @foreach ($existingGallery as $index => $image)
                            <div class="relative group" wire:key="existing-gallery-{{ $index }}">
                                <img src="{{ asset('storage/' . $image) }}" class="w-full h-24 object-cover rounded-xl border border-border" />
                                <button type="button" wire:click="removeExistingGalleryImage({{ $index }})" class="absolute top-1 right-1 w-6 h-6 rounded-full bg-danger/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="x" class="w-3 h-3"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">إضافة صور جديدة</label>
                    <input type="file" wire:model="gallery" multiple accept=".jpg,.jpeg,.png,.webp" class="w-full text-sm text-text file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
                    @error('gallery.*') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Card 6: Publishing --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">النشر والظهور</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">حالة النشر</label>
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
                            <label for="isFeatured" class="text-sm font-semibold text-text">مشروع مميز</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex items-center gap-3">
            <button type="submit" class="px-8 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $projectId ? 'تحديث المشروع' : 'إضافة المشروع' }}</span>
                <span wire:loading>جاري الحفظ...</span>
            </button>
            <a href="{{ route('dashboard.projects') }}" class="px-8 py-3 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors" wire:navigate>إلغاء</a>
        </div>
    </form>

    <livewire:shared.media-picker :target="'cover'" :restrict-collection="'projects'" />
</div>
