<div>

    {{-- ============================================ --}}
    {{-- 1. PAGE CAROUSEL (inherited from Engineering Offices) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'engineering-offices',
        'pageTitle' => $office->office_name,
        'pageSubtitle' => $office->engineer_name ? 'المهندس: ' . $office->engineer_name : null,
        'pageBadge' => 'مكتب هندسي',
        'pageBadgeIcon' => 'hard-hat',
        'compact' => true,
    ])

    <section class="py-12 sm:py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-gray-100">
                    <div class="p-6 space-y-4">
                        <h2 class="text-xs font-bold text-text-tertiary uppercase tracking-wider">معلومات المكتب</h2>
                        @if ($office->license_number)
                            <div>
                                <p class="text-xs text-text-tertiary">رقم الترخيص</p>
                                <p class="text-sm font-bold text-text" dir="ltr">{{ $office->license_number }}</p>
                            </div>
                        @endif
                        @if ($office->engineer_name)
                            <div>
                                <p class="text-xs text-text-tertiary">المهندس المسؤول</p>
                                <p class="text-sm font-bold text-text">{{ $office->engineer_name }}</p>
                            </div>
                        @endif
                        @if ($office->address)
                            <div>
                                <p class="text-xs text-text-tertiary">العنوان</p>
                                <p class="text-sm text-text">{{ $office->address }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 space-y-4">
                        <h2 class="text-xs font-bold text-text-tertiary uppercase tracking-wider">معلومات الاتصال</h2>
                        @if ($office->phone)
                            <div>
                                <p class="text-xs text-text-tertiary">الهاتف</p>
                                <p class="text-sm font-bold text-text" dir="ltr"><a href="tel:{{ $office->phone }}" style="color:inherit;text-decoration:none;">{{ $office->phone }}</a></p>
                            </div>
                        @endif
                        @if ($office->mobile)
                            <div>
                                <p class="text-xs text-text-tertiary">الجوال</p>
                                <p class="text-sm font-bold text-text" dir="ltr"><a href="tel:{{ $office->mobile }}" style="color:inherit;text-decoration:none;">{{ $office->mobile }}</a></p>
                            </div>
                        @endif
                        @if ($office->email)
                            <div>
                                <p class="text-xs text-text-tertiary">البريد الإلكتروني</p>
                                <p class="text-sm font-bold text-text dir-ltr"><a href="mailto:{{ $office->email }}" style="color:inherit;text-decoration:none;">{{ $office->email }}</a></p>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 space-y-4">
                        <h2 class="text-xs font-bold text-text-tertiary uppercase tracking-wider">حالة الاعتماد</h2>
                        @php
                            $statusColors = match($office->approval_status) {
                                'approved' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'suspended' => 'bg-red-100 text-red-700',
                                'expired' => 'bg-gray-100 text-gray-600',
                                default => 'bg-gray-100 text-gray-600',
                            };
                            $statusLabels = [
                                'approved' => 'معتمد',
                                'pending' => 'قيد الانتظار',
                                'suspended' => 'موقوف',
                                'expired' => 'منتهي الصلاحية',
                            ];
                        @endphp
                        <div>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-lg {{ $statusColors }}">
                                {{ $statusLabels[$office->approval_status] ?? $office->approval_status }}
                            </span>
                        </div>
                        @if ($office->expires_at)
                            <div>
                                <p class="text-xs text-text-tertiary">تاريخ انتهاء الاعتماد</p>
                                <p class="text-sm font-bold text-text">{{ $office->expires_at->format('Y-m-d') }}</p>
                            </div>
                        @endif
                        @if ($office->approved_at)
                            <div>
                                <p class="text-xs text-text-tertiary">تاريخ الاعتماد</p>
                                <p class="text-sm text-text">{{ $office->approved_at->format('Y-m-d') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($office->specializations && is_array($office->specializations) && count($office->specializations) > 0)
                    <div class="border-t border-gray-100 px-6 py-5">
                        <h2 class="text-xs font-bold text-text-tertiary uppercase tracking-wider mb-3">التخصصات</h2>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;">
                            @foreach ($office->specializations as $spec)
                                <span style="font-size:12px;font-weight:600;color:#0F6A3D;background:rgba(15,106,61,0.06);padding:4px 12px;border-radius:8px;">{{ $spec }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($office->notes)
                    <div class="border-t border-gray-100 px-6 py-5">
                        <h2 class="text-xs font-bold text-text-tertiary uppercase tracking-wider mb-2">ملاحظات</h2>
                        <p class="text-sm text-text-secondary">{{ $office->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

</div>