<?php
require_once __DIR__.'/app/bootstrap.php';
use Timber\Timber;

/** @var $timber Timber */
$timber = $container->get('timber');
$context = Timber::get_context();
$context['posts'] = Timber::get_posts();
$templates = ['archive.html.twig', 'index.html.twig'];
if ( is_home() ) {
    $context['title'] = get_field('header_title') ?: 'Blog';
    array_unshift($templates, 'archive.html.twig');
}
if ( is_front_page() ) {
    array_unshift($templates, 'home.html.twig');
}
$timber::render($templates, $context);
