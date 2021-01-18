<?php

/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();
$timber_post = Timber::query_post();
$context['post'] = $timber_post;
$files = pk_get_embedded_files_and_folders($timber_post->ID);
$context['hasFiles'] = count($files) > 0;
$context['files'] = $files;
$context['filesUrl'] = get_home_url() . '/wp-json/partiellkorrekt/v1/code/' . $timber_post->slug . '.zip';

if (post_password_required($timber_post->ID)) {
  Timber::render('single-password.twig', $context);
} else {
  Timber::render(array('single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single.twig'), $context);
}
