<?php
require_once('../../../wp-load.php');
// Simulate post_class for a CPT
// We need to find a post of type 'nsfw' or 'detras-del-espejo'
$nsfw_posts = get_posts(['post_type' => 'nsfw', 'posts_per_page' => 1]);
if (!empty($nsfw_posts)) {
    $post = $nsfw_posts[0];
    setup_postdata($post);
    echo "NSFW classes: " . implode(' ', get_post_class('', $post->ID)) . "\n";
} else {
    echo "No NSFW posts found.\n";
}

$mirror_posts = get_posts(['post_type' => 'detras-del-espejo', 'posts_per_page' => 1]);
if (!empty($mirror_posts)) {
    $post = $mirror_posts[0];
    setup_postdata($post);
    echo "Mirror classes: " . implode(' ', get_post_class('', $post->ID)) . "\n";
} else {
    echo "No Mirror posts found.\n";
}
