<?php
require_once __DIR__.'/app/bootstrap.php';
use Timber\Timber;

/** @var $timber Timber */
$timber = $container->get('timber');
$templates = ['archive.html.twig', 'index.html.twig'];
$context = Timber::get_context();
$context['title'] = get_query_var('case_category') ?: 'Case Studies';
$context['current_category'] = isset(get_queried_object()->term_id) ? get_queried_object()->term_id : '';
$context['categories'] = Timber::get_terms('case_category');

$context['posts'] = Timber::get_posts(['post_type' => 'case_study']);
$timber::render('archive-case_study.html.twig', $context);
