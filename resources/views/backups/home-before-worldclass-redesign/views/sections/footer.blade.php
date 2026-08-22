@props([
    'municipality' => null,
    'municipalityName' => '',
    'municipalitySubtitle' => '',
    'logoUrl' => null,
    'contacts' => [],
    'socialPlatforms' => [],
    'portalUrl' => '',
    'sectionKeys' => [],
])

<footer id="footer" style="background:#0B1623;width:100%;max-width:100%;overflow:hidden;" role="contentinfo">
    <div class="container-home py-14 lg:py-16">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
            {{-- Column 1: About --}}
            <div class="sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if ($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $municipalityName }}" class="w-7 h-7 object-contain" loading="lazy">
                        @else
                            <img src="{{ asset('logo.png') }}" alt="{{ $municipalityName }}" class="w-7 h-7 object-contain" style="filter:brightness(0) invert(1);">
                        @endif
                    </div>
                    <div>
                        <p class="font-black text-white text-sm leading-tight">{{ $municipalityName }}</p>
                        <p class="text-[10px] font-medium" style="color:rgba(255,255,255,0.4);">{{ $municipalitySubtitle }}</p>
                    </div>
                </div>
                <p class="text-sm leading-relaxed mb-5" style="color:rgba(255,255,255,0.5);">
                    {{ !empty($municipality['short_description']) ? $municipality['short_description'] : '' }}
                </p>
                @if (!empty($socialPlatforms))
                    <div class="flex items-center gap-2">
                        @foreach ($socialPlatforms as $platform)
                            @php $url = $platform['url'] ?? $platform['platform_url'] ?? null; @endphp
                            @if ($url)
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                   class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200"
                                   style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.4);"
                                   onmouseover="this.style.background='rgba(255,255,255,0.15)';this.style.color='white'"
                                   onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='rgba(255,255,255,0.4)'"
                                   aria-label="{{ $platform['name'] ?? 'تواصل اجتماعي' }}">
                                    <i data-lucide="{{ $platform['icon'] ?? 'globe' }}" class="w-3.5 h-3.5"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Column 2: Quick Links --}}
            <div>
                <h4 class="font-bold text-white text-sm mb-4">روابط سريعة</h4>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('home') }}" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">الرئيسية</a></li>
                    <li><a href="#municipality-intro" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">عن البلدية</a></li>
                    <li><a href="{{ Route::has('public.services.index') ? route('public.services.index') : '#services' }}" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">الخدمات</a></li>
                    <li><a href="{{ Route::has('public.council.index') ? route('public.council.index') : '#council-members' }}" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">المجلس البلدي</a></li>
                    <li><a href="{{ Route::has('public.departments.index') ? route('public.departments.index') : '#departments' }}" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">الأقسام</a></li>
                    <li><a href="#contact" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">اتصل بنا</a></li>
                </ul>
            </div>

            {{-- Column 3: E-Services --}}
            <div>
                <h4 class="font-bold text-white text-sm mb-4">خدمات إلكترونية</h4>
                <ul class="space-y-2.5">
                    @if ($portalUrl)
                        <li><a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">بوابة الخدمات</a></li>
                    @endif
                    @if (Route::has('public.services.index'))
                        <li><a href="{{ route('public.services.index') }}" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">جميع الخدمات</a></li>
                    @endif
                    @if (Route::has('public.water-schedule'))
                        <li><a href="{{ route('public.water-schedule') }}" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">جدول توزيع المياه</a></li>
                    @endif
                    @if (Route::has('public.jobs.index'))
                        <li><a href="{{ route('public.jobs.index') }}" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">الوظائف</a></li>
                    @endif
                    @if (Route::has('public.facilities.index'))
                        <li><a href="{{ route('public.facilities.index') }}" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">المرافق العامة</a></li>
                    @endif
                    @if (Route::has('public.announcements.index'))
                        <li><a href="{{ route('public.announcements.index') }}" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.5);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">الإعلانات</a></li>
                    @endif
                </ul>
            </div>

            {{-- Column 4: Contact --}}
            <div>
                <h4 class="font-bold text-white text-sm mb-4">تواصل معنا</h4>
                <ul class="space-y-2.5">
                    @foreach ($contacts as $contact)
                        <li class="flex items-start gap-2.5">
                            <i data-lucide="{{ $contact['type'] === 'phone' ? 'phone' : ($contact['type'] === 'email' ? 'mail' : ($contact['type'] === 'mobile' ? 'smartphone' : 'map-pin')) }}" class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" style="color:#C8A85A;"></i>
                            <div>
                                @if (!empty($contact['label']))
                                    <p class="text-[10px] font-medium" style="color:rgba(255,255,255,0.3);">{{ $contact['label'] }}</p>
                                @endif
                                @if (!empty($contact['url']))
                                    <a href="{{ $contact['url'] }}" class="text-sm transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.6);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">{{ $contact['value'] }}</a>
                                @else
                                    <span class="text-sm" style="color:rgba(255,255,255,0.6);">{{ $contact['value'] }}</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Newsletter --}}
        <div class="mt-10 pt-8" style="border-top:1px solid rgba(255,255,255,0.06);">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                <div>
                    <h4 class="font-bold text-white text-sm mb-1">اشترك في النشرة البريدية</h4>
                    <p class="text-xs" style="color:rgba(255,255,255,0.4);">احصل على آخر الأخبار والتحديثات</p>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <input type="email" placeholder="بريدك الإلكتروني" class="flex-1 sm:w-56 px-4 py-2.5 rounded-xl text-sm" style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.1);color:white;" aria-label="البريد الإلكتروني">
                    <button class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all cursor-pointer" style="background:#C8A85A;color:#0B1623;" onmouseover="this.style.background='#D4B46A'" onmouseout="this.style.background='#C8A85A'">
                        اشترك
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright Bar --}}
    <div style="border-top:1px solid rgba(255,255,255,0.05);">
        <div class="container-home py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs" style="color:rgba(255,255,255,0.25);">جميع الحقوق محفوظة &copy; {{ date('Y') }} {{ $municipalityName }}</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-xs transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.25);" onmouseover="this.style.color='rgba(255,255,255,0.5)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">سياسة الخصوصية</a>
                    <a href="#" class="text-xs transition-colors duration-200 no-underline" style="color:rgba(255,255,255,0.25);" onmouseover="this.style.color='rgba(255,255,255,0.5)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">شروط الاستخدام</a>
                </div>
            </div>
        </div>
    </div>
</footer>
