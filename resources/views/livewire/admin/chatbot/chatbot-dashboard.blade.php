<div class="p-6 space-y-6" dir="rtl">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                <i data-lucide="bot" class="w-5 h-5 text-primary"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-text">لوحة تحكم المساعد الذكي</h1>
                <p class="text-xs text-text-tertiary">تحليلات ومراقبة الأداء</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-sm text-text-secondary">الفترة:</span>
            <select wire:model.live="period"
                    class="text-sm border border-border rounded-lg px-3 py-1.5 bg-surface text-text focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                <option value="1">آخر يوم</option>
                <option value="7">آخر 7 أيام</option>
                <option value="30">آخر 30 يوم</option>
                <option value="90">آخر 90 يوم</option>
            </select>
            <button wire:click="loadStats"
                    class="p-1.5 rounded-lg hover:bg-surface-hover transition-colors text-text-secondary hover:text-primary">
                <i data-lucide="refresh-cw" class="w-4 h-4" wire:loading.class="animate-spin"></i>
            </button>
        </div>
    </div>

    {{-- KPI Cards Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-surface border border-border rounded-xl p-4">
            <p class="text-xs text-text-tertiary mb-1">إجمالي المحادثات</p>
            <p class="text-2xl font-bold text-text">{{ $conversationStats['totalConversations'] ?? 0 }}</p>
        </div>
        <div class="bg-surface border border-border rounded-xl p-4">
            <p class="text-xs text-text-tertiary mb-1">متوسط وقت الاستجابة</p>
            <p class="text-2xl font-bold text-text">{{ number_format($conversationStats['avgResponseTimeMs'] ?? 0, 0) }} ms</p>
        </div>
        <div class="bg-surface border border-border rounded-xl p-4">
            <p class="text-xs text-text-tertiary mb-1">نسبة الأسئلة المجهولة</p>
            <p class="text-2xl font-bold {{ ($intentDistribution['unknownRate'] ?? 0) > 20 ? 'text-red-500' : 'text-green-500' }}">
                {{ $intentDistribution['unknownRate'] ?? 0 }}%
            </p>
        </div>
        <div class="bg-surface border border-border rounded-xl p-4">
            <p class="text-xs text-text-tertiary mb-1">رضا المستخدمين</p>
            <p class="text-2xl font-bold text-primary">{{ $conversationStats['feedbackPositiveRate'] ?? 0 }}%</p>
        </div>
    </div>

    {{-- Intent Distribution + Top Intents --}}
    <div class="grid md:grid-cols-2 gap-4">
        {{-- Top Intents Table --}}
        <div class="bg-surface border border-border rounded-xl p-5">
            <h2 class="text-sm font-semibold text-text mb-4 flex items-center gap-2">
                <i data-lucide="list-ordered" class="w-4 h-4 text-primary"></i>
                أكثر النوايا تكراراً
            </h2>
            @if(!empty($intentDistribution['topIntents']))
                <div class="space-y-2">
                    @foreach($intentDistribution['topIntents'] as $item)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-text-secondary truncate max-w-[70%]">{{ $item['intent'] ?? $item['final_intent'] ?? '—' }}</span>
                            <span class="font-medium text-text">{{ $item['count'] }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-text-tertiary text-center py-6">لا توجد بيانات بعد</p>
            @endif
        </div>

        {{-- Knowledge Gaps Summary --}}
        <div class="bg-surface border border-border rounded-xl p-5">
            <h2 class="text-sm font-semibold text-text mb-4 flex items-center gap-2">
                <i data-lucide="help-circle" class="w-4 h-4 text-amber-500"></i>
                ثغرات المعرفة
            </h2>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="text-center p-3 rounded-lg bg-surface-hover">
                    <p class="text-xl font-bold text-text">{{ $knowledgeGaps['totalUnknownQuestions'] ?? 0 }}</p>
                    <p class="text-xs text-text-tertiary mt-0.5">إجمالي مجهول</p>
                </div>
                <div class="text-center p-3 rounded-lg bg-amber-50 dark:bg-amber-900/10">
                    <p class="text-xl font-bold text-amber-600">{{ $knowledgeGaps['newUnknownQuestions'] ?? 0 }}</p>
                    <p class="text-xs text-text-tertiary mt-0.5">جديد (لم يُراجع)</p>
                </div>
            </div>
            @if(!empty($knowledgeGaps['topUnknownQuestions']))
                <div class="space-y-1.5">
                    @foreach(array_slice($knowledgeGaps['topUnknownQuestions'], 0, 4) as $q)
                        <div class="flex items-center gap-2 text-xs">
                            <span class="px-1.5 py-0.5 rounded bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 font-mono shrink-0">
                                {{ $q['occurrence_count'] }}×
                            </span>
                            <span class="text-text-secondary truncate">{{ $q['question'] }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Performance --}}
    <div class="bg-surface border border-border rounded-xl p-5">
        <h2 class="text-sm font-semibold text-text mb-4 flex items-center gap-2">
            <i data-lucide="gauge" class="w-4 h-4 text-blue-500"></i>
            مؤشرات الأداء
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-xs text-text-tertiary">متوسط الاستجابة</p>
                <p class="text-lg font-semibold text-text">{{ number_format($performanceStats['avgResponseTimeMs'] ?? 0, 1) }} ms</p>
            </div>
            <div>
                <p class="text-xs text-text-tertiary">P95 الاستجابة</p>
                <p class="text-lg font-semibold text-text">{{ number_format($performanceStats['p95ResponseTimeMs'] ?? 0, 1) }} ms</p>
            </div>
            <div>
                <p class="text-xs text-text-tertiary">طلبات بطيئة</p>
                <p class="text-lg font-semibold {{ ($performanceStats['slowRate'] ?? 0) > 10 ? 'text-red-500' : 'text-text' }}">
                    {{ $performanceStats['slowRequests'] ?? 0 }}
                </p>
            </div>
            <div>
                <p class="text-xs text-text-tertiary">نسبة البطيء</p>
                <p class="text-lg font-semibold {{ ($performanceStats['slowRate'] ?? 0) > 10 ? 'text-red-500' : 'text-green-500' }}">
                    {{ $performanceStats['slowRate'] ?? 0 }}%
                </p>
            </div>
        </div>
    </div>

    {{-- Links to sub-pages --}}
    <div class="grid md:grid-cols-3 gap-3">
        <a href="{{ route('admin.chatbot.unknown-questions') }}"
           class="flex items-center gap-3 p-4 bg-surface border border-border rounded-xl hover:border-primary/40 hover:bg-primary/5 transition-all group">
            <i data-lucide="help-circle" class="w-5 h-5 text-amber-500 group-hover:scale-110 transition-transform"></i>
            <div>
                <p class="text-sm font-medium text-text">الأسئلة المجهولة</p>
                <p class="text-xs text-text-tertiary">مراجعة وتصنيف</p>
            </div>
        </a>
        <a href="{{ route('admin.chatbot.performance') }}"
           class="flex items-center gap-3 p-4 bg-surface border border-border rounded-xl hover:border-primary/40 hover:bg-primary/5 transition-all group">
            <i data-lucide="gauge" class="w-5 h-5 text-blue-500 group-hover:scale-110 transition-transform"></i>
            <div>
                <p class="text-sm font-medium text-text">مراقبة الأداء</p>
                <p class="text-xs text-text-tertiary">تحليل سرعة الاستجابة</p>
            </div>
        </a>
        <a href="{{ route('dashboard.chatbot') }}"
           class="flex items-center gap-3 p-4 bg-surface border border-border rounded-xl hover:border-primary/40 hover:bg-primary/5 transition-all group">
            <i data-lucide="settings" class="w-5 h-5 text-text-secondary group-hover:scale-110 transition-transform"></i>
            <div>
                <p class="text-sm font-medium text-text">إعدادات الروبوت</p>
                <p class="text-xs text-text-tertiary">التدريب والنماذج</p>
            </div>
        </a>
    </div>

</div>
