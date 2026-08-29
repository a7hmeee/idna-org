<div>
    <x-slot name="title">إدارة الكاروسيلات</x-slot>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">إدارة الكاروسيلات</h1>
            <p class="text-sm text-text-tertiary mt-1">التحكم المركزي في جميع كاروسيلات وشرائح الموقع العام</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="sync" class="px-4 py-2.5 rounded-xl bg-surface-secondary border border-border text-text text-sm font-semibold hover:bg-border transition-colors inline-flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                <span>مزامنة</span>
            </button>
            @can('createSlide', \App\Domains\Homepage\Models\HomepageSetting::class)
                <a href="{{ route('dashboard.page-carousels.create') }}"
                   class="px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors inline-flex items-center gap-2" wire:navigate>
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>إضافة شريحة</span>
                </a>
            @endcan
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Tabs --}}
    <div class="flex items-center gap-1 mb-6 border-b border-border">
        <a href="{{ route('dashboard.page-carousels') }}" wire:navigate
           class="px-4 py-2.5 text-sm font-semibold text-text-tertiary hover:text-text transition-colors border-b-2 border-transparent">
            <span>الشرائح</span>
        </a>
        <a href="{{ route('dashboard.carousel-config') }}" wire:navigate
           class="px-4 py-2.5 text-sm font-semibold text-primary border-b-2 border-primary">
            <span>إعدادات الكاروسيلات</span>
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-surface rounded-xl border border-border overflow-hidden mb-6">
        <div class="p-4">
            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                <div class="flex-1 w-full">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث بالاسم أو المفتاح أو الصفحة..."
                           class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <select wire:model.live="filterPage" class="bg-surface-secondary border border-border rounded-xl px-4 py-2 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                    <option value="">جميع الصفحات</option>
                    @foreach ($pages as $page)
                        <option value="{{ $page }}">{{ $page }}</option>
                    @endforeach
                </select>
                <span class="text-xs text-text-tertiary bg-surface-secondary px-3 py-1.5 rounded-lg whitespace-nowrap">
                    <span class="font-semibold">{{ $carousels->count() }}</span> كاروسيل
                </span>
            </div>
        </div>
    </div>

    {{-- Carousel Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($carousels as $carousel)
            <div class="bg-surface rounded-xl border border-border overflow-hidden transition-all hover:shadow-card {{ !$carousel->is_active ? 'opacity-60' : '' }}">
                {{-- Card Header --}}
                <div class="p-4 border-b border-border">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-bold text-text text-sm truncate">{{ $carousel->name }}</h3>
                                @if (!$carousel->is_active)
                                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-danger/10 text-danger">معطل</span>
                                @endif
                            </div>
                            @if ($carousel->title)
                                <p class="text-xs text-text-secondary truncate mb-1">{{ $carousel->title }}</p>
                            @endif
                            @if ($carousel->subtitle)
                                <p class="text-[10px] text-text-tertiary truncate">{{ $carousel->subtitle }}</p>
                            @endif
                            <div class="flex items-center gap-2 flex-wrap mt-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-primary/10 text-primary">{{ $carousel->key }}</span>
                                @if ($carousel->page)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-surface-secondary text-text-tertiary">{{ $carousel->page }}</span>
                                @endif
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-accent-light/40 text-accent">{{ $carousel->type }}</span>
                            </div>
                        </div>
                        @can('updateSlide', \App\Domains\Homepage\Models\HomepageSetting::class)
                            <button wire:click="toggle({{ $carousel->id }})"
                                    class="shrink-0 p-1.5 rounded-lg transition-all {{ $carousel->is_active ? 'bg-success/10 text-success hover:bg-success/20' : 'bg-surface-secondary text-text-tertiary hover:bg-border' }}">
                                <i data-lucide="{{ $carousel->is_active ? 'eye' : 'eye-off' }}" class="w-4 h-4"></i>
                            </button>
                        @endcan
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-4">
                    <div class="grid grid-cols-3 gap-3 mb-3">
                        <div class="text-center">
                            <p class="text-[10px] text-text-tertiary mb-0.5">سطح المكتب</p>
                            <p class="text-lg font-black text-text">{{ $carousel->desktop_slides }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] text-text-tertiary mb-0.5">الجهاز اللوحي</p>
                            <p class="text-lg font-black text-text">{{ $carousel->tablet_slides }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] text-text-tertiary mb-0.5">الجوال</p>
                            <p class="text-lg font-black text-text">{{ $carousel->mobile_slides }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-1.5">
                        @if ($carousel->autoplay)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-primary/10 text-primary">
                                <i data-lucide="play" class="w-3 h-3"></i>
                                تشغيل تلقائي {{ $carousel->autoplay_delay / 1000 }}ث
                            </span>
                        @endif
                        @if ($carousel->show_navigation)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-surface-secondary text-text-tertiary">
                                <i data-lucide="arrow-left-right" class="w-3 h-3"></i>
                                أسهم
                            </span>
                        @endif
                        @if ($carousel->show_pagination)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-surface-secondary text-text-tertiary">
                                <i data-lucide="more-horizontal" class="w-3 h-3"></i>
                                نقاط
                            </span>
                        @endif
                        @if ($carousel->loop)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-surface-secondary text-text-tertiary">
                                <i data-lucide="repeat" class="w-3 h-3"></i>
                                تكرار
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Card Footer --}}
                @can('updateSlide', \App\Domains\Homepage\Models\HomepageSetting::class)
                    <div class="px-4 py-3 border-t border-border bg-surface-secondary/50">
                        <button wire:click="edit({{ $carousel->id }})"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            <span>تعديل الإعدادات</span>
                        </button>
                    </div>
                @endcan
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-surface rounded-xl border border-border p-12 text-center">
                    <i data-lucide="sliders" class="w-10 h-10 text-text-tertiary mx-auto mb-3"></i>
                    <p class="text-sm text-text-tertiary mb-2">لا توجد كاروسيلات مسجلة</p>
                    <button wire:click="sync" class="text-sm text-primary font-semibold hover:underline">مزامنة الآن</button>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Edit Modal --}}
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm p-4" wire:click.self="closeEditModal">
            <div class="bg-surface rounded-2xl border border-border w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-dropdown">
                {{-- Modal Header --}}
                <div class="sticky top-0 bg-surface border-b border-border px-6 py-4 flex items-center justify-between rounded-t-2xl">
                    <div>
                        <h3 class="font-bold text-text">تعديل إعدادات الكاروسيل</h3>
                        <p class="text-xs text-text-tertiary mt-0.5">{{ $editName }}</p>
                    </div>
                    <button wire:click="closeEditModal" class="p-2 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-text transition-all">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit="save" class="p-6 space-y-6">
                    {{-- General --}}
                    <div>
                        <h4 class="text-sm font-bold text-text mb-3">عام</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-text mb-1.5">اسم الكاروسيل</label>
                                <input type="text" wire:model="editName"
                                       class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                                @error('editName') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-text mb-1.5">ترتيب العرض</label>
                                <input type="number" wire:model="editSortOrder" min="0"
                                       class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                                @error('editSortOrder') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-text mb-1.5">العنوان المعروض</label>
                                <input type="text" wire:model="editTitle" placeholder="العنوان الذي يظهر في الموقع (اختياري)"
                                       class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                                <p class="text-[10px] text-text-tertiary mt-1">العنوان الرئيسي الذي يظهر في الكاروسيل. إذا تُرك فارغاً، يُستخدم العنوان من الشرائح أو القيمة الافتراضية.</p>
                                @error('editTitle') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-text mb-1.5">الوصف المعروض</label>
                                <input type="text" wire:model="editSubtitle" placeholder="الوصف الفرعي الذي يظهر في الموقع (اختياري)"
                                       class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                                <p class="text-[10px] text-text-tertiary mt-1">الوصف الفرعي الذي يظهر تحت العنوان. اختياري.</p>
                                @error('editSubtitle') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="editIsActive" class="w-5 h-5 rounded-lg border-border text-primary focus:ring-primary/20" />
                                <span class="text-sm font-semibold text-text">نشط</span>
                            </label>
                        </div>
                    </div>

                    {{-- Responsive Slides --}}
                    <div>
                        <h4 class="text-sm font-bold text-text mb-3">عدد العناصر المرئية</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-text-tertiary mb-1.5">سطح المكتب</label>
                                <input type="number" wire:model="editDesktopSlides" min="1" max="12"
                                       class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text text-center focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                                @error('editDesktopSlides') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-tertiary mb-1.5">الجهاز اللوحي</label>
                                <input type="number" wire:model="editTabletSlides" min="1" max="8"
                                       class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text text-center focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                                @error('editTabletSlides') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-tertiary mb-1.5">الجوال</label>
                                <input type="number" wire:model="editMobileSlides" min="1" max="4"
                                       class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text text-center focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                                @error('editMobileSlides') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Autoplay --}}
                    <div>
                        <h4 class="text-sm font-bold text-text mb-3">التشغيل التلقائي</h4>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="editAutoplay" class="w-5 h-5 rounded-lg border-border text-primary focus:ring-primary/20" />
                                <span class="text-sm font-semibold text-text">تفعيل التشغيل التلقائي</span>
                            </label>
                            @if ($editAutoplay)
                                <div>
                                    <label class="block text-xs font-semibold text-text-tertiary mb-1.5">مدة التشغيل (مللي ثانية)</label>
                                    <input type="number" wire:model="editAutoplayDelay" min="1000" max="30000" step="1000"
                                           class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                                    <p class="text-[10px] text-text-tertiary mt-1">القيمة الحالية: {{ $editAutoplayDelay / 1000 }} ثانية</p>
                                    @error('editAutoplayDelay') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Behavior --}}
                    <div>
                        <h4 class="text-sm font-bold text-text mb-3">السلوك</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-border hover:border-primary/30 transition-colors">
                                <input type="checkbox" wire:model="editLoop" class="w-5 h-5 rounded-lg border-border text-primary focus:ring-primary/20" />
                                <div>
                                    <span class="text-sm font-semibold text-text block">تكرار</span>
                                    <span class="text-[10px] text-text-tertiary">يعود للبداية بعد النهاية</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-border hover:border-primary/30 transition-colors">
                                <input type="checkbox" wire:model="editShowNavigation" class="w-5 h-5 rounded-lg border-border text-primary focus:ring-primary/20" />
                                <div>
                                    <span class="text-sm font-semibold text-text block">أسهم التنقل</span>
                                    <span class="text-[10px] text-text-tertiary">أسهم التالي/السابق</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-border hover:border-primary/30 transition-colors">
                                <input type="checkbox" wire:model="editShowPagination" class="w-5 h-5 rounded-lg border-border text-primary focus:ring-primary/20" />
                                <div>
                                    <span class="text-sm font-semibold text-text block">نقاط التنقل</span>
                                    <span class="text-[10px] text-text-tertiary">نقاط في الأسفل</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-border hover:border-primary/30 transition-colors">
                                <input type="checkbox" wire:model="editPauseOnHover" class="w-5 h-5 rounded-lg border-border text-primary focus:ring-primary/20" />
                                <div>
                                    <span class="text-sm font-semibold text-text block">إيقاف مؤقت</span>
                                    <span class="text-[10px] text-text-tertiary">عند تحريك الماوس</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Direction & Transition --}}
                    <div>
                        <h4 class="text-sm font-bold text-text mb-3">الاتجاه والانتقال</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-text-tertiary mb-1.5">الاتجاه</label>
                                <select wire:model="editDirection" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                                    <option value="rtl">من اليمين لليسار (RTL)</option>
                                    <option value="ltr">من اليسار لليمين (LTR)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-tertiary mb-1.5">نوع الانتقال</label>
                                <select wire:model="editTransition" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                                    <option value="slide">انزلاق (Slide)</option>
                                    <option value="fade">تلاشي (Fade)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeEditModal" class="px-4 py-2.5 rounded-xl bg-surface-secondary text-text text-sm font-semibold hover:bg-border transition-colors">إلغاء</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                            <span wire:loading.remove>حفظ الإعدادات</span>
                            <span wire:loading>جاري الحفظ...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
