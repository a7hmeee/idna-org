<div>
    <x-slot name="title">{{ $councilMember->full_name }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $councilMember->full_name }}</h1>
            <p class="text-sm text-text-tertiary mt-1">{{ $positionLabel }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.municipality.council-members') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary text-text-secondary text-sm font-semibold hover:bg-surface-secondary/80 transition-colors">
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                عودة
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Bio --}}
            @if ($councilMember->bio)
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">نبذة تعريفية</h2>
                <p class="text-sm text-text-secondary leading-relaxed">{{ $councilMember->bio }}</p>
            </div>
            @endif

            {{-- Contact --}}
            @if ($councilMember->phone || $councilMember->mobile || $councilMember->email || $councilMember->address)
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">معلومات الاتصال</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if ($councilMember->phone)
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-surface-secondary flex items-center justify-center">
                            <i data-lucide="phone" class="w-4 h-4 text-text-muted"></i>
                        </div>
                        <div>
                            <p class="text-xs text-text-tertiary">هاتف</p>
                            <p class="text-sm font-semibold text-text" dir="ltr">{{ $councilMember->phone }}</p>
                        </div>
                    </div>
                    @endif
                    @if ($councilMember->mobile)
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-surface-secondary flex items-center justify-center">
                            <i data-lucide="smartphone" class="w-4 h-4 text-text-muted"></i>
                        </div>
                        <div>
                            <p class="text-xs text-text-tertiary">جوال</p>
                            <p class="text-sm font-semibold text-text" dir="ltr">{{ $councilMember->mobile }}</p>
                        </div>
                    </div>
                    @endif
                    @if ($councilMember->email)
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-surface-secondary flex items-center justify-center">
                            <i data-lucide="mail" class="w-4 h-4 text-text-muted"></i>
                        </div>
                        <div>
                            <p class="text-xs text-text-tertiary">بريد إلكتروني</p>
                            <p class="text-sm font-semibold text-text" dir="ltr">{{ $councilMember->email }}</p>
                        </div>
                    </div>
                    @endif
                    @if ($councilMember->address)
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-surface-secondary flex items-center justify-center">
                            <i data-lucide="map-pin" class="w-4 h-4 text-text-muted"></i>
                        </div>
                        <div>
                            <p class="text-xs text-text-tertiary">العنوان</p>
                            <p class="text-sm font-semibold text-text">{{ $councilMember->address }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Social --}}
            @if ($councilMember->facebook || $councilMember->twitter || $councilMember->linkedin)
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">وسائل التواصل الاجتماعي</h2>
                <div class="flex flex-wrap gap-3">
                    @if ($councilMember->facebook)
                    <a href="{{ $councilMember->facebook }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1877F2]/10 text-[#1877F2] text-sm font-semibold hover:bg-[#1877F2]/20 transition-colors">
                        <i data-lucide="facebook" class="w-4 h-4"></i>
                        فيسبوك
                    </a>
                    @endif
                    @if ($councilMember->twitter)
                    <a href="{{ $councilMember->twitter }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-surface-secondary text-text-secondary text-sm font-semibold hover:bg-surface-secondary/80 transition-colors">
                        <i data-lucide="twitter" class="w-4 h-4"></i>
                        تويتر
                    </a>
                    @endif
                    @if ($councilMember->linkedin)
                    <a href="{{ $councilMember->linkedin }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0A66C2]/10 text-[#0A66C2] text-sm font-semibold hover:bg-[#0A66C2]/20 transition-colors">
                        <i data-lucide="linkedin" class="w-4 h-4"></i>
                        لينكد إن
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Photo + Info --}}
            <div class="bg-surface rounded-xl border border-border p-6 text-center">
                <div class="w-28 h-28 rounded-2xl bg-surface-secondary mx-auto overflow-hidden flex items-center justify-center mb-4">
                    @if ($councilMember->photo_url)
                        <img src="{{ $councilMember->photo_url }}" alt="{{ $councilMember->full_name }}" class="w-full h-full object-cover" />
                    @else
                        <span class="text-3xl font-bold text-text-tertiary">{{ mb_substr($councilMember->full_name, 0, 1) }}</span>
                    @endif
                </div>
                <h3 class="text-base font-bold text-text">{{ $councilMember->full_name }}</h3>
                <p class="text-sm text-text-secondary mt-1">{{ $positionLabel }}</p>
                <div class="flex items-center justify-center gap-2 mt-3">
                    @php
                        $statusColors = [
                            'active' => 'bg-success-light text-success',
                            'inactive' => 'bg-warning-light text-warning',
                            'former' => 'bg-surface-secondary text-text-muted',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold {{ $statusColors[$councilMember->status] ?? 'bg-surface-secondary text-text-muted' }}">{{ $statusLabel }}</span>
                    @if ($councilMember->is_featured)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-info-light text-info">مميز</span>
                    @endif
                </div>
            </div>

            {{-- Details --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">التفاصيل</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">المنصب</span>
                        <span class="text-sm text-text-secondary">{{ $positionLabel }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">الحالة</span>
                        <span class="text-sm text-text-secondary">{{ $statusLabel }}</span>
                    </div>
                    @if ($councilMember->national_number)
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">الرقم الوطني</span>
                        <span class="text-sm text-text-secondary" dir="ltr">{{ $councilMember->national_number }}</span>
                    </div>
                    @endif
                    @if ($councilMember->qualification)
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">المؤهل</span>
                        <span class="text-sm text-text-secondary">{{ $councilMember->qualification }}</span>
                    </div>
                    @endif
                    @if ($councilMember->profession)
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">المهنة</span>
                        <span class="text-sm text-text-secondary">{{ $councilMember->profession }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">بداية العضوية</span>
                        <span class="text-sm text-text-secondary">{{ $councilMember->term_start ? \Carbon\Carbon::parse($councilMember->term_start)->format('Y-m-d') : '—' }}</span>
                    </div>
                    @if ($councilMember->term_end)
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">نهاية العضوية</span>
                        <span class="text-sm text-text-secondary">{{ \Carbon\Carbon::parse($councilMember->term_end)->format('Y-m-d') }}</span>
                    </div>
                    @endif
                    @if ($councilMember->years_of_experience)
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">سنوات الخبرة</span>
                        <span class="text-sm text-text-secondary">{{ $councilMember->years_of_experience }}</span>
                    </div>
                    @endif
                    @if ($councilMember->committee)
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">اللجنة</span>
                        <span class="text-sm text-text-secondary">{{ $councilMember->committee }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">ترتيب العرض</span>
                        <span class="text-sm text-text-secondary">{{ $councilMember->display_order }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">عام</span>
                        <span class="text-sm text-text-secondary">{{ $councilMember->is_public ? 'نعم' : 'لا' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">مميز</span>
                        <span class="text-sm text-text-secondary">{{ $councilMember->is_featured ? 'نعم' : 'لا' }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <h2 class="text-lg font-bold text-text mb-4">إجراءات</h2>
                <div class="space-y-2">
                    @if ($canUpdate)
                    <a href="{{ route('dashboard.municipality.council-members.edit', $councilMember) }}" wire:navigate class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                        تعديل
                    </a>
                    @endif
                    @if ($canTogglePublic)
                    <button wire:click="togglePublic" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary text-text-secondary text-sm font-semibold hover:bg-surface-secondary/80 transition-colors">
                        <i data-lucide="{{ $councilMember->is_public ? 'eye-off' : 'eye' }}" class="w-4 h-4"></i>
                        {{ $councilMember->is_public ? 'إخفاء عن العامة' : 'ظهور للعامة' }}
                    </button>
                    @endif
                    @if ($canToggleFeatured)
                    <button wire:click="toggleFeatured" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary text-text-secondary text-sm font-semibold hover:bg-surface-secondary/80 transition-colors">
                        <i data-lucide="star" class="w-4 h-4"></i>
                        {{ $councilMember->is_featured ? 'إزالة التمييز' : 'تمييز' }}
                    </button>
                    @endif
                    @if ($canDelete)
                    <button wire:click="confirmDelete" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-danger/30 text-danger text-sm font-semibold hover:bg-danger-light transition-colors">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        حذف
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeDeleteModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-md">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-danger-light flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-danger"></i>
                </div>
                <h3 class="text-lg font-bold text-text mb-2">حذف العضو</h3>
                <p class="text-sm text-text-tertiary">هل أنت متأكد من حذف هذا العضو؟</p>
                <p class="text-xs text-text-muted mt-2">لا يمكن التراجع عن هذه العملية.</p>
            </div>
            <div class="flex items-center justify-center gap-3 px-6 pb-6">
                <button wire:click="closeDeleteModal" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                <button wire:click="delete" class="px-5 py-2.5 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors">
                    نعم، حذف
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
