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
if (post_password_required($post->ID)) {
    Timber::render('single-password.html.twig', $context);
} else {
    Timber::render(['single-'.$post->ID.'.html.twig', 'single-'.$post->post_type.'.html.twig', 'single.html.twig'], $context);
}
