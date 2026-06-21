<?php
// Generate PWA icons for TodoPeludos
$sizes = [72, 96, 128, 144, 152, 192, 384, 512];
$outputDir = __DIR__ . '/public/icons';

foreach ($sizes as $size) {
    $img = imagecreatetruecolor($size, $size);
    
    // Background color: #0ea5e9 (primary)
    $bg = imagecolorallocate($img, 14, 165, 233);
    imagefill($img, 0, 0, $bg);
    
    // White color for the paw
    $white = imagecolorallocate($img, 255, 255, 255);
    
    // Draw a simplified paw icon
    $cx = $size / 2;
    $cy = $size / 2;
    $scale = $size / 512;
    
    // Main pad (large circle at bottom center)
    imagefilledellipse($img, (int)$cx, (int)($cy + 60 * $scale), (int)(180 * $scale), (int)(140 * $scale), $white);
    
    // Toe pads (4 smaller circles above)
    imagefilledellipse($img, (int)($cx - 80 * $scale), (int)($cy - 60 * $scale), (int)(80 * $scale), (int)(80 * $scale), $white);
    imagefilledellipse($img, (int)($cx - 25 * $scale), (int)($cy - 100 * $scale), (int)(75 * $scale), (int)(75 * $scale), $white);
    imagefilledellipse($img, (int)($cx + 25 * $scale), (int)($cy - 100 * $scale), (int)(75 * $scale), (int)(75 * $scale), $white);
    imagefilledellipse($img, (int)($cx + 80 * $scale), (int)($cy - 60 * $scale), (int)(80 * $scale), (int)(80 * $scale), $white);
    
    $path = $outputDir . '/icon-' . $size . 'x' . $size . '.png';
    imagepng($img, $path);
    imagedestroy($img);
    echo "Created: icon-{$size}x{$size}.png\n";
}

echo "\nAll icons generated!\n";
