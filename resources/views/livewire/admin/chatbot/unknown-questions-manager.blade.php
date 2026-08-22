<div class="p-6 space-y-6" dir="rtl">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                <i data-lucide="help-circle" class="w-5 h-5 text-amber-600"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-text">الأسئلة المجهولة</h1>
                <p class="text-xs text-text-tertiary">أسئلة لم يعرف الروبوت الإجابة عنها</p>
            </div>
        </div>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="flex gap-2 flex-wrap">
        @foreach(['new' => 'جديد', 'reviewed' => 'تمت المراجعة', 'resolved' => 'محلول', 'ignored' => 'تجاهل'] as $key => $label)
            <button wire:click="$set('statusFilter', '{{ $key }}')"
                    class="px-3 py-1.5 text-sm rounded-lg border transition-colors
                        {{ $statusFilter === $key ? 'bg-primary text-white border-primary' : 'border-border bg-surface text-text-secondary hover:border-primary/40' }}">
                {{ $label }}
                @if(isset($counts[$key]))
                    <span class="ml-1 px-1.5 py-0.5 text-xs rounded-full {{ $statusFilter === $key ? 'bg-white/20' : 'bg-surface-hover' }}">
                        {{ $counts[$key] }}
                    </span>
                @endif
            </button>
        @endforeach
    </div>

    {{-- Questions Table --}}
    <div class="bg-surface border border-border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-border bg-surface-hover">
                    <th class="text-right px-4 py-3 text-text-secondary font-medium">السؤال</th>
                    <th class="text-right px-4 py-3 text-text-secondary font-medium w-24">التكرار</th>
                    <th class="text-right px-4 py-3 text-text-secondary font-medium w-32">آخر ظهور</th>
                    <th class="text-right px-4 py-3 text-text-secondary font-medium w-28">النطاق المقترح</th>
                    <th class="px-4 py-3 w-24"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($questions as $question)
                    <tr class="hover:bg-surface-hover transition-colors">
                        <td class="px-4 py-3 text-text">
                            <p class="font-medium">{{ $question->question }}</p>
                            @if($question->admin_notes)
                                <p class="text-xs text-text-tertiary mt-0.5 italic">{{ $question->admin_notes }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                {{ $question->occurrence_count }}×
                            </span>
                        </td>
                        <td class="px-4 py-3 text-text-secondary text-xs">
                            {{ $question->last_seen_at?->diffForHumans() ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-text-secondary text-xs">
                            {{ $question->suggested_domain ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($statusFilter === 'new')
                                <button wire:click="openUpdate({{ $question->id }})"
                                        class="px-2 py-1 text-xs rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                                    مراجعة
                                </button>
                            @endif
                        </td>
                    </tr>

                    {{-- Inline update form --}}
                    @if($updatingId === $question->id)
                        <tr class="bg-primary/5">
                            <td colspan="5" class="px-4 py-4">
                                <div class="flex flex-wrap gap-3 items-start">
                                    <select wire:model="newStatus"
                                            class="text-sm border border-border rounded-lg px-3 py-1.5 bg-surface text-text focus:ring-2 focus:ring-primary/30 outline-none">
                                        <option value="reviewed">تمت المراجعة</option>
                                        <option value="resolved">محلول</option>
                                        <option value="ignored">تجاهل</option>
                                    </select>
                                    <input wire:model="adminNotes"
                                           type="text"
                                           placeholder="ملاحظات (اختياري)"
                                           class="flex-1 text-sm border border-border rounded-lg px-3 py-1.5 bg-surface text-text focus:ring-2 focus:ring-primary/30 outline-none">
                                    <button wire:click="updateStatus"
                                            class="px-3 py-1.5 text-sm bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                        حفظ
                                    </button>
                                    <button wire:click="cancelUpdate"
                                            class="px-3 py-1.5 text-sm border border-border rounded-lg text-text-secondary hover:bg-surface-hover transition-colors">
                                        إلغاء
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-text-tertiary text-sm">
                            لا توجد أسئلة في هذه الفئة
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div>{{ $questions->links() }}</div>

</div>
