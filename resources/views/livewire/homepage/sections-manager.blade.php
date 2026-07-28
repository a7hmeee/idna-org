<div>
    <x-slot name="title">أقسام الصفحة الرئيسية</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">أقسام الصفحة الرئيسية</h1>
            <p class="text-sm text-text-tertiary mt-1">إظهار، إخفاء، وترتيب أقسام الصفحة الرئيسية</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-municipal-50/50">
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">القسم</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">الترتيب</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-text-tertiary">حد العناصر</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-text-tertiary">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sections as $section)
                        <tr class="border-b border-border last:border-0 hover:bg-municipal-50/30 transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-text">{{ $section['title'] }}</p>
                                <p class="text-xs text-text-tertiary mt-0.5">{{ $section['key'] }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-text-tertiary">{{ $section['sort_order'] }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if (in_array($section['key'], ['services', 'departments', 'council_members', 'council_decisions', 'engineering_offices', 'latest_news', 'projects', 'announcements']))
                                    <input type="number" wire:change="updateLimit('{{ $section['key'] }}', $event.target.value)" value="{{ $section['items_limit'] ?? 6 }}" min="1" max="50" class="w-20 bg-surface-secondary border border-border rounded-lg px-3 py-1.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                                @else
                                    <span class="text-sm text-text-tertiary">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="toggle('{{ $section['key'] }}')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors @if($section['is_enabled']) bg-success/10 text-success hover:bg-success/20 @else bg-danger/10 text-danger hover:bg-danger/20 @endif">
                                    <i data-lucide="{{ $section['is_enabled'] ? 'eye' : 'eye-off' }}" class="w-3 h-3"></i>
                                    {{ $section['is_enabled'] ? 'مفعل' : 'معطل' }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
