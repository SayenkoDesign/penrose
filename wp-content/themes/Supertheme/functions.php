<?php
require_once __DIR__ . '/app/bootstrap.php';
require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/merge-tag.php';

// timber stuff
add_filter('timber/context', function ($data) {
    // menu
    $data['menu'] = new Timber\Menu('primary_menu');
    // header background
    $data['default_header_background'] = get_field('default_header_background', 'option');
    // footer
    $data['facebook'] = get_field('facebook', 'option');
    $data['instagram'] = get_field('instagram', 'option');
    $data['pinterest'] = get_field('pinterest', 'option');
    $data['google_plus'] = get_field('google_plus', 'option');
    $data['contact_form_id'] = get_field('contact_form_id', 'option');
    $data['phone'] = get_field('phone', 'option');
    $data['phone_url'] = 'tel:' . preg_replace('/[^0-9]/', '', get_field('phone', 'option'));
    $data['address_1'] = get_field('address_1', 'option');
    $data['address_2'] = get_field('address_2', 'option');
    $data['directions'] = get_field('directions_link', 'option');
    $data['hours'] = get_field('hours', 'option');
    $data['copyright'] = get_field('copyright', 'option');
    // sidebar
    ob_start();
    dynamic_sidebar('sidebar');
    $data['sidebar'] = ob_get_clean();

    return $data;
});

// register some acf fields
if (function_exists('acf_add_local_field_group')) {
    $parser = new \Symfony\Component\Yaml\Parser();
    // header
    $fields = $parser->parse(file_get_contents(__DIR__ . '/app/config/header.yml'));
    acf_add_local_field_group($fields);
    // page builder
    $fields = $parser->parse(file_get_contents(__DIR__ . '/app/config/page_builder.yml'));
    acf_add_local_field_group($fields);
    // case studies
    $fields = $parser->parse(file_get_contents(__DIR__ . '/app/config/case_study.yml'));
    acf_add_local_field_group($fields);
}

register_taxonomy('case_category', 'case_study', [
    'hierarchical' => false,
    'labels' => [
        'name' => _x('Categories', 'taxonomy general name', 'textdomain'),
        'singular_name' => _x('Category', 'taxonomy singular name', 'textdomain'),
    ],
    'show_ui' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
]);

add_filter( 'dynamic_sidebar_params', function (array $params) {

    // $params will ordinarily be an array of 2 elements, we're only interested in the first element
    $widget =& $params[0];
    $widget['before_title'] = '<h4 class="widgettitle">';
    $widget['after_title'] = '</h4>';

    return $params;

}, 20 );

