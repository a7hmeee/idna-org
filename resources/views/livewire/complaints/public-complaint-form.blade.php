<div>
    <div class="max-w-2xl mx-auto py-12 px-4">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-text mb-2">تقديم شكوى</h1>
            <p class="text-text-tertiary">يمكنك تقديم شكوى إلى بلدية إذنا من خلال النموذج أدناه</p>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
                <span class="text-sm text-success font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if ($submitted)
            <div class="bg-surface rounded-2xl border border-border p-8 text-center mb-8">
                <div class="w-16 h-16 rounded-full bg-success/10 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="check-circle" class="w-8 h-8 text-success"></i>
                </div>
                <h2 class="text-xl font-bold text-text mb-2">تم تقديم الشكوى بنجاح</h2>
                <p class="text-text-tertiary mb-4">رقم التتبع الخاص بك:</p>
                <div class="inline-block bg-surface-secondary border border-border rounded-xl px-6 py-3 mb-4">
                    <span class="text-2xl font-mono font-bold text-primary tracking-wider">{{ $trackingNumber }}</span>
                </div>
                <p class="text-sm text-text-tertiary">احتفظ برقم التتبع لمتابعة حالة شكواك</p>
                <div class="mt-6 flex items-center justify-center gap-4">
                    <a href="{{ route('public.complaints.track') }}" class="text-primary font-semibold text-sm hover:underline">
                        متابعة شكوى
                    </a>
                    <button wire:click="$set('submitted', false)" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                        تقديم شكوى أخرى
                    </button>
                </div>
            </div>
        @else
            <form wire:submit="submit" class="space-y-6">
                {{-- Citizen Info --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <h2 class="text-lg font-bold text-text mb-4">معلوماتك</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">الاسم *</label>
                            <input type="text" wire:model="citizenName" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="الاسم الكامل" />
                            @error('citizenName') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">رقم الهاتف *</label>
                            <input type="tel" wire:model="phone" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="059xxxxxxxx" />
                            @error('phone') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">البريد الإلكتروني</label>
                            <input type="email" wire:model="email" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="example@email.com" />
                            @error('email') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Complaint Details --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <h2 class="text-lg font-bold text-text mb-4">تفاصيل الشكوى</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-text mb-1.5">التصنيف *</label>
                            <select wire:model="category" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                                <option value="">اختر التصنيف</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->value }}">{{ $c->label() }}</option>
                                @endforeach
                            </select>
                            @error('category') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-text mb-1.5">الموضوع *</label>
                            <input type="text" wire:model="subject" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="ملخص مختصر للشكوى" />
                            @error('subject') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-text mb-1.5">الوصف *</label>
                            <textarea wire:model="description" rows="5" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="اشرح تفاصيل الشكوى بشكل واضح"></textarea>
                            @error('description') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Location --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <h2 class="text-lg font-bold text-text mb-4">الموقع</h2>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الموقع (اختياري)</label>
                        <input type="text" wire:model="location" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="عنوان أو وصف الموقع" />
                        @error('location') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Attachments --}}
                <div class="bg-surface rounded-xl border border-border p-6">
                    <h2 class="text-lg font-bold text-text mb-4">المرفقات (اختياري)</h2>
                    <div>
                        <input type="file" wire:model="attachments" multiple accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm text-text file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
                        <p class="text-xs text-text-tertiary mt-1.5">يمكن إرفاق صور أو مستندات (الحد الأقصى 5 ملفات، كل ملف حتى 5 ميجابايت)</p>
                        @error('attachments.*') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        @error('attachments') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="px-10 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors" wire:loading.attr="disabled">
                        <span wire:loading.remove><i data-lucide="send" class="w-4 h-4 inline-block ml-2"></i>تقديم الشكوى</span>
                        <span wire:loading>جاري التقديم...</span>
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>