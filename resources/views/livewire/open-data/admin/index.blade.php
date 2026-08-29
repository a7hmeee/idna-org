<div>
    <x-slot name="title">البيانات المفتوحة</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">البيانات المفتوحة</h2>
            <p class="text-sm text-gray-500 mt-1">إدارة مجموعات البيانات والتقارير</p>
        </div>
        <a href="{{ route('dashboard.open-data.create') }}" wire:navigate class="px-4 py-2 bg-green-700 text-white rounded-xl text-sm font-bold hover:bg-green-800 transition-colors inline-flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            إضافة مجموعة بيانات
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center gap-3 flex-wrap">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث..."
                   class="px-4 py-2 border border-gray-200 rounded-xl text-sm flex-1 min-w-[200px]">
            <select wire:model.live="status" class="px-4 py-2 border border-gray-200 rounded-xl text-sm">
                <option value="">جميع الحالات</option>
                <option value="draft">مسودة</option>
                <option value="published">منشور</option>
                <option value="archived">مؤرشف</option>
            </select>
        </div>

        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-right">
                    <th class="px-4 py-3 text-xs font-bold text-gray-500">العنوان</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500">النوع</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500">التصنيف</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500">الحالة</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500">مميز</th>
                    <th class="px-4 py-3 text-xs font-bold text-gray-500">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($datasets as $dataset)
                    <tr class="border-t border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $dataset->title }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $dataset->type->label() }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $dataset->category ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{
                                $dataset->status->value === 'published' ? 'bg-green-50 text-green-700' :
                                ($dataset->status->value === 'draft' ? 'bg-yellow-50 text-yellow-700' : 'bg-gray-50 text-gray-500')
                            }}">
                                {{ $dataset->status->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $dataset->is_featured ? '✅' : '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('dashboard.open-data.edit', $dataset) }}" wire:navigate
                                   class="px-3 py-1.5 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    تعديل
                                </a>
                                <button wire:click="delete({{ $dataset->id }})"
                                        wire:confirm="هل أنت متأكد من الحذف؟"
                                        class="px-3 py-1.5 text-xs font-bold text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    حذف
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-400">
                            لا توجد بيانات مفتوحة بعد
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($datasets->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $datasets->links() }}
            </div>
        @endif
    </div>
</div>
