<?php

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array('templates', 'views');

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

add_filter('timber/context', function ($context) {
  $context['footer_widgets'] = Timber::get_widgets('footer');

  return $context;
});
