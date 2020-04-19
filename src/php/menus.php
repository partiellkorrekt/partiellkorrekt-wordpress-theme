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

add_action('widgets_init', function () {
  register_sidebar([
    'name' => 'Footer',
    'id' => 'footer',
    'description' => 'Widgets to show in the footer',
    'before_widget' => '<div id="%1$s" class="col col-md-3 widget %2$s">',
    'after_widget' => "</div>\n"
  ]);
});
