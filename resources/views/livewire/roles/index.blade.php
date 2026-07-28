<div>
    <x-slot name="title">الأدوار والصلاحيات</x-slot>

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">الأدوار والصلاحيات</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة الأدوار وصلاحيات الوصول في النظام</p>
        </div>
        @can('create roles')
        <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
            <i data-lucide="shield-plus" class="w-4 h-4"></i>
            إضافة دور
        </button>
        @endcan
    </div>

    {{-- Success Message --}}
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

    {{-- Search --}}
    <div class="bg-surface rounded-xl border border-border p-4 mb-6">
        <div class="relative">
            <i data-lucide="search" class="absolute start-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-text-muted"></i>
            <input type="text" wire:model.live="search" placeholder="بحث بالاسم..." class="w-full bg-surface-secondary border border-border rounded-xl px-10 py-2.5 text-sm text-text placeholder-text-muted focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
        </div>
    </div>

    {{-- Roles Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($roles as $role)
            <div class="bg-surface rounded-xl border border-border p-5 hover:shadow-elevated transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center
                            {{ match($role->name) {
                                'Super Admin' => 'bg-danger-light',
                                'Admin' => 'bg-primary-50',
                                'Department Manager' => 'bg-info-light',
                                default => 'bg-surface-secondary',
                            } }}">
                            <i data-lucide="shield" class="w-5 h-5
                                {{ match($role->name) {
                                    'Super Admin' => 'text-danger',
                                    'Admin' => 'text-primary',
                                    'Department Manager' => 'text-info',
                                    default => 'text-text-secondary',
                                } }}"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-text">{{ $role->name }}</h3>
                            <p class="text-[11px] text-text-tertiary">{{ $role->users_count }} مستخدم</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        @can('edit roles')
                        <button wire:click="openEditModal({{ $role->id }})" class="p-1.5 rounded-lg hover:bg-surface-secondary text-text-tertiary hover:text-primary transition-colors" title="تعديل">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </button>
                        @endcan
                        @can('delete roles')
                        @if ($role->name !== 'Super Admin')
                        <button wire:click="confirmDelete({{ $role->id }})" class="p-1.5 rounded-lg hover:bg-danger-light text-text-tertiary hover:text-danger transition-colors" title="حذف">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                        @endif
                        @endcan
                    </div>
                </div>
                <div class="flex flex-wrap gap-1.5">
                    @forelse ($role->permissions->take(6) as $permission)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-surface-secondary text-[10px] font-semibold text-text-secondary">{{ $permission->name }}</span>
                    @empty
                        <span class="text-[11px] text-text-muted">لا توجد صلاحيات</span>
                    @endforelse
                    @if ($role->permissions->count() > 6)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-primary-50 text-[10px] font-semibold text-primary">+{{ $role->permissions->count() - 6 }}</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <div class="w-16 h-16 rounded-2xl bg-surface-secondary flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="shield" class="w-7 h-7 text-text-muted"></i>
                </div>
                <p class="text-sm font-bold text-text">لا توجد أدوار</p>
                <p class="text-xs text-text-tertiary mt-1">لم يتم العثور على أي أدوار.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($roles->hasPages())
    <div class="mt-6">
        <x-ui.pagination :paginator="$roles" />
    </div>
    @endif

    {{-- Create Role Modal --}}
    @if ($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeCreateModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-border">
                <h3 class="text-lg font-bold text-text">إضافة دور جديد</h3>
                <button wire:click="closeCreateModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form wire:submit="createRole" class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">اسم الدور <span class="text-danger">*</span></label>
                    <input type="text" wire:model="name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('name') border-danger @enderror" placeholder="مثال: مشرف المحتوى" />
                    @error('name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-2">الصلاحيات</label>
                    @php $registry = config('permissions', []); @endphp
                    <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                        @foreach ($permissionsByGroup as $module => $perms)
                            @php
                                $moduleDisplay = $module;
                                foreach ($registry as $reg) {
                                    if ($reg['module'] === $module) { $moduleDisplay = $reg['display_name']; break; }
                                }
                            @endphp
                            <div class="border border-border rounded-xl p-3">
                                <p class="text-xs font-bold text-text-secondary mb-2 uppercase">{{ $moduleDisplay }}</p>
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
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">إنشاء الدور</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Edit Role Modal --}}
    @if ($showEditModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeEditModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-border">
                <h3 class="text-lg font-bold text-text">تعديل الدور</h3>
                <button wire:click="closeEditModal" class="p-2 rounded-xl hover:bg-surface-secondary text-text-tertiary transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form wire:submit="updateRole" class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-text mb-1.5">اسم الدور <span class="text-danger">*</span></label>
                    <input type="text" wire:model="name" class="w-full bg-surface-secondary border border-border rounded-xl px-4 py-2.5 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all @error('name') border-danger @enderror" />
                    @error('name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-text mb-2">الصلاحيات</label>
                    @php $registry = config('permissions', []); @endphp
                    <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                        @foreach ($permissionsByGroup as $module => $perms)
                            @php
                                $moduleDisplay = $module;
                                foreach ($registry as $reg) {
                                    if ($reg['module'] === $module) { $moduleDisplay = $reg['display_name']; break; }
                                }
                            @endphp
                            <div class="border border-border rounded-xl p-3">
                                <p class="text-xs font-bold text-text-secondary mb-2 uppercase">{{ $moduleDisplay }}</p>
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

    {{-- Delete Role Modal --}}
    @if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeDeleteModal"></div>
        <div class="relative bg-surface rounded-2xl shadow-xl border border-border w-full max-w-md" x-cloak>
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-danger-light flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-danger"></i>
                </div>
                <h3 class="text-lg font-bold text-text mb-2">حذف الدور</h3>
                <p class="text-sm text-text-tertiary">هل أنت متأكد من حذف هذا الدور؟ سيتم إزالة الصلاحيات من جميع المستخدمين المرتبطين.</p>
            </div>
            <div class="flex items-center justify-center gap-3 px-6 pb-6">
                <button wire:click="closeDeleteModal" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-text-secondary hover:bg-surface-secondary transition-colors">إلغاء</button>
                <button wire:click="deleteRole" class="px-5 py-2.5 rounded-xl bg-danger text-white text-sm font-semibold hover:bg-danger/90 transition-colors">نعم، حذف</button>
            </div>
        </div>
    </div>
    @endif
</div>
