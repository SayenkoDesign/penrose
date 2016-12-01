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
$timber::render(['page-'.$post->post_name.'.twig', 'page.html.twig'], $context);
