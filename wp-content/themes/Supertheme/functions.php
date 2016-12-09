<?php
require_once __DIR__.'/app/bootstrap.php';
require_once __DIR__.'/src/functions.php';

// timber stuff
add_filter('timber/context', function($data){
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
    $data['phone_url'] = 'tel:'.preg_replace('/[^0-9]/', '', get_field('phone', 'option'));
    $data['address_1'] = get_field('address_1', 'option');
    $data['address_2'] = get_field('address_2', 'option');
    $data['directions'] = get_field('directions_link', 'option');
    $data['hours'] = get_field('hours', 'option');
    $data['copyright'] = get_field('copyright', 'option');

    return $data;
});

// register some acf fields
if(function_exists('acf_add_local_field_group')){
    $parser = new \Symfony\Component\Yaml\Parser();
    // header
    $fields = $parser->parse(file_get_contents(__DIR__.'/app/config/header.yml'));
    acf_add_local_field_group($fields);
    // page builder
    $fields = $parser->parse(file_get_contents(__DIR__.'/app/config/page_builder.yml'));
    acf_add_local_field_group($fields);
    // case studies
    $fields = $parser->parse(file_get_contents(__DIR__.'/app/config/case_study.yml'));
    acf_add_local_field_group($fields);
}