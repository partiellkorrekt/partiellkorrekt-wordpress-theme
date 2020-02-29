<?php

function parcel_assets_path($filename = '', $public = false)
{
  $base = $public ? get_template_directory_uri() : get_stylesheet_directory();
  return $base . '/' . (WP_DEBUG ? 'dev' : 'dist') . '/' . $filename;
}

function parcel_enqueue_resource($name, ...$args)
{
  $jsonFileName = parcel_assets_path('parcel.json');
  if (!is_readable($jsonFileName)) {
    wp_die('I did not find /' . $jsonFileName . ', did you run <b>yarn install</b> && <b>yarn ' . (WP_DEBUG ? 'start' : 'build') . '</b>?');
  }
  $assets = json_decode(file_get_contents($jsonFileName));
  if (!property_exists($assets, $name)) return;
  $uri = parcel_assets_path($assets->$name, true);
  if (substr($uri, -3, 3) === 'css') {
    wp_enqueue_style($name, $uri, ...$args);
  } else {
    wp_enqueue_script($name, $uri, ...$args);
    wp_localize_script($name, $name . '_parcel', ['baseUrl' => parcel_assets_path('', true)]);
  }
}

add_action('wp_enqueue_scripts', function () {
  parcel_enqueue_resource('frontendCSS');
  parcel_enqueue_resource('frontendJS', ['jquery'], null, true);
});

add_action('admin_enqueue_scripts', function () {
  parcel_enqueue_resource('backendCSS');
  parcel_enqueue_resource('backendJS', ['jquery'], null, true);
});
