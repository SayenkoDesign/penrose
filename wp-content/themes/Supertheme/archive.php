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


$max_pages = $GLOBALS['wp_query']->max_num_pages;
$pagination = '';
if ($max_pages > 1) {
    $current_page = get_query_var('paged') ?: 1;
    $pagination .= '<ul class="pagination text-center" role="navigation" aria-label="Pagination">';
    if ($current_page > 1) {
        $pagination .= '<li class="pagination-previous"><a href="' . get_pagenum_link($current_page-1) . '">Previous <span class="show-for-sr">page</span></a></li>';
    }
    for ($page=1; $page <= $max_pages; $page++) {
        $page_link = get_pagenum_link($page);
        $current = $current_page == $page ? 'class="current"' : '';
        $pagination .= '<li><a href="'.$page_link.'" aria-label="Page '.$page.'" '.$current.'>'.$page.'</a></li>';
    }
    if ($current_page < $max_pages) {
        $pagination .= '<li class="pagination-next"><a href="' . get_pagenum_link($current_page+1) . '">Next <span class="show-for-sr">page</span></a></li>';
    }
    $pagination .= '</ul>';
}
$context['pagination'] = $pagination;

$context['posts'] = Timber::get_posts();
$timber::render($templates, $context);
