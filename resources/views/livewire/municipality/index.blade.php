<div>
    <x-slot name="title">معلومات البلدية</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">معلومات البلدية</h1>
            <p class="text-sm text-text-tertiary mt-1">إدارة معلومات بلدية إذنا</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

        <a href="{{ route('dashboard.municipality.general-info') }}" class="bg-surface rounded-xl border border-border p-5 hover:shadow-elevated transition-all group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center">
                    <i data-lucide="building-2" class="w-5 h-5 text-primary"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors">المعلومات العامة</h3>
                    <p class="text-[11px] text-text-tertiary">الاسم، الوصف، الرؤية، الرسالة</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[11px] text-text-muted">
                <span>{{ $municipality->name_ar }}</span>
            </div>
        </a>

        <a href="{{ route('dashboard.municipality.contacts') }}" class="bg-surface rounded-xl border border-border p-5 hover:shadow-elevated transition-all group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-info-light flex items-center justify-center">
                    <i data-lucide="phone" class="w-5 h-5 text-info"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors">جهات الاتصال</h3>
                    <p class="text-[11px] text-text-tertiary">هاتف، فاكس، بريد إلكتروني</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[11px] text-text-muted">
                <span>{{ $municipality->contacts_count }} جهة اتصال</span>
            </div>
        </a>

        <a href="{{ route('dashboard.municipality.social') }}" class="bg-surface rounded-xl border border-border p-5 hover:shadow-elevated transition-all group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-info-light flex items-center justify-center">
                    <i data-lucide="share-2" class="w-5 h-5 text-info"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors">منصات التواصل</h3>
                    <p class="text-[11px] text-text-tertiary">فيسبوك، تويتر، إنستغرام</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[11px] text-text-muted">
                <span>{{ $municipality->social_platforms_count }} منصة</span>
            </div>
        </a>

        <a href="{{ route('dashboard.municipality.platforms') }}" class="bg-surface rounded-xl border border-border p-5 hover:shadow-elevated transition-all group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-warning-light flex items-center justify-center">
                    <i data-lucide="globe" class="w-5 h-5 text-warning"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors">المنصات الخارجية</h3>
                    <p class="text-[11px] text-text-tertiary">بوابات، أنظمة خارجية</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[11px] text-text-muted">
                <span>{{ $municipality->external_platforms_count }} منصة</span>
            </div>
        </a>

        <a href="{{ route('dashboard.municipality.custom-fields') }}" class="bg-surface rounded-xl border border-border p-5 hover:shadow-elevated transition-all group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-surface-secondary flex items-center justify-center">
                    <i data-lucide="list-checks" class="w-5 h-5 text-text-secondary"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors">الحقول المخصصة</h3>
                    <p class="text-[11px] text-text-tertiary">بيانات إضافية مخصصة</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[11px] text-text-muted">
                <span>{{ $municipality->custom_fields_count }} حقل</span>
            </div>
        </a>

        <a href="{{ route('dashboard.municipality.media') }}" class="bg-surface rounded-xl border border-border p-5 hover:shadow-elevated transition-all group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-surface-secondary flex items-center justify-center">
                    <i data-lucide="image" class="w-5 h-5 text-text-secondary"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors">الوسائط</h3>
                    <p class="text-[11px] text-text-tertiary">شعار، صور، ملفات</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[11px] text-text-muted">
                <span>{{ $municipality->media_count }} مرفق</span>
            </div>
        </a>

        <a href="{{ route('dashboard.municipality.business-hours') }}" class="bg-surface rounded-xl border border-border p-5 hover:shadow-elevated transition-all group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-success-light flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-success"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors">ساعات العمل</h3>
                    <p class="text-[11px] text-text-tertiary">أيام ومواعيد العمل</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[11px] text-text-muted">
                <span>{{ $municipality->business_hours_count }} يوم</span>
            </div>
        </a>

        <a href="{{ route('dashboard.municipality.emergency-contacts') }}" class="bg-surface rounded-xl border border-border p-5 hover:shadow-elevated transition-all group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-danger-light flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-danger"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors">جهات اتصال الطوارئ</h3>
                    <p class="text-[11px] text-text-tertiary">أرقام طوارئ مهمة</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[11px] text-text-muted">
                <span>{{ $municipality->emergency_contacts_count }} جهة اتصال</span>
            </div>
        </a>

    </div>
</div>
