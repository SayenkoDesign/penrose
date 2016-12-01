<?php
require_once __DIR__.'/app/bootstrap.php';
require_once __DIR__.'/src/functions.php';

// timber stuff
add_filter('timber/context', function($data){
    // logos
    $data['menu'] = new Timber\Menu('primary_menu');

    return $data;
});

// register some acf fields
if(function_exists('acf_add_local_field_group')){
    $parser = new \Symfony\Component\Yaml\Parser();
    // header
    $fields = $parser->parse(file_get_contents(__DIR__.'/app/config/page_builder.yml'));
    acf_add_local_field_group($fields);
}