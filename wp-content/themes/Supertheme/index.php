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
}
if ( is_front_page() ) {
    array_unshift($templates, 'home.html.twig');
}
$timber::render($templates, $context);
