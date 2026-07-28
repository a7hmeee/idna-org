<div>

    {{-- ============================================ --}}
    {{-- 1. PAGE CAROUSEL (inherited from Services) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'services',
        'breadcrumbExtra' => $service->category
            ? [['label' => $service->category->name, 'url' => route('public.services.category', $service->category->slug)]]
            : null,
        'pageTitle' => $service->name,
        'pageSubtitle' => $service->summary ?? null,
        'pageBadge' => $service->category?->name ?? 'خدمة',
        'pageBadgeIcon' => $service->category?->icon ?? 'file-text',
        'compact' => true,
    ])

    {{-- ============================================ --}}
    {{-- 2. TABBED CONTENT + SIDEBAR --}}
    {{-- ============================================ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="margin-top:-30px;position:relative;z-index:20;">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6 lg:gap-8">

            {{-- === MAIN: TABS === --}}
            <div x-data="{ tab: 'overview' }">
                <div style="background:white;border-radius:14px;border:1px solid #F3F4F6;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.04);">

                    {{-- Tab Navigation --}}
                    <div style="display:flex;gap:0;border-bottom:1px solid #F3F4F6;overflow-x:auto;">
                        <button @click="tab='overview'" :class="tab==='overview' ? 'text-[#0F6A3D] border-b-2 border-[#0F6A3D] bg-[#F5FAF7]' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'" style="padding:14px 20px;font-size:12px;font-weight:700;white-space:nowrap;transition:all 0.2s;border:none;cursor:pointer;background:transparent;">
                            <span style="display:inline-flex;align-items:center;gap:6px;">
                                <i data-lucide="info" style="width:14px;height:14px;"></i>
                                <span>نبذة عن الخدمة</span>
                            </span>
                        </button>
                        <button @click="tab='requirements'" :class="tab==='requirements' ? 'text-[#0F6A3D] border-b-2 border-[#0F6A3D] bg-[#F5FAF7]' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'" style="padding:14px 20px;font-size:12px;font-weight:700;white-space:nowrap;transition:all 0.2s;border:none;cursor:pointer;background:transparent;">
                            <span style="display:inline-flex;align-items:center;gap:6px;">
                                <i data-lucide="clipboard-list" style="width:14px;height:14px;"></i>
                                <span>المتطلبات والوثائق</span>
                            </span>
                        </button>
                        <button @click="tab='steps'" :class="tab==='steps' ? 'text-[#0F6A3D] border-b-2 border-[#0F6A3D] bg-[#F5FAF7]' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'" style="padding:14px 20px;font-size:12px;font-weight:700;white-space:nowrap;transition:all 0.2s;border:none;cursor:pointer;background:transparent;">
                            <span style="display:inline-flex;align-items:center;gap:6px;">
                                <i data-lucide="list-ordered" style="width:14px;height:14px;"></i>
                                <span>خطوات التقديم</span>
                            </span>
                        </button>
                        <button @click="tab='fees'" :class="tab==='fees' ? 'text-[#0F6A3D] border-b-2 border-[#0F6A3D] bg-[#F5FAF7]' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'" style="padding:14px 20px;font-size:12px;font-weight:700;white-space:nowrap;transition:all 0.2s;border:none;cursor:pointer;background:transparent;">
                            <span style="display:inline-flex;align-items:center;gap:6px;">
                                <i data-lucide="wallet" style="width:14px;height:14px;"></i>
                                <span>الرسوم</span>
                            </span>
                        </button>
                    </div>

                    {{-- Tab: Overview --}}
                    <div x-show="tab==='overview'" style="padding:28px 24px;">
                        @if ($service->description)
                            <div style="margin-bottom:24px;">
                                <h3 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 10px;">وصف الخدمة</h3>
                                <p style="font-size:13px;color:#6B7280;line-height:1.8;margin:0;">{{ $service->description }}</p>
                            </div>
                        @endif
                        @if ($service->eligibility)
                            <div>
                                <h3 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 10px;">من يستطيع التقديم؟</h3>
                                <p style="font-size:13px;color:#6B7280;line-height:1.8;margin:0;">{{ $service->eligibility }}</p>
                            </div>
                        @endif
                        @if (!$service->description && !$service->eligibility)
                            <div style="text-align:center;padding:40px 20px;">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                    <i data-lucide="file-text" style="width:22px;height:22px;color:#9CA3AF;"></i>
                                </div>
                                <p style="font-size:13px;color:#9CA3AF;margin:0;">لا توجد تفاصيل إضافية لهذه الخدمة</p>
                            </div>
                        @endif
                        @if ($service->requires_login)
                            <div style="margin-top:24px;padding:16px;border-radius:10px;background:#FFFBEB;border:1px solid rgba(251,191,36,0.3);">
                                <div style="display:flex;align-items:flex-start;gap:10px;">
                                    <i data-lucide="alert-triangle" style="width:16px;height:16px;color:#D97706;flex-shrink:0;margin-top:1px;"></i>
                                    <div>
                                        <p style="font-size:12px;font-weight:700;color:#92400E;margin:0 0 4px;">ملاحظة مهمة</p>
                                        <p style="font-size:11px;color:rgba(146,64,14,0.8);line-height:1.6;margin:0;">هذه الخدمة تتطلب تسجيل الدخول إلى البوابة الإلكترونية لتقديم الطلب. يرجى التأكد من امتلاك حساب نشط على البوابة.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Tab: Requirements & Documents --}}
                    <div x-show="tab==='requirements'" style="padding:28px 24px;">
                        @php $hasReqs = !empty($service->requirements); $hasDocs = !empty($service->documents); @endphp
                        @if ($hasReqs || $hasDocs)
                            <x-services.service-requirements :requirements="$service->requirements ?? []" />
                            @if ($hasReqs && $hasDocs)
                                <hr style="border:none;border-top:1px solid #F3F4F6;margin:20px 0;">
                            @endif
                            <x-services.service-documents :documents="$service->documents ?? []" />
                        @else
                            <div style="text-align:center;padding:40px 20px;">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                    <i data-lucide="clipboard-list" style="width:22px;height:22px;color:#9CA3AF;"></i>
                                </div>
                                <p style="font-size:13px;color:#9CA3AF;margin:0;">لا توجد متطلبات أو وثائق لهذه الخدمة</p>
                            </div>
                        @endif
                    </div>

                    {{-- Tab: Steps --}}
                    <div x-show="tab==='steps'" style="padding:28px 24px;">
                        @if (!empty($service->steps))
                            <x-services.service-steps :steps="$service->steps ?? []" />
                        @else
                            <div style="text-align:center;padding:40px 20px;">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                    <i data-lucide="list-ordered" style="width:22px;height:22px;color:#9CA3AF;"></i>
                                </div>
                                <p style="font-size:13px;color:#9CA3AF;margin:0;">لا توجد خطوات متاحة لهذه الخدمة</p>
                            </div>
                        @endif
                    </div>

                    {{-- Tab: Fees --}}
                    <div x-show="tab==='fees'" style="padding:28px 24px;">
                        @if (!empty($service->fees))
                            <x-services.service-fees :fees="$service->fees ?? []" />
                        @else
                            <div style="text-align:center;padding:40px 20px;">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                    <i data-lucide="wallet" style="width:22px;height:22px;color:#9CA3AF;"></i>
                                </div>
                                <p style="font-size:13px;color:#9CA3AF;margin:0;">هذه الخدمة مجانية بالكامل</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- === SIDEBAR === --}}
            <div class="min-w-0">
                <div class="lg:sticky lg:top-28" style="display:flex;flex-direction:column;gap:12px;">

                    @if ($service->portal_url)
                        <button wire:click="goToPortal" style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:14px 20px;border-radius:12px;background:linear-gradient(135deg,#0F6A3D,#2E7D32);color:white;font-size:13px;font-weight:700;border:none;cursor:pointer;transition:all 0.3s;box-shadow:0 4px 16px rgba(15,106,61,0.3);" onmouseover="this.style.boxShadow='0 6px 24px rgba(15,106,61,0.4)'" onmouseout="this.style.boxShadow='0 4px 16px rgba(15,106,61,0.3)'">
                            <i data-lucide="external-link" style="width:15px;height:15px;"></i>
                            <span>التقديم عبر البوابة</span>
                        </button>
                    @endif

                    <div style="background:white;border-radius:12px;border:1px solid #F3F4F6;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                        <p style="font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:0.5px;margin:0 0 14px;">معلومات الخدمة</p>
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="folder" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                </span>
                                <div style="min-width:0;">
                                    <p style="font-size:10px;color:#9CA3AF;margin:0;">التصنيف</p>
                                    <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;">{{ $service->category?->name ?? '—' }}</p>
                                </div>
                            </div>
                            @if ($service->department)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="building-2" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                    </span>
                                    <div style="min-width:0;">
                                        <p style="font-size:10px;color:#9CA3AF;margin:0;">الدائرة المسؤولة</p>
                                        <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;">{{ $service->department->name }}</p>
                                    </div>
                                </div>
                            @endif
                            @if ($service->processing_time)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="clock" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                    </span>
                                    <div style="min-width:0;">
                                        <p style="font-size:10px;color:#9CA3AF;margin:0;">مدة الإنجاز</p>
                                        <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;">{{ $service->processing_time }}</p>
                                    </div>
                                </div>
                            @endif
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="shield" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                </span>
                                <div style="min-width:0;">
                                    <p style="font-size:10px;color:#9CA3AF;margin:0;">حالة الخدمة</p>
                                    <span style="display:inline-flex;align-items:center;gap:3px;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;margin-top:2px;{{ $statusLabel === 'متاحة' ? 'background:#ECFDF5;color:#059669;' : 'background:#FEF2F2;color:#DC2626;' }}">{{ $statusLabel }}</span>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="{{ $service->requires_login ? 'lock' : 'unlock' }}" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                </span>
                                <div style="min-width:0;">
                                    <p style="font-size:10px;color:#9CA3AF;margin:0;">تسجيل دخول</p>
                                    <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;">{{ $service->requires_login ? 'مطلوب' : 'غير مطلوب' }}</p>
                                </div>
                            </div>
                            @if ($service->views_count > 0)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="eye" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                    </span>
                                    <div style="min-width:0;">
                                        <p style="font-size:10px;color:#9CA3AF;margin:0;">المشاهدات</p>
                                        <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;">{{ number_format($service->views_count) }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- 3. RELATED SERVICES --}}
    {{-- ============================================ --}}
    @if ($relatedServices->isNotEmpty())
        <section style="padding:48px 0 32px;margin-top:40px;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div style="text-align:center;margin-bottom:28px;">
                    <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:9999px;font-size:11px;font-weight:700;background:rgba(15,106,61,0.06);color:#0F6A3D;margin-bottom:10px;">خدمات ذات صلة</span>
                    <h2 style="font-size:22px;font-weight:900;color:#1F2937;margin:0;">خدمات قد تهمك</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($relatedServices as $related)
                        <x-services.service-card
                            :service="$related"
                            :route="route('public.services.show', ['category' => $service->category?->slug ?? 'general', 'service' => $related->slug])"
                        />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================ --}}
    {{-- 4. PORTAL CTA --}}
    {{-- ============================================ --}}
    <x-services.portal-cta :portalUrl="$portalUrl ?? null" />

</div>
