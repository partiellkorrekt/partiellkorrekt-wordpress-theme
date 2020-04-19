<?php
add_filter('pk_get_block_meta_core_code', function ($data, $block, $doc) {
  $data['code'] = $doc->textContent;
  return $data;
}, 10, 3);

function pk_get_embedded_files($id)
{
  $post = get_post($id);
  $blocks = parse_blocks($post->post_content);
  ob_start();
  $result = [
    'readme.md' => Timber::render('readme.twig', ['title' => ' ' . get_the_title($post), 'permalink' => get_the_permalink($post)])
  ];
  ob_end_clean();
  foreach ($blocks as $block) {
    if ($block['blockName'] === 'core/code') {
      $data = pk_get_block_meta($block);
      if ($data['filename'] ?? '') {
        if ($result[$data['filename']] ?? '') {
          $result[$data['filename']] .= "\n" . $data['code'];
        } else {
          $result[$data['filename']] = trim($data['code']) . "\n";
        }
      }
    }
  }
  if (count($result) < 2) {
    return [];
  }
  ksort($result);
  return $result;
}

function pk_get_embedded_files_and_folders($id)
{
  $files = pk_get_embedded_files($id);
  $result = [];
  foreach ($files as $filename => $content) {
    $parts = explode('/', $filename);
    $location = &$result;
    while (count($parts) > 1) {
      if ($location[$parts[0]]['type'] ?? '' !== 'folder') {
        $location[$parts[0]] = ['type' => 'folder', 'name' => $parts[0], 'children' => []];
      }
      $location = &$location[$parts[0]]['children'];
      array_splice($parts, 0, 1);
    }
    $location[$parts[0]] = ['type' => 'file', 'name' => $parts[0], 'content' => $content];
  }
  return $result;
}

add_action('rest_api_init', function () {
  register_rest_route('partiellkorrekt/v1', '/code/(?P<slug>[^\/]+)\.zip', array(
    'methods' => 'GET',
    'callback' => function (\WP_REST_Request $request) {
      $slug = $request->get_param('slug');
      $posts = get_posts(['name' => $slug]);
      $files = (count($posts) > 0) ? pk_get_embedded_files($posts[0]) : [];
      if (count($files) === 0) {
        return new WP_Error('not_found', 'File not found', ['status' => 404]);
      }

      $tmpfilename = tempnam('/tmp', 'partiellkorrekt_');
      $zip = new ZipArchive();
      $zip->open($tmpfilename, ZipArchive::CREATE);
      foreach ($files as $name => $content) {
        $zip->addFromString($slug . '/' . $name, $content);
      }
      $zip->close();

      if (file_exists($tmpfilename)) {
        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="' . $slug . '.zip"');
        readfile($tmpfilename);
        unlink($tmpfilename);
        exit();
      }
      return new WP_Error('internal_error', 'Could not create the zipfile', ['status' => 500]);
    }
  ));
});
