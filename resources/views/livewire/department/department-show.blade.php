<div>
    <x-slot name="title">{{ $department->name }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $department->name }}</h1>
            <p class="text-sm text-text-tertiary mt-1">{{ $department->short_description }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.departments') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary text-text-secondary text-sm font-semibold hover:bg-surface-secondary/80 transition-colors">
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

    {{-- Cover --}}
    <div class="h-48 md:h-64 rounded-xl bg-surface-secondary overflow-hidden mb-6 relative">
        @if ($department->cover_image_url)
            <img src="{{ $department->cover_image_url }}" alt="{{ $department->name }}" class="w-full h-full object-cover" />
        @else
            <div class="w-full h-full flex items-center justify-center">
                <i data-lucide="{{ $department->icon ?? 'building-2' }}" class="w-16 h-16 text-text-muted/30"></i>
            </div>
        @endif
        <div class="absolute top-4 left-4 flex items-center gap-2">
            @if ($department->is_featured)
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-info-light text-info">مميز</span>
            @endif
            @if ($department->is_public)
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-surface/80 text-text-muted">عام</span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Description --}}
            @if ($department->description)
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="file-text" class="w-5 h-5 text-primary"></i>
                    <h2 class="text-lg font-bold text-text">نبذة عن الدائرة</h2>
                </div>
                <p class="text-sm text-text-secondary leading-relaxed">{{ $department->description }}</p>
            </div>
            @endif

            {{-- Vision & Mission --}}
            @if ($department->vision || $department->mission)
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if ($department->vision)
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <i data-lucide="eye" class="w-5 h-5 text-primary"></i>
                            <h3 class="text-sm font-bold text-text">الرؤية</h3>
                        </div>
                        <p class="text-sm text-text-secondary leading-relaxed">{{ $department->vision }}</p>
                    </div>
                    @endif
                    @if ($department->mission)
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <i data-lucide="target" class="w-5 h-5 text-primary"></i>
                            <h3 class="text-sm font-bold text-text">الرسالة</h3>
                        </div>
                        <p class="text-sm text-text-secondary leading-relaxed">{{ $department->mission }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Responsibilities --}}
            @if ($department->responsibilities)
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="list-checks" class="w-5 h-5 text-primary"></i>
                    <h2 class="text-lg font-bold text-text">المهام والمسؤوليات</h2>
                </div>
                <div class="text-sm text-text-secondary leading-relaxed whitespace-pre-wrap">{{ $department->responsibilities }}</div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Details --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="info" class="w-5 h-5 text-primary"></i>
                    <h2 class="text-lg font-bold text-text">معلومات</h2>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">الحالة</span>
                        @php
                            $statusColors = [
                                'active' => 'bg-success-light text-success',
                                'inactive' => 'bg-warning-light text-warning',
                                'maintenance' => 'bg-info-light text-info',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold {{ $statusColors[$department->status] ?? 'bg-surface-secondary text-text-muted' }}">{{ $statusLabel }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">ترتيب العرض</span>
                        <span class="text-sm text-text-secondary">{{ $department->display_order }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">عام</span>
                        <span class="text-sm text-text-secondary">{{ $department->is_public ? 'نعم' : 'لا' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">مميز</span>
                        <span class="text-sm text-text-secondary">{{ $department->is_featured ? 'نعم' : 'لا' }}</span>
                    </div>
                </div>
            </div>

            {{-- Manager --}}
            @if ($department->manager_name || $department->manager_position)
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="user" class="w-5 h-5 text-primary"></i>
                    <h2 class="text-lg font-bold text-text">المدير</h2>
                </div>
                <div class="space-y-2">
                    @if ($department->manager_name)
                    <div>
                        <span class="text-xs font-bold text-text-tertiary">الاسم</span>
                        <p class="text-sm font-semibold text-text">{{ $department->manager_name }}</p>
                    </div>
                    @endif
                    @if ($department->manager_position)
                    <div>
                        <span class="text-xs font-bold text-text-tertiary">المنصب</span>
                        <p class="text-sm text-text-secondary">{{ $department->manager_position }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Contact --}}
            @if ($department->phone || $department->mobile || $department->email || $department->office_location || $department->working_hours)
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="phone" class="w-5 h-5 text-primary"></i>
                    <h2 class="text-lg font-bold text-text">التواصل</h2>
                </div>
                <div class="space-y-3">
                    @if ($department->phone)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-surface-secondary flex items-center justify-center">
                            <i data-lucide="phone" class="w-4 h-4 text-text-muted"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-text-tertiary">هاتف</p>
                            <p class="text-sm font-semibold text-text" dir="ltr">{{ $department->phone }}</p>
                        </div>
                    </div>
                    @endif
                    @if ($department->extension)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-surface-secondary flex items-center justify-center">
                            <i data-lucide="switch" class="w-4 h-4 text-text-muted"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-text-tertiary">تحويلة</p>
                            <p class="text-sm font-semibold text-text" dir="ltr">{{ $department->extension }}</p>
                        </div>
                    </div>
                    @endif
                    @if ($department->mobile)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-surface-secondary flex items-center justify-center">
                            <i data-lucide="smartphone" class="w-4 h-4 text-text-muted"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-text-tertiary">جوال</p>
                            <p class="text-sm font-semibold text-text" dir="ltr">{{ $department->mobile }}</p>
                        </div>
                    </div>
                    @endif
                    @if ($department->email)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-surface-secondary flex items-center justify-center">
                            <i data-lucide="mail" class="w-4 h-4 text-text-muted"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-text-tertiary">بريد إلكتروني</p>
                            <p class="text-sm font-semibold text-text" dir="ltr">{{ $department->email }}</p>
                        </div>
                    </div>
                    @endif
                    @if ($department->office_location)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-surface-secondary flex items-center justify-center">
                            <i data-lucide="map-pin" class="w-4 h-4 text-text-muted"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-text-tertiary">موقع المكتب</p>
                            <p class="text-sm font-semibold text-text">{{ $department->office_location }}</p>
                        </div>
                    </div>
                    @endif
                    @if ($department->working_hours)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-surface-secondary flex items-center justify-center">
                            <i data-lucide="clock" class="w-4 h-4 text-text-muted"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-text-tertiary">ساعات الدوام</p>
                            <p class="text-sm font-semibold text-text">{{ $department->working_hours }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Timeline --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="clock" class="w-5 h-5 text-primary"></i>
                    <h2 class="text-lg font-bold text-text">الجدول الزمني</h2>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">تاريخ الإنشاء</span>
                        <span class="text-sm text-text-secondary">{{ $department->created_at ? $department->created_at->format('Y-m-d') : '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">آخر تحديث</span>
                        <span class="text-sm text-text-secondary">{{ $department->updated_at ? $department->updated_at->format('Y-m-d') : '—' }}</span>
                    </div>
                    @if ($department->creator)
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-text-tertiary">أنشئ بواسطة</span>
                        <span class="text-sm text-text-secondary">{{ $department->creator->name }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="settings" class="w-5 h-5 text-primary"></i>
                    <h2 class="text-lg font-bold text-text">إجراءات</h2>
                </div>
                <div class="space-y-2">
                    @if ($canUpdate)
                    <a href="{{ route('dashboard.departments.edit', $department) }}" wire:navigate class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                        تعديل
                    </a>
                    @endif
                    @if ($canTogglePublic)
                    <button wire:click="togglePublic" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary text-text-secondary text-sm font-semibold hover:bg-surface-secondary/80 transition-colors">
                        <i data-lucide="{{ $department->is_public ? 'eye-off' : 'eye' }}" class="w-4 h-4"></i>
                        {{ $department->is_public ? 'إخفاء عن العامة' : 'ظهور للعامة' }}
                    </button>
                    @endif
                    @if ($canToggleFeatured)
                    <button wire:click="toggleFeatured" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-surface-secondary text-text-secondary text-sm font-semibold hover:bg-surface-secondary/80 transition-colors">
                        <i data-lucide="star" class="w-4 h-4"></i>
                        {{ $department->is_featured ? 'إزالة التمييز' : 'تمييز' }}
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
                <h3 class="text-lg font-bold text-text mb-2">حذف الدائرة</h3>
                <p class="text-sm text-text-tertiary">هل أنت متأكد من حذف هذه الدائرة؟</p>
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
