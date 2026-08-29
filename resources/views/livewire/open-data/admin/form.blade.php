<div>
    <x-slot name="title">{{ $editing ? 'تعديل' : 'إضافة' }} مجموعة بيانات</x-slot>

    <div class="mb-6">
        <a href="{{ route('dashboard.open-data') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-700 inline-flex items-center gap-1">
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            العودة للبيانات المفتوحة
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 max-w-2xl">
        <h2 class="text-lg font-bold text-gray-900 mb-6">{{ $editing ? 'تعديل' : 'إضافة' }} مجموعة بيانات</h2>

        @if (session('success'))
            <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">العنوان</label>
                <input type="text" wire:model="title" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">النوع</label>
                    <select wire:model="type" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm">
                        <option value="datasets">مجموعة بيانات</option>
                        <option value="reports">تقرير</option>
                    </select>
                    @error('type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">التصنيف</label>
                    <input type="text" wire:model="category" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm" placeholder="اختياري">
                    @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">الوصف</label>
                <textarea wire:model="description" rows="4" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm"></textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">الملف</label>
                @if ($editing && $dataset?->file_path && !$removeFile)
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-sm text-gray-600">الملف الحالي: {{ basename($dataset->file_path) }}</span>
                        <button type="button" wire:click="$set('removeFile', true)" class="text-xs text-red-600 hover:underline">إزالة</button>
                    </div>
                @endif
                <input type="file" wire:model="file" class="w-full text-sm">
                @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">الرابط الخارجي</label>
                <input type="url" wire:model="external_url" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm" placeholder="https://...">
                @error('external_url') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">الحالة</label>
                    <select wire:model="status" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm">
                        <option value="draft">مسودة</option>
                        <option value="published">منشور</option>
                        <option value="archived">مؤرشف</option>
                    </select>
                    @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-end pb-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="is_featured" class="rounded border-gray-300">
                        <span class="text-sm font-bold text-gray-700">مميز</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" wire:loading.attr="disabled" class="px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors disabled:opacity-50">
                    <span wire:loading.remove wire:target="save">{{ $editing ? 'تحديث' : 'إضافة' }}</span>
                    <span wire:loading wire:target="save">جاري الحفظ...</span>
                </button>
                @if ($editing)
                    <button type="button" wire:click="delete" wire:confirm="هل أنت متأكد من الحذف？"
                            class="px-6 py-2.5 bg-red-50 text-red-600 rounded-xl text-sm font-bold hover:bg-red-100 transition-colors">
                        حذف
                    </button>
                @endif
                <a href="{{ route('dashboard.open-data') }}" wire:navigate class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
