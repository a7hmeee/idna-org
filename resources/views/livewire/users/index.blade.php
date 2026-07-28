<div>
    <x-slot name="title">المستخدمين</x-slot>

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">المستخدمين</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة حسابات المستخدمين والصلاحيات</p>
        </div>
        @can('create users')
        <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            إضافة مستخدم
        </button>
        @endcan
    </div>

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-xl bg-danger-light border border-danger/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-danger mt-0.5 shrink-0"></i>
            <span class="text-sm text-danger font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-surface rounded-xl border border-border p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i data-lucide="search" class="absolute start-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-text-muted"></i>
                <input type="text" wire:model.live="search" placeholder="بحث بالاسم أو البريد..." class="w-full bg-surface-secondary border border-border rounded-xl px-10 py-2.5 text-sm text-text placeholder-text-muted focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
            </div>
            <select wire:model.live="filterRole" class="bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all sm:w-44">
                <option value="">جميع الأدوار</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterStatus" class="bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all sm:w-36">
                <option value="">الحالة</option>
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-border bg-surface-secondary/50">
                        <th class="text-start px-6 py-4 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">المستخدم</th>
                        <th class="text-start px-6 py-4 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">البريد الإلكتروني</th>
                        <th class="text-start px-6 py-4 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">القسم</th>
                        <th class="text-start px-6 py-4 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">الدور</th>
                        <th class="text-start px-6 py-4 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">آخر دخول</th>
                        <th class="text-start px-6 py-4 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">الحالة</th>
                        <th class="text-start px-6 py-4 text-[11px] font-bold text-text-tertiary uppercase tracking-wider">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($users as $user)
                        <tr class="hover:bg-surface-secondary/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-primary/10 text-primary font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ \Illuminate\Support\Str::words($user->name, 1, '') }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-text">{{ $user->name }}</p>
                                        @if ($user->phone)
                                            <p class="text-[11px] text-text-tertiary">{{ $user->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-text-secondary">{{ $user->email }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-text-secondary">{{ $user->department?->name ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @foreach ($user->getRoleNames() as $roleName)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold
                                        {{ match($roleName) {
                                            'Super Admin' => 'bg-danger-light text-danger',
                                            'Admin' => 'bg-primary-50 text-primary',
                                            'Department Manager' => 'bg-info-light text-info',
                                            default => 'bg-surface-secondary text-text-secondary',
                                        } }}">
                                        {{ $roleName }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-text-tertiary">{{ $user->last_login_at?->diffForHumans() ?? 'لم يسجل دخول بعد' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold
                                    {{ $user->status === 'active' ? 'bg-success-light text-success' : 'bg-warning-light text-warning' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-success' : 'bg-warning' }}"></span>
                                    {{ $user->status === 'active' ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    @can('edit users')
                                    <button wire:click="openEditModal({{ $user->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    @endcan
                                    @can('edit users')
                                    <button wire:click="confirmResetPassword({{ $user->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-warning transition-colors" title="إعادة تعيين كلمة المرور">
                                        <i data-lucide="key-round" class="w-4 h-4"></i>
                                    </button>
                                    @endcan
                                    @can('delete users')
                                    @if ($user->id !== auth()->id())
                                    <button wire:click="confirmDelete({{ $user->id }})" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors" title="حذف">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                    @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-2xl bg-surface-secondary flex items-center justify-center mb-3">
                                        <i data-lucide="users" class="w-7 h-7 text-text-muted"></i>
                                    </div>
                                    <p class="text-sm font-bold text-text">لا يوجد مستخدمين</p>
                                    <p class="text-xs text-text-tertiary mt-1">لم يتم العثور على مستخدمين مطابقين.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
        <div class="px-6 py-4 border-t border-border">
            <x-ui.pagination :paginator="$users" />
        </div>
        @endif
    </div>

    {{-- Create User Modal --}}
    @if ($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeCreateModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-border">
                <h3 class="text-lg font-bold text-text">إضافة مستخدم جديد</h3>
                <button wire:click="closeCreateModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form wire:submit="createUser" class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الاسم الكامل <span class="text-danger">*</span></label>
                    <input type="text" wire:model="name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('name') border-danger @enderror" placeholder="أدخل الاسم الكامل" />
                    @error('name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">البريد الإلكتروني <span class="text-danger">*</span></label>
                    <input type="email" wire:model="email" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('email') border-danger @enderror" placeholder="admin@idhna.ps" />
                    @error('email') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">رقم الهاتف</label>
                    <input type="text" wire:model="phone" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="059-XXX-XXXX" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password" wire:model="password" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('password') border-danger @enderror" placeholder="8 أحرف على الأقل" />
                        @error('password') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password" wire:model="password_confirmation" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="أعد إدخال كلمة المرور" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">القسم</label>
                        <select wire:model="department_id" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="">اختر القسم</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الدور</label>
                        <select wire:model="role" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="">اختر الدور</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r->name }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الحالة <span class="text-danger">*</span></label>
                    <select wire:model="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-2">الصلاحيات المباشرة</label>
                    @php $registry = config('permissions', []); @endphp
                    <div class="space-y-3 max-h-48 overflow-y-auto pr-1 border border-border rounded-xl p-3 bg-surface-secondary/30">
                        @foreach ($permissionsByGroup as $module => $perms)
                            @php
                                $moduleDisplay = $module;
                                foreach ($registry as $reg) {
                                    if ($reg['module'] === $module) { $moduleDisplay = $reg['display_name']; break; }
                                }
                            @endphp
                            <div>
                                <p class="text-xs font-bold text-text-secondary mb-1.5">{{ $moduleDisplay }}</p>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($perms as $perm)
                                        <label class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg bg-surface-secondary hover:bg-primary-50 cursor-pointer transition-colors text-[11px] font-semibold text-text-secondary has-[:checked]:bg-primary has-[:checked]:text-white">
                                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm->name }}" class="sr-only" />
                                            {{ $perm->display_name ?? $perm->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                    <button type="button" wire:click="closeCreateModal" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">إنشاء المستخدم</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Edit User Modal --}}
    @if ($showEditModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeEditModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-border">
                <h3 class="text-lg font-bold text-text">تعديل المستخدم</h3>
                <button wire:click="closeEditModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form wire:submit="updateUser" class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الاسم الكامل <span class="text-danger">*</span></label>
                    <input type="text" wire:model="name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('name') border-danger @enderror" />
                    @error('name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">البريد الإلكتروني <span class="text-danger">*</span></label>
                    <input type="email" wire:model="email" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('email') border-danger @enderror" />
                    @error('email') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">رقم الهاتف</label>
                    <input type="text" wire:model="phone" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">القسم</label>
                        <select wire:model="department_id" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="">اختر القسم</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-text mb-1.5">الدور</label>
                        <select wire:model="role" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                            <option value="">اختر الدور</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r->name }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">الحالة <span class="text-danger">*</span></label>
                    <select wire:model="status" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-2">الصلاحيات المباشرة</label>
                    @php $registry = config('permissions', []); @endphp
                    <div class="space-y-3 max-h-48 overflow-y-auto pr-1 border border-border rounded-xl p-3 bg-surface-secondary/30">
                        @foreach ($permissionsByGroup as $module => $perms)
                            @php
                                $moduleDisplay = $module;
                                foreach ($registry as $reg) {
                                    if ($reg['module'] === $module) { $moduleDisplay = $reg['display_name']; break; }
                                }
                            @endphp
                            <div>
                                <p class="text-xs font-bold text-text-secondary mb-1.5">{{ $moduleDisplay }}</p>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($perms as $perm)
                                        <label class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg bg-surface-secondary hover:bg-primary-50 cursor-pointer transition-colors text-[11px] font-semibold text-text-secondary has-[:checked]:bg-primary has-[:checked]:text-white">
                                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm->name }}" class="sr-only" />
                                            {{ $perm->display_name ?? $perm->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                    <button type="button" wire:click="closeEditModal" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Delete User Modal --}}
    @if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeDeleteModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-md" x-cloak>
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-danger-light flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-danger"></i>
                </div>
                <h3 class="text-lg font-bold text-text mb-2">حذف المستخدم</h3>
                <p class="text-sm text-text-tertiary">هل أنت متأكد من حذف هذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء.</p>
            </div>
            <div class="flex items-center justify-center gap-3 px-6 pb-6">
                <button wire:click="closeDeleteModal" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                <button wire:click="deleteUser" class="px-5 py-2.5 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors">نعم، حذف</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Reset Password Modal --}}
    @if ($showResetPasswordModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeResetPasswordModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-md" x-cloak>
            <div class="flex items-center justify-between p-5 border-b border-border">
                <h3 class="text-lg font-bold text-text">إعادة تعيين كلمة المرور</h3>
                <button wire:click="closeResetPasswordModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form wire:submit="resetPassword" class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">كلمة المرور الجديدة <span class="text-danger">*</span></label>
                    <input type="password" wire:model="newPassword" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('newPassword') border-danger @enderror" placeholder="8 أحرف على الأقل" />
                    @error('newPassword') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                    <input type="password" wire:model="newPasswordConfirmation" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" placeholder="أعد إدخال كلمة المرور" />
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                    <button type="button" wire:click="closeResetPasswordModal" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">إعادة التعيين</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
