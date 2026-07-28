<?php
    $f = $facility;
    $fName = $f['name'] ?? '';
    $fSummary = $f['summary'] ?? '';
    $fCategory = $f['category']['name'] ?? null;
    $fSlug = $f['slug'] ?? '';
    $fImage = $f['cover_image_url'] ?? null;
    $fAddress = $f['address'] ?? null;
    $fWorkingHours = $f['working_hours'] ?? null;
    $fIcon = $resolveIcon($fCategory);
    $fUrl = $fSlug && Route::has('public.facilities.show') ? route('public.facilities.show', $fSlug) : '#';
?>

<a href="<?php echo e($fUrl); ?>" <?php if($fUrl !== '#'): ?> wire:navigate <?php endif; ?>
   class="facility-wide-card" aria-label="<?php echo e($fName); ?>"
   style="display:flex;border-radius:18px;border:1px solid #E3E9E4;background:white;overflow:hidden;text-decoration:none;box-shadow:0 5px 18px rgba(20,50,30,0.05);min-height:190px;min-width:0;">

    
    <div style="flex:0 0 42%;min-width:0;overflow:hidden;position:relative;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fImage): ?>
            <img src="<?php echo e($fImage); ?>"
                 alt="<?php echo e($fName); ?>"
                 style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;"
                 loading="lazy" decoding="async"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
            <div style="display:none;position:absolute;inset:0;background:#EAF5EE;align-items:center;justify-content:center;">
                <i data-lucide="<?php echo e($fIcon); ?>" style="width:28px;height:28px;color:#176B32;opacity:0.4;"></i>
            </div>
        <?php else: ?>
            <div style="position:absolute;inset:0;background:#EAF5EE;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="<?php echo e($fIcon); ?>" style="width:28px;height:28px;color:#176B32;opacity:0.4;"></i>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div style="flex:1;padding:16px 18px;display:flex;flex-direction:column;min-width:0;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fCategory): ?>
            <span style="display:inline-flex;align-items:center;gap:3px;height:22px;padding:0 8px;border-radius:9999px;background:#EAF5EE;color:#176B32;font-size:10px;font-weight:600;align-self:flex-start;white-space:nowrap;">
                <i data-lucide="<?php echo e($fIcon); ?>" style="width:10px;height:10px;"></i>
                <span><?php echo e($fCategory); ?></span>
            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <h3 style="margin:10px 0 0;font-size:clamp(15px,1.3vw,18px);font-weight:700;color:#17243A;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
            <?php echo e($fName); ?>

        </h3>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fSummary): ?>
            <p style="margin:6px 0 0;font-size:12px;line-height:1.6;color:#66756D;overflow:hidden;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;">
                <?php echo e($fSummary); ?>

            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div style="flex:1;min-height:4px;"></div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fAddress || $fWorkingHours): ?>
            <div style="margin-top:8px;display:flex;align-items:center;gap:6px;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fAddress): ?>
                    <span style="display:inline-flex;align-items:center;gap:3px;font-size:11px;color:#94A3B8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        <i data-lucide="map-pin" style="width:11px;height:11px;flex-shrink:0;"></i>
                        <span class="truncate"><?php echo e($fAddress); ?></span>
                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div style="margin-top:10px;display:flex;align-items:center;gap:4px;color:#94A3B8;font-size:12px;font-weight:500;transition:color 200ms;">
            <span>التفاصيل</span>
            <i data-lucide="chevron-left" class="wide-card-arrow" style="width:13px;height:13px;transition:transform 200ms,color 200ms;"></i>
        </div>
    </div>
</a>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/facilities-wide.blade.php ENDPATH**/ ?>