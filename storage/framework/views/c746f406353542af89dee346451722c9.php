<?php
    $f = $facility;
    $fName = $f['name'] ?? '';
    $fSummary = $f['summary'] ?? '';
    $fCategory = $f['category']['name'] ?? null;
    $fSlug = $f['slug'] ?? '';
    $fImage = $f['cover_image_url'] ?? null;
    $fAddress = $f['address'] ?? null;
    $fIcon = $resolveIcon($fCategory);
    $fUrl = $fSlug && Route::has('public.facilities.show') ? route('public.facilities.show', $fSlug) : '#';
    $isFeatured = $f['is_featured'] ?? false;
?>

<article class="facility-featured-card" aria-label="<?php echo e($fName); ?>"
         style="position:relative;height:clamp(320px,38vw,450px);width:100%;border-radius:22px;overflow:hidden;box-shadow:0 12px 32px rgba(20,50,30,0.12);">

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fImage): ?>
        <img src="<?php echo e($fImage); ?>"
             alt="<?php echo e($fName); ?>"
             class="w-full h-full"
             style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;"
             loading="lazy" decoding="async"
             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
        <div style="display:none;position:absolute;inset:0;background:#EAF5EE;align-items:center;justify-content:center;">
            <i data-lucide="<?php echo e($fIcon); ?>" style="width:48px;height:48px;color:#176B32;opacity:0.4;"></i>
        </div>
    <?php else: ?>
        <div style="position:absolute;inset:0;background:#EAF5EE;display:flex;align-items:center;justify-content:center;">
            <i data-lucide="<?php echo e($fIcon); ?>" style="width:48px;height:48px;color:#176B32;opacity:0.4;"></i>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(8,20,25,0.82) 0%,rgba(8,20,25,0.35) 50%,rgba(8,20,25,0.05) 100%);pointer-events:none;"></div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isFeatured): ?>
        <span style="position:absolute;top:20px;right:20px;display:inline-flex;align-items:center;gap:4px;height:28px;padding:0 12px;border-radius:9999px;background:rgba(255,255,255,0.15);backdrop-filter:blur(6px);color:white;font-size:10px;font-weight:700;">
            <i data-lucide="star" style="width:11px;height:11px;"></i>
            <span>مرفق مميز</span>
        </span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div style="position:absolute;bottom:0;right:0;left:0;padding:clamp(20px,2.5vw,30px);z-index:2;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fCategory): ?>
            <span style="display:inline-flex;align-items:center;gap:4px;height:26px;padding:0 12px;border-radius:9999px;background:rgba(255,255,255,0.12);backdrop-filter:blur(4px);color:rgba(255,255,255,0.92);font-size:11px;font-weight:600;margin-bottom:12px;">
                <i data-lucide="<?php echo e($fIcon); ?>" style="width:12px;height:12px;"></i>
                <span><?php echo e($fCategory); ?></span>
            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <h3 style="margin:0;font-size:clamp(20px,2.2vw,28px);font-weight:800;color:white;line-height:1.3;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
            <?php echo e($fName); ?>

        </h3>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fSummary): ?>
            <p style="margin:8px 0 0;font-size:clamp(12px,1vw,14px);line-height:1.75;color:rgba(255,255,255,0.85);overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                <?php echo e($fSummary); ?>

            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fAddress): ?>
            <div style="display:flex;align-items:center;gap:5px;margin-top:10px;">
                <i data-lucide="map-pin" style="width:13px;height:13px;color:rgba(255,255,255,0.6);flex-shrink:0;"></i>
                <span style="font-size:12px;color:rgba(255,255,255,0.7);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo e($fAddress); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <a href="<?php echo e($fUrl); ?>" <?php if($fUrl !== '#'): ?> wire:navigate <?php endif; ?>
           style="display:inline-flex;align-items:center;gap:6px;margin-top:16px;height:42px;padding:0 20px;border-radius:10px;background:rgba(255,255,255,0.15);backdrop-filter:blur(8px);color:white;font-size:13px;font-weight:600;text-decoration:none;transition:background 200ms;"
           onmouseover="this.style.background='rgba(23,107,50,0.85)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
            <span>استكشف المرفق</span>
            <i data-lucide="chevron-left" class="featured-action-arrow" style="width:15px;height:15px;transition:transform 200ms;"></i>
        </a>
    </div>
</article>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/facilities-featured.blade.php ENDPATH**/ ?>