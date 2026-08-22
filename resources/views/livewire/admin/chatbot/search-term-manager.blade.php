<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-text mb-2">إدارة مصطلحات البحث</h1>
        <p class="text-text-tertiary text-sm">إضافة وتعديل مصطلحات البحث المرتبطة بالخدمات البلدية</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Service Selection --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border p-4">
                <h2 class="font-semibold text-text mb-3">الخدمات</h2>
                <div class="space-y-1">
                    @foreach($services as $service)
                        <button
                            wire:click="selectService({{ $service->id }})"
                            class="w-full text-right px-3 py-2 rounded-lg text-sm transition-colors
                                {{ $selectedServiceId === $service->id ? 'bg-primary/10 text-primary font-medium' : 'text-text-secondary hover:bg-gray-50' }}"
                        >
                            {{ $service->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            @if($selectedServiceId)
                {{-- Add/Edit Form --}}
                <div class="bg-white rounded-xl shadow-sm border p-4">
                    <h2 class="font-semibold text-text mb-4">
                        {{ $editingTermId ? 'تعديل مصطلح' : 'إضافة مصطلح جديد' }}
                    </h2>

                    <form wire:submit="save" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-text mb-1">المصطلح</label>
                            <input
                                type="text"
                                wire:model="term"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                placeholder="أدخل المصطلح..."
                            />
                            @error('term') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text mb-1">النوع</label>
                            <select wire:model="type" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                <option value="keyword">كلمة مفتاحية</option>
                                <option value="alias">مرادف</option>
                                <option value="phrase">عبارة بحث</option>
                                <option value="citizen_expression">تعبير مواطن</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-text mb-1">الوزن</label>
                                <input
                                    type="number"
                                    wire:model="weight"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                    min="1" max="100"
                                />
                                @error('weight') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-text mb-1">الأولوية</label>
                                <input
                                    type="number"
                                    wire:model="priority"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                    min="0" max="100"
                                />
                                @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm hover:bg-primary/90">
                                {{ $editingTermId ? 'تحديث' : 'إضافة' }}
                            </button>
                            @if($editingTermId)
                                <button type="button" wire:click="resetForm" class="px-4 py-2 bg-gray-200 text-text rounded-lg text-sm hover:bg-gray-300">
                                    إلغاء
                                </button>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Terms List --}}
                <div class="bg-white rounded-xl shadow-sm border p-4">
                    <h2 class="font-semibold text-text mb-4">المصطلحات الحالية ({{ $terms->count() }})</h2>

                    @if($terms->isEmpty())
                        <p class="text-text-tertiary text-sm py-4 text-center">لا توجد مصطلحات بعد. أضف المصطلح الأول.</p>
                    @else
                        <div class="space-y-2">
                            @foreach($terms as $termItem)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg text-sm">
                                    <div class="flex-1">
                                        <span class="font-medium text-text">{{ $termItem->term }}</span>
                                        <span class="text-text-tertiary mx-2">|</span>
                                        <span class="text-xs text-text-secondary">{{ $termItem->type }}</span>
                                        <span class="text-text-tertiary mx-2">|</span>
                                        <span class="text-xs text-text-secondary">وزن: {{ $termItem->weight }}</span>
                                        <span class="text-text-tertiary mx-2">|</span>
                                        <span class="text-xs text-text-secondary">أولوية: {{ $termItem->priority }}</span>
                                        <div class="text-xs text-text-tertiary mt-1 dir-ltr">{{ $termItem->normalized_term }}</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button
                                            wire:click="toggleActive({{ $termItem->id }})"
                                            class="px-2 py-1 text-xs rounded {{ $termItem->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}"
                                        >
                                            {{ $termItem->is_active ? 'نشط' : 'غير نشط' }}
                                        </button>
                                        <button wire:click="edit({{ $termItem->id }})" class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">تعديل</button>
                                        <button wire:click="delete({{ $termItem->id }})" wire:confirm="Are you sure?" class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">حذف</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Test Search --}}
                <div class="bg-white rounded-xl shadow-sm border p-4">
                    <h2 class="font-semibold text-text mb-4">اختبار البحث</h2>
                    <div class="flex gap-2">
                        <input
                            type="text"
                            wire:model="testQuery"
                            wire:keydown.enter="testSearch"
                            class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm"
                            placeholder="اكتب جملة بحث..."
                        />
                        <button wire:click="testSearch" class="px-4 py-2 bg-primary text-white rounded-lg text-sm hover:bg-primary/90">
                            بحث
                        </button>
                    </div>

                    @if($testResult)
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg text-sm space-y-2">
                            <p><span class="font-medium">النص الطبيعي:</span> {{ $testResult['normalized'] }}</p>
                            <p><span class="font-medium">القرار:</span>
                                <span class="px-2 py-0.5 rounded text-xs {{
                                    $testResult['decision'] === 'AUTO_SELECTED' ? 'bg-green-100 text-green-700' :
                                    ($testResult['decision'] === 'CLARIFICATION' ? 'bg-yellow-100 text-yellow-700' :
                                    ($testResult['decision'] === 'NO_MATCH' ? 'bg-red-100 text-red-700' : 'bg-gray-200'))
                                }}">
                                    {{ $testResult['decision'] }}
                                </span>
                            </p>
                            @if($testResult['best'])
                                <p><span class="font-medium">أفضل تطابق:</span> {{ $testResult['best'] }} ({{ $testResult['best_score'] }})</p>
                            @endif
                            @if(count($testResult['candidates']) > 0)
                                <div class="mt-2">
                                    <p class="font-medium mb-1">المرشحون:</p>
                                    @foreach($testResult['candidates'] as $candidate)
                                        <div class="flex justify-between text-xs text-text-secondary py-1 border-b border-gray-100 last:border-0">
                                            <span>{{ $candidate['name'] }}</span>
                                            <span>{{ $candidate['score'] }} ({{ $candidate['matched_by'] }})</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border p-8 text-center">
                    <p class="text-text-tertiary">اختر خدمة من القائمة لإدارة مصطلحات البحث</p>
                </div>
            @endif
        </div>
    </div>
</div>
