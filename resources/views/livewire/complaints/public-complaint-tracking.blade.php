<div>
    <div class="max-w-xl mx-auto py-12 px-4">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-text mb-2">متابعة شكوى</h1>
            <p class="text-text-tertiary">أدخل رقم التتبع للاستعلام عن حالة شكواك</p>
        </div>

        {{-- Search Form --}}
        <div class="bg-surface rounded-2xl border border-border p-6 mb-6">
            <form wire:submit="track" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" wire:model="trackingNumber" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-3 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="أدخل رقم التتبع (مثال: CMP-XXXXXXXXXX)" />
                    @error('trackingNumber') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="px-6 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors shrink-0" wire:loading.attr="disabled">
                    <span wire:loading.remove>بحث</span>
                    <span wire:loading>جاري البحث...</span>
                </button>
            </form>
        </div>

        {{-- Result --}}
        @if ($searched)
            @if ($complaint)
                <div class="bg-surface rounded-2xl border border-border overflow-hidden">
                    {{-- Header --}}
                    <div class="p-6 border-b border-border">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-text">تفاصيل الشكوى</h2>
                            <span class="font-mono text-xs text-primary bg-primary/5 px-3 py-1.5 rounded-lg">{{ $complaint->tracking_number }}</span>
                        </div>

                        {{-- Status Badge --}}
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-sm text-text-tertiary">الحالة:</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                @if($complaint->status->value === 'resolved' || $complaint->status->value === 'closed') bg-success/10 text-success
                                @elseif($complaint->status->value === 'rejected') bg-danger/10 text-danger
                                @elseif($complaint->status->value === 'submitted') bg-info/10 text-info
                                @else bg-warning/10 text-warning @endif">
                                {{ $complaint->status->label() }}
                            </span>
                        </div>
                        <span class="text-xs text-text-tertiary">آخر تحديث: {{ $complaint->updated_at?->format('Y-m-d H:i') }}</span>
                    </div>

                    {{-- Info --}}
                    <div class="p-6 space-y-4">
                        <div>
                            <span class="text-xs font-semibold text-text-tertiary block mb-1">التصنيف</span>
                            <span class="text-sm text-text">{{ $complaint->category->label() }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-text-tertiary block mb-1">الموضوع</span>
                            <span class="text-sm text-text">{{ $complaint->subject }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-text-tertiary block mb-1">الوصف</span>
                            <p class="text-sm text-text-secondary">{{ $complaint->description }}</p>
                        </div>

                        @if ($complaint->location)
                            <div>
                                <span class="text-xs font-semibold text-text-tertiary block mb-1">الموقع</span>
                                <span class="text-sm text-text">{{ $complaint->location }}</span>
                            </div>
                        @endif

                        @if ($complaint->submitted_at)
                            <div>
                                <span class="text-xs font-semibold text-text-tertiary block mb-1">تاريخ التقديم</span>
                                <span class="text-sm text-text">{{ $complaint->submitted_at->format('Y-m-d H:i') }}</span>
                            </div>
                        @endif

                        @if ($complaint->resolution_at)
                            <div>
                                <span class="text-xs font-semibold text-text-tertiary block mb-1">تاريخ الحل</span>
                                <span class="text-sm text-text">{{ $complaint->resolution_at->format('Y-m-d H:i') }}</span>
                            </div>
                        @endif

                        @if ($complaint->public_response)
                            <div class="bg-success-light border border-success/20 rounded-xl p-4">
                                <span class="text-xs font-semibold text-success block mb-1">الرد</span>
                                <p class="text-sm text-text">{{ $complaint->public_response }}</p>
                            </div>
                        @endif

                        @if ($complaint->attachments && count($complaint->attachments) > 0)
                            <div>
                                <span class="text-xs font-semibold text-text-tertiary block mb-2">المرفقات</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($complaint->attachments_urls as $url)
                                        <a href="{{ $url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-secondary text-xs text-primary font-semibold hover:bg-border transition-colors">
                                            <i data-lucide="file" class="w-3.5 h-3.5"></i>
                                            <span>مرفق</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($complaint->attachments && count($complaint->attachments) > 0)
                            <div class="pt-4 border-t border-border">
                                <span class="text-xs font-semibold text-text-tertiary block mb-2">المرفقات</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($complaint->attachments_urls as $url)
                                        <a href="{{ $url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-secondary text-xs text-primary font-semibold hover:bg-border transition-colors">
                                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                            <span>عرض المرفق</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-surface rounded-2xl border border-border p-8 text-center">
                    <div class="w-14 h-14 rounded-full bg-warning/10 flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="search-x" class="w-6 h-6 text-warning"></i>
                    </div>
                    <h3 class="font-semibold text-text mb-1">لم يتم العثور على الشكوى</h3>
                    <p class="text-sm text-text-tertiary">تأكد من صحة رقم التتبع وحاول مرة أخرى</p>
                </div>
            @endif
        @endif
    </div>
</div>