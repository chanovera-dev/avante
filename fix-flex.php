<?php
// Let's test the math.
$ratios = [0.5, 0.8, 1.2, 0.5, 0.8]; // Example ratios.
$C = 1200; // Container width
$H = 300; // Base height
$margin = 4; // Total horizontal margin per item
$gap = 8; // Row gap

$base_sum = 0;
$grow_sum = 0;
foreach ($ratios as $r) {
    $base_sum += $H * $r + $margin;
    $grow_sum += $r * 100;
}
$base_sum += count($ratios) > 1 ? (count($ratios) - 1) * $gap : 0;

$D = $C - $base_sum;

echo "Free space: $D\n";
foreach ($ratios as $i => $r) {
    $w_in = $H * $r + ($D * ($r * 100) / $grow_sum);
    $h_calc = $w_in / $r;
    echo "Item $i (ratio $r): W=$w_in  H=$h_calc\n";
}
