<?php
require_once __DIR__.'/app/bootstrap.php';
use Timber\Timber;

/** @var $timber Timber */
$timber = $container->get('timber');
$templates = ['archive.html.twig', 'index.html.twig'];
$context = Timber::get_context();
$context['title'] = 'Archive';
if (is_day()) {
    $context['title'] = 'Archive: '.get_the_date('D M Y');
}
if (is_month()) {
    $context['title'] = 'Archive: '.get_the_date('M Y');
}
if (is_year()) {
    $context['title'] = 'Archive: '.get_the_date('Y');
}
if (is_tag()) {
    $context['title'] = single_tag_title('', false);
}
if (is_category()) {
    $context['title'] = 'Category: '.single_cat_title('', false);
    array_unshift($templates, 'archive-'.get_query_var('cat').'.html.twig' );
}

if(get_post_type() == "case_study") {
    $context['title'] = get_query_var('case_category') ?: 'Success Stories';
    $context['current_category'] = isset(get_queried_object()->term_id) ? get_queried_object()->term_id : '';
    $context['categories'] = Timber::get_terms('case_category');
}
array_unshift($templates, 'archive-'.get_post_type().'.html.twig' );

$context['posts'] = Timber::get_posts();
$timber::render($templates, $context);
