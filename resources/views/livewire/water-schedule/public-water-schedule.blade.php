<div>

    @livewire('public-page-carousel', [
        'pageKey' => 'water-schedule',
        'fallbackTitle' => "جدول توزيع المياه",
        'fallbackDescription' => "تفقد جدول الضخ الأسبوعي للمياه في مختلف مناطق بلدية إذنا.",
        'fallbackBadge' => 'جدول المياه',
        'fallbackIcon' => 'droplets',
        'fallbackImage' => $slides->isNotEmpty() ? $slides->first()->image_url : null,
        'compact' => true,
    ])

    <div class="max-w-3xl mx-auto px-4 py-8 space-y-6">

        @if ($activeMaintenance)
            <div class="rounded-2xl border-2 border-red-300 bg-red-50 p-5 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-red-800 text-lg">{{ $activeMaintenance->title }}</h3>
                        @if ($activeMaintenance->description)
                            <p class="text-red-700 text-sm mt-1">{{ $activeMaintenance->description }}</p>
                        @endif
                        @if ($activeMaintenance->affected_areas)
                            <div class="mt-2">
                                <p class="text-xs font-semibold text-red-700">المناطق المتأثرة:</p>
                                <div class="flex flex-wrap gap-1.5 mt-1">
                                    @foreach ($activeMaintenance->affected_areas as $area)
                                        <span class="inline-block bg-red-100 text-red-700 text-xs px-2.5 py-1 rounded-full font-medium">{{ $area }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="flex items-center gap-4 mt-2 text-xs text-red-600">
                            <span class="flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                من {{ $activeMaintenance->starts_at->format('h:i A') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                إلى {{ $activeMaintenance->ends_at->format('h:i A') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
            <label class="block text-sm font-bold text-text mb-2">اختر المنطقة</label>
            <select wire:model.live="selectedAreaId" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-base text-text focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all appearance-none cursor-pointer">
                <option value="">-- اختر المنطقة --</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                @endforeach
            </select>
        </div>

        @php
            $currentSchedule = $this->currentSchedule;
            $todayDayName = $this->todayDayName;
            $latestUpdate = $this->latestUpdate;
        @endphp

        @if ($currentSchedule)
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="text-center mb-6">
                    <p class="text-xs font-semibold text-text-tertiary uppercase tracking-wider">اليوم</p>
                    <p class="text-xl font-bold text-text mt-1">{{ $todayDayName }}</p>
                </div>

                <div class="flex items-center justify-center gap-8 mb-6">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-text">{{ $currentSchedule->start_time ? \Carbon\Carbon::parse($currentSchedule->start_time)->format('h:i A') : '—' }}</p>
                        <p class="text-xs text-text-tertiary mt-1">بداية الضخ</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <i data-lucide="arrow-down" class="w-5 h-5 text-primary"></i>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-text">{{ $currentSchedule->end_time ? \Carbon\Carbon::parse($currentSchedule->end_time)->format('h:i A') : '—' }}</p>
                        <p class="text-xs text-text-tertiary mt-1">نهاية الضخ</p>
                    </div>
                </div>

                <div class="text-center mb-6">
                    @php
                        $statusColors = [
                            'available' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'check-circle', 'label' => 'يوجد ضخ'],
                            'low_pressure' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'icon' => 'alert-triangle', 'label' => 'ضغط منخفض'],
                            'maintenance' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'icon' => 'tools', 'label' => 'صيانة'],
                            'emergency' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => 'alert-octagon', 'label' => 'طارئ'],
                            'no_water' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'x-circle', 'label' => 'لا يوجد ضخ'],
                        ];
                        $status = $currentSchedule->status?->value ?? 'available';
                        $colors = $statusColors[$status] ?? $statusColors['available'];
                    @endphp
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold {{ $colors['bg'] }} {{ $colors['text'] }}">
                        <i data-lucide="{{ $colors['icon'] }}" class="w-4 h-4"></i>
                        {{ $colors['label'] }}
                    </span>
                </div>

                @if ($currentSchedule->notes)
                    <div class="border-t border-gray-100 pt-4 mt-4">
                        <p class="text-xs font-semibold text-text-tertiary mb-1">الملاحظات</p>
                        <p class="text-sm text-text-secondary">{{ $currentSchedule->notes }}</p>
                    </div>
                @endif
            </div>
        @elseif($selectedAreaId)
            <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center shadow-sm">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="calendar-x" class="w-8 h-8 text-text-tertiary"></i>
                </div>
                <p class="text-text-secondary font-medium">لا يوجد جدول لهذه المنطقة اليوم</p>
                <p class="text-sm text-text-tertiary mt-1">سيتم تحديث الجدول قريباً</p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center shadow-sm">
                <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="map-pin" class="w-8 h-8 text-primary"></i>
                </div>
                <p class="text-text-secondary font-medium">اختر المنطقة لعرض جدول الضخ</p>
            </div>
        @endif

        @if ($latestUpdate)
            <div class="text-center py-4">
                <p class="text-xs text-text-tertiary">آخر تحديث</p>
                <p class="text-xs font-semibold text-text-secondary mt-0.5">{{ $latestUpdate }}</p>
            </div>
        @endif

        <div class="text-center pt-4 pb-8 border-t border-gray-100">
            <p class="text-xs text-text-tertiary">جميع الحقوق محفوظة &copy; {{ date('Y') }} بلدية إذنا</p>
        </div>
    </div>
</div>