<section id="facebook-feed" class="section-py bg-white overflow-hidden">
    <div class="container-home">
        
        <div class="text-center max-w-[640px] mx-auto">
            
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold mb-3" style="background:rgba(15,106,61,0.08);color:#0F6A3D;">
                <i data-lucide="facebook" class="w-3.5 h-3.5"></i>
                <span>آخر أخبار البلدية</span>
            </span>

            
            <h2 class="text-3xl sm:text-4xl lg:text-[34px] font-black text-text leading-tight">
                آخر أخبار البلدية
            </h2>

            
            <p class="text-sm sm:text-base text-text-secondary mt-3 leading-relaxed">
                تابع أحدث أخبار وإعلانات بلدية إذنا عبر صفحتنا الرسمية على فيسبوك
            </p>

            
            <a href="https://www.facebook.com/100064888802457/"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="زيارة صفحة بلدية إذنا على فيسبوك"
               class="inline-flex items-center gap-2 px-5 py-2.5 mt-5 rounded-xl bg-primary text-white text-sm font-bold no-underline transition-all duration-200 hover:bg-primary-dark hover:shadow-lg focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-2">
                <i data-lucide="facebook" class="w-4 h-4"></i>
                <span>زيارة صفحة البلدية</span>
                <i data-lucide="external-link" class="w-3.5 h-3.5 opacity-70"></i>
            </a>
        </div>

        
        <div wire:ignore class="max-w-[500px] w-full mx-auto mt-8">

            
            <div id="fb-skeleton" class="rounded-2xl border border-border/60 shadow-card overflow-hidden">
                <div class="bg-surface-secondary p-5 space-y-4 animate-pulse">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full" style="background:#DDE5DC;"></div>
                        <div class="flex-1 space-y-1.5">
                            <div class="h-2.5 rounded w-1/3" style="background:#DDE5DC;"></div>
                            <div class="h-2 rounded w-1/5" style="background:#DDE5DC;"></div>
                        </div>
                    </div>
                    <div class="rounded-lg" style="height:180px;background:#DDE5DC;"></div>
                    <div class="space-y-2">
                        <div class="h-2.5 rounded w-full" style="background:#DDE5DC;"></div>
                        <div class="h-2.5 rounded w-4/5" style="background:#DDE5DC;"></div>
                        <div class="h-2.5 rounded w-3/5" style="background:#DDE5DC;"></div>
                    </div>
                    <div class="pt-2 border-t border-border/40">
                        <div class="h-2 rounded w-1/4" style="background:#DDE5DC;"></div>
                    </div>
                </div>
            </div>

            
            <div id="fb-fallback" class="rounded-2xl border border-border/60 shadow-card p-8 text-center" style="display:none;background:#FFFFFF;">
                <div class="w-14 h-14 rounded-2xl mx-auto flex items-center justify-center" style="background:rgba(15,106,61,0.08);">
                    <svg class="w-7 h-7" style="color:#0F6A3D;" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </div>
                <p class="text-base font-bold text-text mt-4">تعذر تحميل منشورات فيسبوك حاليًا</p>
                <p class="text-sm text-text-secondary mt-1">قد يكون فيسبوك محجوبًا أو غير متاح في هذه اللحظة</p>
                <a href="https://www.facebook.com/100064888802457/"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-5 py-2.5 mt-4 rounded-xl bg-primary text-white text-sm font-bold no-underline hover:bg-primary-dark transition-colors focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-2">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    <span>مشاهدة الأخبار على فيسبوك</span>
                </a>
            </div>

            
            <div id="fb-root"></div>

            
            <div class="fb-page"
                 data-href="https://www.facebook.com/100064888802457/"
                 data-tabs="timeline"
                 data-width="500"
                 data-height="1100"
                 data-small-header="true"
                 data-adapt-container-width="true"
                 data-hide-cover="true"
                 data-show-facepile="false">
            </div>
        </div>
    </div>

    <?php if (! $__env->hasRenderedOnce('55fd8649-755e-4151-b24f-4a9d5c5a5c0c')): $__env->markAsRenderedOnce('55fd8649-755e-4151-b24f-4a9d5c5a5c0c'); ?>
        <?php $__env->startPush('scripts'); ?>
            <script>
                console.log('[FB Debug] Initializing...');

                window.fbAsyncInit = function() {
                    console.log('[FB Debug] fbAsyncInit fired — SDK ready');
                    var sk = document.getElementById('fb-skeleton');
                    if (sk) { sk.style.display = 'none'; console.log('[FB Debug] Skeleton hidden'); }
                    if (window.FB && typeof FB.XFBML === 'object') {
                        console.log('[FB Debug] Calling FB.XFBML.parse()');
                        FB.XFBML.parse();
                    }
                };

                setTimeout(function() {
                    var sk = document.getElementById('fb-skeleton');
                    if (sk) { sk.style.display = 'none'; console.log('[FB Debug] Skeleton hidden (timeout)'); }

                    var iframe = document.querySelector('.fb-page iframe, .fb-page > iframe');
                    var fallback = document.getElementById('fb-fallback');

                    console.log('[FB Debug] 10s check — iframe in .fb-page:', !!iframe);

                    if (iframe && iframe.getAttribute('src') && iframe.getAttribute('src').indexOf('facebook.com') !== -1) {
                        if (fallback) { fallback.style.display = 'none'; }
                        console.log('[FB Debug] Facebook iframe confirmed — hiding fallback, plugin active');
                    } else {
                        if (fallback) { fallback.style.display = 'block'; }
                        console.log('[FB Debug] No Facebook iframe — showing fallback');
                        console.log('[FB Debug] Possible causes: AdBlock, network block, CSP, or Facebook domain blocked');
                    }
                }, 10000);

                document.querySelector('script[src*="connect.facebook.net"]')?.addEventListener('load', function() {
                    console.log('[FB Debug] SDK script load event fired');
                });
                document.querySelector('script[src*="connect.facebook.net"]')?.addEventListener('error', function() {
                    console.log('[FB Debug] SDK script FAILED to load — connect.facebook.net may be blocked');
                });
            </script>
            <script async defer crossorigin="anonymous"
                    src="https://connect.facebook.net/ar_AR/sdk.js#xfbml=1&version=v25.0">
            </script>
        <?php $__env->stopPush(); ?>
    <?php endif; ?>
</section>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/facebook-feed.blade.php ENDPATH**/ ?>