<?php
require_once dirname(__FILE__) . '/../../../../wp-load.php';

$initial_args = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 5,
];
$initial_query = new WP_Query($initial_args);
while ($initial_query->have_posts()) {
    $initial_query->the_post();
    $id = get_the_ID();
    $thumb_id = get_post_thumbnail_id();
    $img_data = wp_get_attachment_image_src($thumb_id, 'large');
    echo "Post $id: Thumb $thumb_id -> W: {$img_data[1]}, H: {$img_data[2]}\n";
}
