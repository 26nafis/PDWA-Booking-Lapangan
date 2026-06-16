<?php
$flash = getFlash();
if ($flash):
    $colors = [
        'success' => 'bg-green-50 border-green-400 text-green-800',
        'error'   => 'bg-red-50 border-red-400 text-red-800',
        'info'    => 'bg-blue-50 border-blue-400 text-blue-800',
        'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-800',
    ];
    $icons = [
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
        'error'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
        'info'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
    ];
    $type = $flash['type'];
    $color = $colors[$type] ?? $colors['info'];
    $icon  = $icons[$type]  ?? $icons['info'];
?>
<div class="flex items-center gap-3 px-4 py-3 mb-4 rounded-lg border <?= $color ?>" role="alert">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $icon ?></svg>
    <span class="text-sm font-medium"><?= e($flash['message']) ?></span>
</div>
<?php endif; ?>
