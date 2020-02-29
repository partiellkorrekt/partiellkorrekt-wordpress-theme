<?php
add_action('after_setup_theme', function () {
  register_nav_menus([
    'primary_menu' => __('Primary Menu', 'text_domain'),
    'legal_menu' => __('Legal Menu', 'text_domain'),
  ]);
});

add_filter('timber/context', function ($context) {
  $context['primary_menu'] = new Timber\Menu('primary_menu');
  $context['legal_menu'] = new Timber\Menu('legal_menu');
  // $context['site'] = $this;
  return $context;
});
