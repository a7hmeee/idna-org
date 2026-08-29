<div class="min-h-screen bg-white">

    {{-- ============================================================
         SECTION 1 — PREMIUM PAGE HERO
    ============================================================ --}}
    <section class="relative overflow-hidden" style="background:linear-gradient(135deg, #0F4F28 0%, #176B32 40%, #1A7A38 100%);">
        <div class="absolute inset-0 opacity-[0.04]" style="background-image:url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        {{-- Decorative shapes --}}
        <div class="absolute top-0 left-0 w-[300px] h-[300px] rounded-full opacity-10" style="background:radial-gradient(circle, #C8A85A 0%, transparent 70%); transform:translate(-40%,-40%);"></div>
        <div class="absolute bottom-0 right-0 w-[250px] h-[250px] rounded-full opacity-8" style="background:radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); transform:translate(30%,30%);"></div>

        <div class="container-home relative z-10 py-12 md:py-16 lg:py-20">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-6">
                <ol class="flex items-center gap-2 text-sm text-white/60">
                    <li>
                        <a href="{{ route('home') }}" wire:navigate class="hover:text-white/90 transition-colors no-underline text-white/60">الرئيسية</a>
                    </li>
                    <li aria-hidden="true">
                        <svg class="w-3.5 h-3.5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </li>
                    <li aria-current="page" class="text-white font-medium">عن البلدية</li>
                </ol>
            </nav>

            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                {{-- RIGHT: Content --}}
                <div class="order-1 lg:order-1 text-right">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold mb-5" style="background:rgba(200,168,90,0.2);color:#F5E6B8; border:1px solid rgba(200,168,90,0.3);">
                        <i data-lucide="landmark" class="w-3.5 h-3.5"></i>
                        {{ $municipality->name_ar ?? 'بلدية إذنا' }}
                    </span>

                    <h1 class="text-3xl md:text-4xl lg:text-[42px] font-black text-white leading-tight mb-4">
                        عن بلدية إذنا
                    </h1>

                    <p class="text-base md:text-lg text-white/70 leading-relaxed mb-8 max-w-xl">
                        {{ $municipality->short_description ?? 'بلدية إذنا هي الجهة المحلية الرسمية المعنية بتقديم الخدمات البلدية وتنظيم العمل المحلي، والعمل على تطوير المدينة وتحسين جودة الحياة للمواطنين.' }}
                    </p>

                    <a href="#municipality-about" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold no-underline transition-all" style="background:white;color:#176B32;box-shadow:0 4px 20px rgba(0,0,0,0.15);">
                        تعرف على البلدية
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                </div>

                {{-- LEFT: Image --}}
                <div class="order-2 lg:order-2 flex justify-center lg:justify-start">
                    <div class="relative">
                        <div class="absolute -inset-3 rounded-3xl opacity-20" style="background:linear-gradient(135deg, #C8A85A 0%, transparent 60%);"></div>
                        @php
                            $heroImage = null;
                            if (count($images) > 0) {
                                $heroImage = !empty($images[0]['path']) ? asset('storage/' . $images[0]['path']) : ($images[0]['url'] ?? null);
                            }
                        @endphp
                        <div class="relative w-[280px] h-[280px] md:w-[340px] md:h-[340px] lg:w-[380px] lg:h-[380px] rounded-3xl overflow-hidden shadow-2xl border-4 border-white/10">
                            @if ($heroImage)
                                <img src="{{ $heroImage }}" alt="{{ $municipality->name_ar ?? 'بلدية إذنا' }}" class="w-full h-full object-cover" loading="eager">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg, #1A7A38 0%, #0F4F28 100%);">
                                    <div class="text-center">
                                        <i data-lucide="landmark" class="w-16 h-16 text-white/30 mx-auto mb-3"></i>
                                        <p class="text-white/40 text-sm font-semibold">{{ $municipality->name_ar ?? 'بلدية إذنا' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         SECTION 2 — MUNICIPALITY QUICK FACTS
    ============================================================ --}}
    @if ($municipality && ($municipality->foundation_date || $municipality->population || $municipality->area || $municipality->municipality_code))
        <section class="border-b" style="background:#F8FAF9;border-bottom:1px solid #E8EDEA;">
            <div class="container-home py-6 md:py-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                    @if ($municipality->foundation_date)
                        <div class="text-center p-4 md:p-5 rounded-2xl bg-white border border-[#E1E8E2] shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center" style="background:#EAF5EE;">
                                <i data-lucide="calendar-days" class="w-5 h-5 text-primary"></i>
                            </div>
                            <p class="text-2xl md:text-[28px] font-extrabold text-[#13251C] leading-none">{{ $municipality->foundation_date->format('Y') }}</p>
                            <p class="text-[11px] text-[#66756D] mt-1.5 font-medium">سنة التأسيس</p>
                        </div>
                    @endif
                    @if ($municipality->population)
                        <div class="text-center p-4 md:p-5 rounded-2xl bg-white border border-[#E1E8E2] shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center" style="background:#EAF5EE;">
                                <i data-lucide="users" class="w-5 h-5 text-primary"></i>
                            </div>
                            <p class="text-2xl md:text-[28px] font-extrabold text-[#13251C] leading-none">{{ number_format($municipality->population) }}</p>
                            <p class="text-[11px] text-[#66756D] mt-1.5 font-medium">عدد السكان</p>
                        </div>
                    @endif
                    @if ($municipality->area)
                        <div class="text-center p-4 md:p-5 rounded-2xl bg-white border border-[#E1E8E2] shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center" style="background:#EAF5EE;">
                                <i data-lucide="map-pin" class="w-5 h-5 text-primary"></i>
                            </div>
                            <p class="text-2xl md:text-[28px] font-extrabold text-[#13251C] leading-none">{{ number_format($municipality->area, 2) }}</p>
                            <p class="text-[11px] text-[#66756D] mt-1.5 font-medium">المساحة (كم²)</p>
                        </div>
                    @endif
                    @if ($municipality->municipality_code)
                        <div class="text-center p-4 md:p-5 rounded-2xl bg-white border border-[#E1E8E2] shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center" style="background:#EAF5EE;">
                                <i data-lucide="hash" class="w-5 h-5 text-primary"></i>
                            </div>
                            <p class="text-2xl md:text-[28px] font-extrabold text-[#13251C] leading-none">{{ $municipality->municipality_code }}</p>
                            <p class="text-[11px] text-[#66756D] mt-1.5 font-medium">رمز البلدية</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         SECTION 3 — نبذة عن البلدية
    ============================================================ --}}
    <section id="municipality-about" class="bg-white">
        <div class="container-home py-12 md:py-16 lg:py-20">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                {{-- RIGHT: Content --}}
                <div class="order-1 lg:order-1 text-right">
                    <div class="flex items-center gap-3 mb-4 justify-end">
                        <h2 class="text-2xl md:text-3xl font-black text-[#13251C]">نبذة عن البلدية</h2>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#EAF5EE;">
                            <i data-lucide="info" class="w-5 h-5 text-primary"></i>
                        </div>
                    </div>
                    <div class="w-12 h-1 rounded-full mb-6" style="background:#176B32;margin-left:auto;"></div>

                    @if (!empty($municipality->full_description))
                        <div class="text-[14px] md:text-[15px] text-[#66756D] leading-[1.9]">
                            {!! nl2br(e($municipality->full_description)) !!}
                        </div>
                    @else
                        <div class="p-5 rounded-2xl border border-dashed border-[#DCE8DE] bg-[#F8FAF9] text-center">
                            <i data-lucide="file-text" class="w-8 h-8 text-[#94A3B8] mx-auto mb-2"></i>
                            <p class="text-sm text-[#66756D] font-medium">لم يتم إعداد نبذة عن البلدية بعد.</p>
                            <p class="text-xs text-[#94A3B8] mt-1">يمكن للإدارة إضافة هذا المحتوى من لوحة التحكم.</p>
                        </div>
                    @endif
                </div>

                {{-- LEFT: Image --}}
                <div class="order-2 lg:order-2">
                    @php
                        $aboutImage = null;
                        if (count($images) > 1) {
                            $aboutImage = !empty($images[1]['path']) ? asset('storage/' . $images[1]['path']) : ($images[1]['url'] ?? null);
                        } elseif (count($images) > 0) {
                            $aboutImage = !empty($images[0]['path']) ? asset('storage/' . $images[0]['path']) : ($images[0]['url'] ?? null);
                        }
                    @endphp
                    <div class="relative rounded-3xl overflow-hidden shadow-lg" style="aspect-ratio:4/3;">
                        @if ($aboutImage)
                            <img src="{{ $aboutImage }}" alt="نبذة عن {{ $municipality->name_ar ?? 'بلدية إذنا' }}" class="w-full h-full object-cover" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg, #EAF5EE 0%, #C5E2D3 100%);">
                                <div class="text-center">
                                    <i data-lucide="building-2" class="w-14 h-14 text-primary/30 mx-auto mb-3"></i>
                                    <p class="text-primary/50 text-sm font-semibold">صورة البلدية</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         SECTION 4 — الرؤية والرسالة
    ============================================================ --}}
    @if (!empty($municipality->vision) || !empty($municipality->mission))
        <section class="bg-[#F8FAF9]">
            <div class="container-home py-12 md:py-16">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-black text-[#13251C] mb-3">الرؤية والرسالة</h2>
                    <div class="w-12 h-1 rounded-full mx-auto" style="background:#176B32;"></div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    @if (!empty($municipality->vision))
                        <div class="rounded-2xl bg-white p-6 md:p-8 border border-[#DCE8DE] shadow-[0_4px_20px_rgba(20,55,30,0.04)] hover:shadow-[0_8px_30px_rgba(20,55,30,0.08)] transition-shadow">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background:#EAF5EE;">
                                    <i data-lucide="eye" class="w-6 h-6 text-primary"></i>
                                </div>
                                <h3 class="text-lg font-bold text-[#13251C]">رؤيتنا</h3>
                            </div>
                            <p class="text-[14px] md:text-[15px] text-[#66756D] leading-[1.85]">{{ $municipality->vision }}</p>
                        </div>
                    @endif

                    @if (!empty($municipality->mission))
                        <div class="rounded-2xl bg-white p-6 md:p-8 border border-[#DCE8DE] shadow-[0_4px_20px_rgba(20,55,30,0.04)] hover:shadow-[0_8px_30px_rgba(20,55,30,0.08)] transition-shadow">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background:#EAF5EE;">
                                    <i data-lucide="crosshair" class="w-6 h-6 text-primary"></i>
                                </div>
                                <h3 class="text-lg font-bold text-[#13251C]">رسالتنا</h3>
                            </div>
                            <p class="text-[14px] md:text-[15px] text-[#66756D] leading-[1.85]">{{ $municipality->mission }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         SECTION 5 — أهداف البلدية
    ============================================================ --}}
    @if (!empty($municipality->objectives) && is_array($municipality->objectives) && count($municipality->objectives) > 0)
        <section class="bg-white">
            <div class="container-home py-12 md:py-16">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-black text-[#13251C] mb-3">أهدافنا</h2>
                    <div class="w-12 h-1 rounded-full mx-auto" style="background:#176B32;"></div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    @foreach ($municipality->objectives as $index => $objective)
                        <div class="flex items-start gap-3 p-4 md:p-5 rounded-2xl bg-[#F8FAF9] border border-[#E8EDEA] hover:border-[#DCE8DE] hover:shadow-sm transition-all">
                            <span class="w-9 h-9 rounded-xl text-white flex items-center justify-center text-sm font-bold shrink-0 mt-0.5" style="background:#176B32;">{{ $index + 1 }}</span>
                            <p class="text-[14px] text-[#4A5A52] leading-[1.8]">{{ $objective }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         SECTION 6 — تاريخ البلدية / المحطات الرئيسية
    ============================================================ --}}
    @if (!empty($municipality->foundation_date))
        <section class="bg-[#F8FAF9]">
            <div class="container-home py-12 md:py-16">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-black text-[#13251C] mb-3">محطات من تاريخ البلدية</h2>
                    <div class="w-12 h-1 rounded-full mx-auto" style="background:#176B32;"></div>
                </div>

                <div class="max-w-3xl mx-auto">
                    <div class="relative">
                        {{-- Timeline line --}}
                        <div class="absolute top-0 bottom-0 right-[18px] w-[2px] bg-[#DCE8DE]"></div>

                        {{-- Milestone: Founding --}}
                        <div class="relative flex gap-4 mb-8 last:mb-0">
                            <div class="relative z-10 w-9 h-9 rounded-full flex items-center justify-center shrink-0 border-[3px] border-white" style="background:#176B32;box-shadow:0 0 0 2px #DCE8DE;">
                                <i data-lucide="flag" class="w-4 h-4 text-white"></i>
                            </div>
                            <div class="flex-1 pb-2">
                                <span class="inline-block px-2.5 py-0.5 rounded-lg text-xs font-bold mb-1.5" style="background:#EAF5EE;color:#176B32;">{{ $municipality->foundation_date->format('Y') }}</span>
                                <h4 class="text-[15px] font-bold text-[#13251C] mb-1">تأسيس البلدية</h4>
                                <p class="text-[13px] text-[#66756D] leading-relaxed">تأسيس بلدية إذنا كجهة محلية رسمية لخدمة المواطنين.</p>
                            </div>
                        </div>

                        @if (!empty($customFields))
                            @foreach ($customFields as $field)
                                @if (!empty($field['key']) && !empty($field['value']))
                                    <div class="relative flex gap-4 mb-8 last:mb-0">
                                        <div class="relative z-10 w-9 h-9 rounded-full flex items-center justify-center shrink-0 border-[3px] border-white" style="background:#C8A85A;box-shadow:0 0 0 2px #DCE8DE;">
                                            <i data-lucide="star" class="w-4 h-4 text-white"></i>
                                        </div>
                                        <div class="flex-1 pb-2">
                                            <h4 class="text-[15px] font-bold text-[#13251C] mb-1">{{ $field['key'] }}</h4>
                                            <p class="text-[13px] text-[#66756D] leading-relaxed">{{ $field['value'] }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         SECTION 7 — خدمات البلدية
    ============================================================ --}}
    @if ($serviceCategories->isNotEmpty())
        <section class="bg-white">
            <div class="container-home py-12 md:py-16">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-black text-[#13251C] mb-3">ماذا نقدم للمواطن؟</h2>
                    <div class="w-12 h-1 rounded-full mx-auto" style="background:#176B32;"></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($serviceCategories as $category)
                        @if (Route::has('public.services.category'))
                            <a href="{{ route('public.services.category', $category->slug) }}" wire:navigate class="group p-5 rounded-2xl border border-[#E8EDEA] bg-white hover:border-[#DCE8DE] hover:shadow-[0_8px_24px_rgba(23,107,50,0.08)] transition-all no-underline text-center">
                                <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center group-hover:scale-110 transition-transform" style="background:#EAF5EE;">
                                    <i data-lucide="{{ $category->icon ?? 'layers' }}" class="w-6 h-6 text-primary"></i>
                                </div>
                                <h3 class="text-sm font-bold text-[#13251C] mb-1">{{ $category->name }}</h3>
                            </a>
                        @endif
                    @endforeach
                </div>

                @if (Route::has('public.services.index'))
                    <div class="text-center mt-8">
                        <a href="{{ route('public.services.index') }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold no-underline transition-all border" style="border-color:#176B32;color:#176B32;">
                            عرض جميع الخدمات
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- ============================================================
         SECTION 8 — إنجازات ومشاريع البلدية
    ============================================================ --}}
    @if ($latestProjects->isNotEmpty())
        <section class="bg-[#F8FAF9]">
            <div class="container-home py-12 md:py-16">
                <div class="flex items-center justify-between mb-10">
                    <div class="text-right">
                        <h2 class="text-2xl md:text-3xl font-black text-[#13251C] mb-2">مشاريع وإنجازات</h2>
                        <div class="w-12 h-1 rounded-full" style="background:#176B32;"></div>
                    </div>
                    @if (Route::has('public.projects.index'))
                        <a href="{{ route('public.projects.index') }}" wire:navigate class="hidden md:inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold no-underline transition-all border" style="border-color:#176B32;color:#176B32;">
                            عرض الكل
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($latestProjects as $project)
                        @if (Route::has('public.projects.show'))
                            <a href="{{ route('public.projects.show', $project->slug) }}" wire:navigate class="group rounded-2xl bg-white border border-[#E8EDEA] overflow-hidden hover:shadow-[0_8px_24px_rgba(23,107,50,0.08)] transition-all no-underline">
                                <div class="h-[140px] overflow-hidden">
                                    @if ($project->cover_image_path)
                                        <img src="{{ asset('storage/' . $project->cover_image_path) }}" alt="{{ $project->name_ar }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg, #EAF5EE, #C5E2D3);">
                                            <i data-lucide="folder-kanban" class="w-8 h-8 text-primary/30"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="text-sm font-bold text-[#13251C] mb-2 line-clamp-2">{{ $project->name_ar }}</h3>
                                    @if ($project->implementation_percentage > 0)
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="flex-1 h-1.5 rounded-full bg-[#E8EDEA] overflow-hidden">
                                                <div class="h-full rounded-full" style="background:#176B32;width:{{ $project->implementation_percentage }}%;"></div>
                                            </div>
                                            <span class="text-[10px] font-bold text-[#66756D]">{{ $project->implementation_percentage }}%</span>
                                        </div>
                                    @endif
                                    @if ($project->status)
                                        <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold" style="background:#EAF5EE;color:#176B32;">{{ $project->status->value }}</span>
                                    @endif
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>

                @if (Route::has('public.projects.index'))
                    <div class="text-center mt-8 md:hidden">
                        <a href="{{ route('public.projects.index') }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold no-underline transition-all border" style="border-color:#176B32;color:#176B32;">
                            عرض جميع المشاريع
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- ============================================================
         SECTION 9 — أعضاء المجلس البلدي
    ============================================================ --}}
    @if (count($councilMembers) > 0)
        <section class="bg-white">
            <div class="container-home py-12 md:py-16">

                {{-- Section header --}}
                <div class="text-center mb-10">
                    <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background:#F0F7F2;border:1px solid #D4E8DA;">
                        <i data-lucide="users" class="w-7 h-7 text-[#176B32]"></i>
                    </div>
                    <div class="flex items-center justify-center gap-4 mb-4">
                        <span class="w-12 h-0.5 rounded-full" style="background:linear-gradient(90deg,transparent,#C8A85A);"></span>
                        <span class="w-2 h-2 rounded-full" style="background:#C8A85A;"></span>
                        <span class="w-12 h-0.5 rounded-full" style="background:linear-gradient(270deg,transparent,#C8A85A);"></span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-black text-[#17243A] mb-3">أعضاء المجلس البلدي</h2>
                    <p class="text-sm md:text-base text-[#6B7A8D] max-w-lg mx-auto leading-relaxed">نعمل معًا من أجل خدمة أهلنا وتطوير مدينتنا</p>
                </div>

                {{-- Carousel container --}}
                <div x-data="{
                    currentIndex: 0,
                    get cardWidth() { return window.innerWidth >= 1024 ? 330 : window.innerWidth >= 640 ? 300 : 280; },
                    get gap() { return 20; },
                    get visibleCount() { return window.innerWidth >= 1024 ? 3 : window.innerWidth >= 640 ? 2 : 1; },
                    get maxIndex() { return Math.max(0, {{ count($councilMembers) }} - this.visibleCount); },
                    scrollTo(idx) {
                        this.currentIndex = Math.max(0, Math.min(idx, this.maxIndex));
                        const track = this.$refs.track;
                        if (!track) return;
                        const isRtl = document.documentElement.dir === 'rtl';
                        const offset = this.currentIndex * (this.cardWidth + this.gap);
                        track.scrollTo({ left: isRtl ? offset : offset, behavior: 'smooth' });
                    },
                    prev() { this.scrollTo(this.currentIndex - 1); },
                    next() { this.scrollTo(this.currentIndex + 1); }
                }" class="relative">

                    {{-- Navigation arrows --}}
                    <button x-show="currentIndex < maxIndex" x-transition.opacity
                            @click="next()"
                            class="absolute top-1/2 -translate-y-1/2 left-0 md:-left-5 z-20 w-11 h-11 rounded-full bg-white border border-[#E2E8F0] shadow-md flex items-center justify-center hover:bg-[#F8FAFC] hover:border-[#176B32]/30 transition-all cursor-pointer"
                            aria-label="التالي">
                        <i data-lucide="chevron-left" class="w-5 h-5 text-[#176B32]" stroke-width="2.5"></i>
                    </button>
                    <button x-show="currentIndex > 0" x-transition.opacity
                            @click="prev()"
                            class="absolute top-1/2 -translate-y-1/2 right-0 md:-right-5 z-20 w-11 h-11 rounded-full bg-white border border-[#E2E8F0] shadow-md flex items-center justify-center hover:bg-[#F8FAFC] hover:border-[#176B32]/30 transition-all cursor-pointer"
                            aria-label="السابق">
                        <i data-lucide="chevron-right" class="w-5 h-5 text-[#176B32]" stroke-width="2.5"></i>
                    </button>

                    {{-- Scrollable track --}}
                    <div x-ref="track"
                         class="flex overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2"
                         style="gap:20px;scrollbar-width:none;-ms-overflow-style:none;padding-left:4px;padding-right:4px;"
                         @scroll.throttle.200ms="
                            const isRtl = document.documentElement.dir === 'rtl';
                            const scrollPos = Math.abs($refs.track.scrollLeft);
                            currentIndex = Math.round(scrollPos / (cardWidth + gap));
                         ">
                        <style>. council-carousel-track::-webkit-scrollbar { display:none; }</style>

                        @foreach ($councilMembers as $member)
                            @php
                                $memberName = $member['full_name'] ?? '';
                                $memberSlug = $member['slug'] ?? '';
                                $memberPosition = $member['position'] ?? 'council_member';
                                $memberCommittee = $member['committee'] ?? null;
                                $memberPhotoPath = $member['photo_path'] ?? null;
                                $memberPhone = $member['phone'] ?? $member['mobile'] ?? null;
                                $memberEmail = $member['email'] ?? null;

                                try {
                                    $posLabel = \App\Domains\Municipality\Enums\CouncilMemberPosition::from($memberPosition)->label();
                                } catch (\Throwable $e) {
                                    $posLabel = $memberPosition;
                                }

                                $photoUrl = null;
                                if (!empty($memberPhotoPath)) {
                                    $photoUrl = \Illuminate\Support\Facades\Storage::disk('public')->exists($memberPhotoPath)
                                        ? asset('storage/' . $memberPhotoPath)
                                        : null;
                                }

                                $nameParts = array_filter(explode(' ', $memberName));
                                $initials = '';
                                if (count($nameParts) >= 2) {
                                    $initials = mb_substr($nameParts[0], 0, 1) . ' ' . mb_substr($nameParts[1], 0, 1);
                                } elseif (count($nameParts) === 1) {
                                    $initials = mb_substr($nameParts[0], 0, 2);
                                }

                                $isChairman = $memberPosition === 'mayor';
                                $isDeputy = $memberPosition === 'deputy_mayor';
                                $memberUrl = $memberSlug && Route::has('public.council.show')
                                    ? route('public.council.show', $memberSlug)
                                    : '#';
                            @endphp

                            {{-- Card — fixed width, snap start --}}
                            <div class="snap-start shrink-0" style="width:clamp(260px, calc((100% - 40px) / 3), 330px);">
                                <a href="{{ $memberUrl }}" wire:navigate
                                   class="group flex flex-col items-center text-center rounded-2xl bg-white overflow-hidden transition-all duration-300 no-underline h-full
                                          {{ $isChairman ? 'border-2 border-[#C8A85A]/40 shadow-[0_4px_20px_rgba(200,168,90,0.15)]' : 'border border-[#E8EDF2] shadow-[0_2px_12px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_28px_rgba(23,107,50,0.1)] hover:border-[#176B32]/20' }}">

                                    {{-- Photo area — fixed 3:4 aspect ratio --}}
                                    <div class="w-full overflow-hidden relative" style="aspect-ratio:3/4;background:linear-gradient(135deg,#F0F7F2,#E8F5EC);">
                                        @if ($photoUrl)
                                            <img src="{{ $photoUrl }}" alt="{{ $memberName }}"
                                                 class="w-full h-full object-cover object-[center_20%] transition-transform duration-500 group-hover:scale-105"
                                                 loading="lazy" decoding="async">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="text-4xl font-black select-none" style="color:rgba(23,107,50,0.18);">{{ $initials }}</span>
                                            </div>
                                        @endif

                                        {{-- Chairman crown badge --}}
                                        @if ($isChairman)
                                            <div class="absolute top-3 right-3 px-2.5 py-1 rounded-lg text-[10px] font-bold flex items-center gap-1 shadow-sm" style="background:rgba(200,168,90,0.92);color:white;">
                                                <i data-lucide="crown" class="w-3 h-3"></i>
                                                رئيس المجلس
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Content --}}
                                    <div class="w-full px-4 pt-4 pb-5 flex flex-col items-center">
                                        {{-- Position --}}
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold mb-2.5
                                            {{ $isChairman ? 'text-[#B8942E] bg-[rgba(200,168,90,0.1)]' : ($isDeputy ? 'text-[#176B32] bg-[#EAF5EE]' : 'text-[#6B7A8D] bg-[#F3F4F6]') }}">
                                            {{ $posLabel }}
                                        </span>

                                        {{-- Name --}}
                                        <h3 class="text-[15px] font-extrabold text-[#17243A] leading-snug line-clamp-2 mb-1.5">{{ $memberName }}</h3>

                                        {{-- Committee --}}
                                        @if ($memberCommittee)
                                            <p class="text-[12px] text-[#6B7A8D] leading-relaxed line-clamp-1 mb-3">{{ $memberCommittee }}</p>
                                        @else
                                            <div class="mb-3"></div>
                                        @endif

                                        {{-- Contact actions --}}
                                        <div class="flex items-center justify-center gap-2 mt-auto">
                                            @if ($memberPhone)
                                                <a href="tel:{{ $memberPhone }}"
                                                   class="w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-200 bg-[#F0F7F2] text-[#176B32] hover:bg-[#176B32] hover:text-white"
                                                   aria-label="اتصال بـ {{ $memberName }}">
                                                    <i data-lucide="phone" class="w-4 h-4" stroke-width="2"></i>
                                                </a>
                                            @endif
                                            @if ($memberEmail)
                                                <a href="mailto:{{ $memberEmail }}"
                                                   class="w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-200 bg-[#F0F7F2] text-[#176B32] hover:bg-[#176B32] hover:text-white"
                                                   aria-label="بريد {{ $memberName }}">
                                                    <i data-lucide="mail" class="w-4 h-4" stroke-width="2"></i>
                                                </a>
                                            @endif
                                            @if ($memberUrl !== '#')
                                                <a href="{{ $memberUrl }}" wire:navigate
                                                   class="w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-200 bg-[#F0F7F2] text-[#176B32] hover:bg-[#176B32] hover:text-white"
                                                   aria-label="الملف الشخصي لـ {{ $memberName }}">
                                                    <i data-lucide="user" class="w-4 h-4" stroke-width="2"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination dots --}}
                    <div class="flex items-center justify-center gap-2 mt-6" x-show="maxIndex > 0">
                        <template x-for="i in (maxIndex + 1)" :key="i">
                            <button @click="scrollTo(i - 1)"
                                    :class="{
                                        'bg-[#176B32] w-7 rounded-md': currentIndex === (i - 1),
                                        'bg-[#D1D9E0] w-2 rounded-full': currentIndex !== (i - 1)
                                    }"
                                    class="h-2 transition-all duration-300 cursor-pointer border-0 p-0"
                                    :aria-label="'الانتقال إلى العضو ' + i">
                            </button>
                        </template>
                    </div>
                </div>

                {{-- View all link --}}
                @if (Route::has('public.council.index'))
                    <div class="text-center mt-8">
                        <a href="{{ route('public.council.index') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold no-underline transition-all border border-[#176B32] text-[#176B32] hover:bg-[#176B32] hover:text-white">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            عرض جميع الأعضاء
                        </a>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- ============================================================
         SECTION 10 — معرض صور البلدية
    ============================================================ --}}
    @if (count($images) > 0)
        <section class="bg-[#F8FAF9]">
            <div class="container-home py-12 md:py-16">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-black text-[#13251C] mb-3">من صور بلدية إذنا</h2>
                    <div class="w-12 h-1 rounded-full mx-auto" style="background:#176B32;"></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                    {{-- Featured large image --}}
                    @if (count($images) > 0)
                        <div class="col-span-2 row-span-2 rounded-2xl overflow-hidden shadow-sm min-h-[200px] md:min-h-[300px]">
                            @php $img = $images[0]; @endphp
                            <img src="{{ !empty($img['path']) ? asset('storage/' . $img['path']) : ($img['url'] ?? '') }}" alt="{{ $img['alt'] ?? $img['title'] ?? 'صورة من بلدية إذنا' }}" class="w-full h-full object-cover" loading="lazy">
                        </div>
                    @endif

                    {{-- Smaller images --}}
                    @for ($i = 1; $i < min(count($images), 5); $i++)
                        @php $img = $images[$i]; @endphp
                        <div class="rounded-2xl overflow-hidden shadow-sm min-h-[140px] md:min-h-[145px]">
                            <img src="{{ !empty($img['path']) ? asset('storage/' . $img['path']) : ($img['url'] ?? '') }}" alt="{{ $img['alt'] ?? $img['title'] ?? 'صورة من بلدية إذنا' }}" class="w-full h-full object-cover" loading="lazy">
                        </div>
                    @endfor
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         SECTION 11 — الأخبار والإعلانات
    ============================================================ --}}
    @if ($latestNews->isNotEmpty())
        <section class="bg-white">
            <div class="container-home py-12 md:py-16">
                <div class="flex items-center justify-between mb-10">
                    <div class="text-right">
                        <h2 class="text-2xl md:text-3xl font-black text-[#13251C] mb-2">آخر الأخبار والإعلانات</h2>
                        <div class="w-12 h-1 rounded-full" style="background:#176B32;"></div>
                    </div>
                    @if (Route::has('public.news.index'))
                        <a href="{{ route('public.news.index') }}" wire:navigate class="hidden md:inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold no-underline transition-all border" style="border-color:#176B32;color:#176B32;">
                            عرض الكل
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($latestNews->take(4) as $newsItem)
                        @if (Route::has('public.news.show'))
                            <a href="{{ route('public.news.show', $newsItem->slug) }}" wire:navigate class="group rounded-2xl bg-white border border-[#E8EDEA] overflow-hidden hover:shadow-[0_8px_24px_rgba(23,107,50,0.08)] transition-all no-underline">
                                <div class="h-[120px] overflow-hidden">
                                    @if ($newsItem->cover_image_path)
                                        <img src="{{ asset('storage/' . $newsItem->cover_image_path) }}" alt="{{ $newsItem->title_ar }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg, #EAF5EE, #C5E2D3);">
                                            <i data-lucide="newspaper" class="w-7 h-7 text-primary/30"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    @if ($newsItem->category)
                                        <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold mb-2" style="background:#EAF5EE;color:#176B32;">{{ $newsItem->category->value }}</span>
                                    @endif
                                    <h3 class="text-sm font-bold text-[#13251C] mb-2 line-clamp-2">{{ $newsItem->title_ar }}</h3>
                                    @if ($newsItem->publish_at)
                                        <p class="text-[10px] text-[#94A3B8]">{{ $newsItem->publish_at->format('Y-m-d') }}</p>
                                    @endif
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         SECTION 12 — معلومات وحقائق إضافية
    ============================================================ --}}
    @if ($municipality)
        <section class="bg-[#F8FAF9]">
            <div class="container-home py-12 md:py-16">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-black text-[#13251C] mb-3">معلومات وحقائق</h2>
                    <div class="w-12 h-1 rounded-full mx-auto" style="background:#176B32;"></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
                    @if ($municipality->latitude && $municipality->longitude)
                        <div class="p-4 md:p-5 rounded-2xl bg-white border border-[#E8EDEA] text-center">
                            <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center" style="background:#EAF5EE;">
                                <i data-lucide="map" class="w-5 h-5 text-primary"></i>
                            </div>
                            <h4 class="text-xs font-bold text-[#13251C] mb-1">الموقع الجغرافي</h4>
                            <p class="text-[11px] text-[#66756D]">{{ $municipality->latitude }}, {{ $municipality->longitude }}</p>
                        </div>
                    @endif

                    @if (!empty($businessHours))
                        <div class="p-4 md:p-5 rounded-2xl bg-white border border-[#E8EDEA] text-center">
                            <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center" style="background:#EAF5EE;">
                                <i data-lucide="clock" class="w-5 h-5 text-primary"></i>
                            </div>
                            <h4 class="text-xs font-bold text-[#13251C] mb-1">أوقات الدوام</h4>
                            @php
                                $openDays = collect($businessHours)->reject(fn($h) => $h['is_closed'] ?? false);
                            @endphp
                            @if ($openDays->isNotEmpty())
                                <p class="text-[11px] text-[#66756D]">{{ $openDays->first()['opening_time'] ?? '' }} - {{ $openDays->first()['closing_time'] ?? '' }}</p>
                            @else
                                <p class="text-[11px] text-[#66756D]">غير محدد</p>
                            @endif
                        </div>
                    @endif

                    @if ($municipality->population)
                        <div class="p-4 md:p-5 rounded-2xl bg-white border border-[#E8EDEA] text-center">
                            <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center" style="background:#EAF5EE;">
                                <i data-lucide="users" class="w-5 h-5 text-primary"></i>
                            </div>
                            <h4 class="text-xs font-bold text-[#13251C] mb-1">عدد السكان</h4>
                            <p class="text-[11px] text-[#66756D]">{{ number_format($municipality->population) }} نسمة</p>
                        </div>
                    @endif

                    @if ($municipality->area)
                        <div class="p-4 md:p-5 rounded-2xl bg-white border border-[#E8EDEA] text-center">
                            <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center" style="background:#EAF5EE;">
                                <i data-lucide="maximize" class="w-5 h-5 text-primary"></i>
                            </div>
                            <h4 class="text-xs font-bold text-[#13251C] mb-1">المساحة</h4>
                            <p class="text-[11px] text-[#66756D]">{{ number_format($municipality->area, 2) }} كم²</p>
                        </div>
                    @endif

                    @if ($municipality->foundation_date)
                        <div class="p-4 md:p-5 rounded-2xl bg-white border border-[#E8EDEA] text-center">
                            <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center" style="background:#EAF5EE;">
                                <i data-lucide="calendar" class="w-5 h-5 text-primary"></i>
                            </div>
                            <h4 class="text-xs font-bold text-[#13251C] mb-1">سنة التأسيس</h4>
                            <p class="text-[11px] text-[#66756D]">{{ $municipality->foundation_date->format('Y') }}</p>
                        </div>
                    @endif

                    @if (count($contacts) > 0)
                        <div class="p-4 md:p-5 rounded-2xl bg-white border border-[#E8EDEA] text-center">
                            <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center" style="background:#EAF5EE;">
                                <i data-lucide="phone" class="w-5 h-5 text-primary"></i>
                            </div>
                            <h4 class="text-xs font-bold text-[#13251C] mb-1">معلومات التواصل</h4>
                            @php $phoneContact = collect($contacts)->firstWhere('type', 'phone'); @endphp
                            @if ($phoneContact)
                                <p class="text-[11px] text-[#66756D]">{{ $phoneContact['value'] }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         SECTION 13 — تواصل مع البلدية
    ============================================================ --}}
    <section class="relative overflow-hidden" style="background:linear-gradient(135deg, #0F4F28 0%, #176B32 40%, #1A7A38 100%);">
        <div class="absolute inset-0 opacity-[0.03]" style="background-image:url('data:image/svg+xml,%3Csvg width=&quot;40&quot; height=&quot;40&quot; viewBox=&quot;0 0 40 40&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot; fill-rule=&quot;evenodd&quot;%3E%3Cpath d=&quot;M0 40L40 0H20L0 20M40 40V20L20 40&quot;/%3E%3C/g%3E%3C/svg%3E');"></div>

        <div class="container-home relative z-10 py-12 md:py-16">
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-black text-white mb-3">تواصل مع بلدية إذنا</h2>
                <p class="text-sm text-white/60 max-w-lg mx-auto">نحن هنا لمساعدتك. لا تتردد في التواصل معنا لأي استفسار أو اقتراح.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 max-w-4xl mx-auto">
                @foreach ($contacts as $contact)
                    @if (($contact['type'] ?? '') === 'phone' || ($contact['type'] ?? '') === 'mobile')
                        <a href="tel:{{ $contact['value'] }}" class="flex items-center gap-3 p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10 text-white no-underline hover:bg-white/15 transition-all">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(200,168,90,0.2);">
                                <i data-lucide="phone" class="w-5 h-5" style="color:#C8A85A;"></i>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-white/50 font-medium">{{ $contact['label'] ?? 'الهاتف' }}</p>
                                <p class="text-sm font-bold text-white">{{ $contact['value'] }}</p>
                            </div>
                        </a>
                    @elseif (($contact['type'] ?? '') === 'email')
                        <a href="mailto:{{ $contact['value'] }}" class="flex items-center gap-3 p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10 text-white no-underline hover:bg-white/15 transition-all">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(200,168,90,0.2);">
                                <i data-lucide="mail" class="w-5 h-5" style="color:#C8A85A;"></i>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-white/50 font-medium">{{ $contact['label'] ?? 'البريد الإلكتروني' }}</p>
                                <p class="text-sm font-bold text-white">{{ $contact['value'] }}</p>
                            </div>
                        </a>
                    @else
                        <div class="flex items-center gap-3 p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10 text-white">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(200,168,90,0.2);">
                                <i data-lucide="map-pin" class="w-5 h-5" style="color:#C8A85A;"></i>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-white/50 font-medium">{{ $contact['label'] ?? 'العنوان' }}</p>
                                <p class="text-sm font-bold text-white">{{ $contact['value'] }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if (!empty($businessHours))
                    <div class="flex items-center gap-3 p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10 text-white sm:col-span-2 lg:col-span-1">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(200,168,90,0.2);">
                            <i data-lucide="clock" class="w-5 h-5" style="color:#C8A85A;"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-white/50 font-medium">أوقات الدوام</p>
                            @php $openDays = collect($businessHours)->reject(fn($h) => $h['is_closed'] ?? false); @endphp
                            @if ($openDays->isNotEmpty())
                                <p class="text-sm font-bold text-white">{{ $openDays->first()['opening_time'] ?? '' }} - {{ $openDays->first()['closing_time'] ?? '' }}</p>
                            @else
                                <p class="text-sm font-bold text-white">غير محدد</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Social Links --}}
            @if (count($socialPlatforms) > 0)
                <div class="flex items-center justify-center gap-3 mt-8">
                    @foreach ($socialPlatforms as $platform)
                        @if (!empty($platform['url']))
                            <a href="{{ $platform['url'] }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl flex items-center justify-center transition-all no-underline" style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.6);" aria-label="{{ $platform['name'] ?? 'تواصل اجتماعي' }}">
                                <i data-lucide="{{ $platform['icon'] ?? 'external-link' }}" class="w-4.5 h-4.5"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- CTA Buttons --}}
            <div class="flex flex-wrap items-center justify-center gap-3 mt-8">
                @if (Route::has('public.complaints.submit'))
                    <a href="{{ route('public.complaints.submit') }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold no-underline transition-all" style="background:white;color:#176B32;box-shadow:0 4px 15px rgba(0,0,0,0.1);">
                        <i data-lucide="message-square" class="w-4 h-4"></i>
                        أرسل رسالة
                    </a>
                @endif
            </div>
        </div>
    </section>

</div>
