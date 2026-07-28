<div>
    <x-slot name="title">{{ $office->office_name }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $office->office_name }}</h1>
            <p class="text-sm text-text-tertiary mt-1">{{ $office->engineer_name ? "بإشراف المهندس {$office->engineer_name}" : 'مكتب هندسي' }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.engineering-offices') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface border border-border text-text-secondary text-sm font-semibold hover:bg-surface-secondary transition-colors">
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                العودة
            </a>
            @if ($canUpdate)
            <a href="{{ route('dashboard.engineering-offices.edit', $office) }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                تعديل
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Info --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="building-2" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">بيانات المكتب</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-text-tertiary">اسم المكتب</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $office->office_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">الرابط المختصر</p>
                        <p class="text-sm font-semibold text-text mt-1" dir="ltr">{{ $office->slug }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">رقم الترخيص</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $office->license_number ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">ترتيب الظهور</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $office->sort_order }}</p>
                    </div>
                </div>
            </div>

            {{-- Engineer Info --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="user" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">بيانات المهندس</h2>
                </div>
                <p class="text-sm font-semibold text-text">{{ $office->engineer_name ?? '—' }}</p>
            </div>

            {{-- Contact Info --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="phone" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">معلومات التواصل</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-text-tertiary">الهاتف</p>
                        <p class="text-sm font-semibold text-text mt-1" dir="ltr">{{ $office->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">الجوال</p>
                        <p class="text-sm font-semibold text-text mt-1" dir="ltr">{{ $office->mobile ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">البريد الإلكتروني</p>
                        <p class="text-sm font-semibold text-text mt-1" dir="ltr">{{ $office->email ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">العنوان</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $office->address ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Specializations --}}
            @if (!empty($office->specializations))
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="list-checks" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">التخصصات</h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($office->specializations as $spec)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-primary-50 text-primary text-xs font-bold">{{ $spec }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Notes --}}
            @if ($office->notes)
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="file-text" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">الملاحظات</h2>
                </div>
                <p class="text-sm text-text">{{ $office->notes }}</p>
            </div>
            @endif
        </div>

        <div class="space-y-4">
            {{-- Status --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="settings" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">الحالة</h2>
                </div>
                @php
                    $approvalColors = [
                        'approved' => 'bg-success-light text-success',
                        'pending' => 'bg-warning-light text-warning',
                        'suspended' => 'bg-danger-light text-danger',
                        'expired' => 'bg-surface-secondary text-text-muted',
                    ];
                    $approvalLabels = [
                        'approved' => 'معتمد',
                        'pending' => 'قيد الانتظار',
                        'suspended' => 'موقوف',
                        'expired' => 'منتهي',
                    ];
                    $statusColors = [
                        'active' => 'bg-success-light text-success',
                        'inactive' => 'bg-surface-secondary text-text-muted',
                    ];
                    $statusLabels = [
                        'active' => 'نشط',
                        'inactive' => 'غير نشط',
                    ];
                @endphp
                <div class="space-y-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold {{ $approvalColors[$office->approval_status] ?? 'bg-surface-secondary text-text-muted' }}">
                        {{ $approvalLabels[$office->approval_status] ?? $office->approval_status }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold {{ $statusColors[$office->status] ?? 'bg-surface-secondary text-text-muted' }} mr-2">
                        {{ $statusLabels[$office->status] ?? $office->status }}
                    </span>
                </div>
                @if ($office->approved_at)
                <p class="text-xs text-text-tertiary mt-3">تاريخ الاعتماد: {{ $office->approved_at->format('Y-m-d') }}</p>
                @endif
                @if ($office->suspended_at)
                <p class="text-xs text-text-tertiary mt-1">تاريخ الإيقاف: {{ $office->suspended_at->format('Y-m-d') }}</p>
                @endif
                @if ($office->expires_at)
                <p class="text-xs text-text-tertiary mt-1">انتهاء الاعتماد: {{ $office->expires_at->format('Y-m-d') }}</p>
                @endif
                <div class="mt-3">
                    <span class="inline-flex items-center gap-1 text-xs font-bold {{ $office->is_public ? 'text-success' : 'text-text-tertiary' }}">
                        <i data-lucide="{{ $office->is_public ? 'eye' : 'eye-off' }}" class="w-3 h-3"></i>
                        {{ $office->is_public ? 'مرئي للعامة' : 'مخفي عن العامة' }}
                    </span>
                </div>
            </div>

            {{-- Creator / Updater --}}
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="users" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">معلومات السجل</h2>
                </div>
                @if ($office->creator)
                <p class="text-xs text-text-tertiary">منشئ السجل</p>
                <p class="text-xs font-semibold text-text mt-0.5">{{ $office->creator->name }}</p>
                <p class="text-[10px] text-text-tertiary mt-0.5">{{ $office->created_at?->format('Y-m-d H:i') }}</p>
                @endif
                @if ($office->updater && $office->updater_id !== $office->creator_id)
                <div class="mt-2 pt-2 border-t border-border">
                    <p class="text-xs text-text-tertiary">آخر تعديل</p>
                    <p class="text-xs font-semibold text-text mt-0.5">{{ $office->updater->name }}</p>
                    <p class="text-[10px] text-text-tertiary mt-0.5">{{ $office->updated_at?->format('Y-m-d H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>