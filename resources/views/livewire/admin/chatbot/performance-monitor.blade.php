<div class="p-6 space-y-6" dir="rtl">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <i data-lucide="gauge" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-text">مراقبة الأداء</h1>
                <p class="text-xs text-text-tertiary">سرعة الاستجابة والمعالجة</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <select wire:model.live="period"
                    class="text-sm border border-border rounded-lg px-3 py-1.5 bg-surface text-text focus:ring-2 focus:ring-primary/30 outline-none">
                <option value="1">آخر يوم</option>
                <option value="7">آخر 7 أيام</option>
                <option value="30">آخر 30 يوم</option>
            </select>
        </div>
    </div>

    {{-- KPI Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-surface border border-border rounded-xl p-4">
            <p class="text-xs text-text-tertiary">إجمالي الطلبات</p>
            <p class="text-2xl font-bold text-text">{{ number_format($report['totalRequests'] ?? 0) }}</p>
        </div>
        <div class="bg-surface border border-border rounded-xl p-4">
            <p class="text-xs text-text-tertiary">متوسط الاستجابة</p>
            <p class="text-2xl font-bold text-text">{{ number_format($report['avgResponseTimeMs'] ?? 0, 1) }} ms</p>
        </div>
        <div class="bg-surface border border-border rounded-xl p-4">
            <p class="text-xs text-text-tertiary">P95</p>
            <p class="text-2xl font-bold text-text">{{ number_format($report['p95ResponseTimeMs'] ?? 0, 1) }} ms</p>
        </div>
        <div class="bg-surface border border-border rounded-xl p-4">
            <p class="text-xs text-text-tertiary">نسبة البطء</p>
            <p class="text-2xl font-bold {{ ($report['slowRate'] ?? 0) > 10 ? 'text-red-500' : 'text-green-500' }}">
                {{ $report['slowRate'] ?? 0 }}%
            </p>
        </div>
    </div>

    {{-- Slow Handlers --}}
    @if(!empty($report['slowHandlers']))
        <div class="bg-surface border border-border rounded-xl p-5">
            <h2 class="text-sm font-semibold text-text mb-4 flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-500"></i>
                المعالجات البطيئة
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="text-right pb-2 text-text-secondary font-medium">المعالج</th>
                            <th class="text-right pb-2 text-text-secondary font-medium">العدد</th>
                            <th class="text-right pb-2 text-text-secondary font-medium">متوسط ms</th>
                            <th class="text-right pb-2 text-text-secondary font-medium">أقصى ms</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($report['slowHandlers'] as $handler)
                            <tr class="hover:bg-surface-hover">
                                <td class="py-2 text-text font-mono text-xs truncate max-w-xs">{{ $handler['handler_class'] ?? '—' }}</td>
                                <td class="py-2 text-text-secondary">{{ $handler['count'] }}</td>
                                <td class="py-2 text-amber-600 font-medium">{{ number_format((float) $handler['avg_ms'], 1) }}</td>
                                <td class="py-2 text-red-500 font-medium">{{ number_format((float) $handler['max_ms'], 1) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Context Breakdown --}}
    @if(!empty($report['contextBreakdown']))
        <div class="bg-surface border border-border rounded-xl p-5">
            <h2 class="text-sm font-semibold text-text mb-4 flex items-center gap-2">
                <i data-lucide="layers" class="w-4 h-4 text-primary"></i>
                تفصيل حسب السياق
            </h2>
            <div class="space-y-2">
                @foreach($report['contextBreakdown'] as $ctx)
                    <div class="flex items-center justify-between text-sm py-1.5 border-b border-border last:border-0">
                        <span class="text-text font-mono text-xs">{{ $ctx['context'] }}</span>
                        <div class="flex items-center gap-4 text-text-secondary">
                            <span>{{ $ctx['calls'] }} استدعاء</span>
                            <span class="text-text font-medium">{{ number_format((float) $ctx['avg_ms'], 1) }} ms</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-surface border border-border rounded-xl p-10 text-center text-text-tertiary text-sm">
            <i data-lucide="gauge" class="w-8 h-8 mx-auto mb-3 opacity-30"></i>
            لا توجد بيانات أداء بعد
        </div>
    @endif

</div>
