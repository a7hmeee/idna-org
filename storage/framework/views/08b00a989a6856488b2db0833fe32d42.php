<?php
    $f = $facility;
    $fName = $f['name'] ?? '';
    $fCategory = $f['category']['name'] ?? null;
    $fSlug = $f['slug'] ?? '';
    $fImage = $f['cover_image_url'] ?? null;
    $fAddress = $f['address'] ?? null;
    $fIcon = $resolveIcon($fCategory);
    $fUrl = $fSlug && Route::has('public.facilities.show') ? route('public.facilities.show', $fSlug) : '#';
?>

<a href="<?php echo e($fUrl); ?>" <?php if($fUrl !== '#'): ?> wire:navigate <?php endif; ?>
   class="facility-small-card" aria-label="<?php echo e($fName); ?>"
   style="display:flex;flex-direction:column;border-radius:17px;border:1px solid #E3E9E4;background:white;overflow:hidden;text-decoration:none;box-shadow:0 5px 18px rgba(20,50,30,0.05);min-height:200px;min-width:0;">

    
    <div style="flex:0 0 58%;min-height:0;overflow:hidden;position:relative;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fImage): ?>
            <img src="<?php echo e($fImage); ?>"
                 alt="<?php echo e($fName); ?>"
                 style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;"
                 loading="lazy" decoding="async"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
            <div style="display:none;position:absolute;inset:0;background:#EAF5EE;align-items:center;justify-content:center;">
                <i data-lucide="<?php echo e($fIcon); ?>" style="width:24px;height:24px;color:#176B32;opacity:0.4;"></i>
            </div>
        <?php else: ?>
            <div style="position:absolute;inset:0;background:#EAF5EE;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="<?php echo e($fIcon); ?>" style="width:24px;height:24px;color:#176B32;opacity:0.4;"></i>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div style="flex:1;padding:12px 14px;display:flex;flex-direction:column;min-width:0;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fCategory): ?>
            <span style="display:inline-flex;align-items:center;gap:3px;height:20px;padding:0 7px;border-radius:9999px;background:#EAF5EE;color:#176B32;font-size:9px;font-weight:600;align-self:flex-start;white-space:nowrap;">
                <i data-lucide="<?php echo e($fIcon); ?>" style="width:9px;height:9px;"></i>
                <span><?php echo e($fCategory); ?></span>
            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <h3 style="margin:8px 0 0;font-size:clamp(13px,1.1vw,15px);font-weight:700;color:#17243A;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
            <?php echo e($fName); ?>

        </h3>

        <div style="flex:1;min-height:4px;"></div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fAddress): ?>
            <div style="margin-top:6px;display:flex;align-items:center;gap:3px;">
                <i data-lucide="map-pin" style="width:10px;height:10px;color:#94A3B8;flex-shrink:0;"></i>
                <span style="font-size:10px;color:#94A3B8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo e($fAddress); ?></span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</a>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/facilities-small.blade.php ENDPATH**/ ?>