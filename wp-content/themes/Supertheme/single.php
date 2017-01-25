<?php
require_once __DIR__.'/app/bootstrap.php';
use Timber\Timber;
use Timber\Post;

/** @var $timber Timber */
$timber = $container->get('timber');
$context = Timber::get_context();
$post = new Post();
$context['post'] = $post;
$context["acf"] = get_field_objects($context["post"]->ID);

if(get_post_type() == 'case_study') {
    $posts_array = get_posts([
        'numberposts'     => 1,
        'offset'          => 0,
        'orderby'         => 'post_date',
        'order'           => 'ASC',
        'post_type'       => 'case_study',
        'post_status'     => 'publish'
    ]);
    $context['first_post_link'] = get_permalink($posts_array[0]);

    $posts_array = get_posts([
        'numberposts'     => 1,
        'offset'          => 0,
        'orderby'         => 'post_date',
        'order'           => 'DESC',
        'post_type'       => 'case_study',
        'post_status'     => 'publish'
    ]);
    $context['last_post_link'] = get_permalink($posts_array[0]);
}

if (post_password_required($post->ID)) {
    Timber::render('single-password.html.twig', $context);
} else {
    Timber::render(['single-'.$post->ID.'.html.twig', 'single-'.$post->post_type.'.html.twig', 'single.html.twig'], $context);
}
