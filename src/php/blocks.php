<?php
function pk_get_block_meta($block)
{
  $data = [];
  if ($block['innerHTML']) {
    $doc = new DOMDocument();
    $doc->loadHTML("<?xml encoding=\"utf-8\" ?><!doctype html><html><body>" . $block['innerHTML'] . "</body></html>");
    $content = $doc->documentElement->firstChild->firstChild->nextSibling;
    if ($content) {
      foreach ($content->attributes as $key => $value) {
        if (substr($key, 0, 5) === 'data-') {
          $name = lcfirst(str_replace('-', '', ucwords(substr($key, 5), '-')));
          $data[$name] = $content->getAttribute($key);
        }
      }
    }
  }

  $data = apply_filters('pk_get_block_meta', $data, $block, $doc);
  $data = apply_filters('pk_get_block_meta_' . str_replace('/', '_', $block['blockName']), $data, $block, $doc);

  return $data;
}

add_filter('render_block', function ($block_content, $block) {
  $template = __DIR__ . '/../../templates/blocks/' . $block['blockName'] . '.twig';

  if (!file_exists($template)) {
    return $block_content;
  }

  $data = pk_get_block_meta($block);

  ob_start();
  $result = Timber::render_string(file_get_contents($template), array_merge($block, ['data' => $data]));
  ob_end_clean();

  return $result;
}, 10, 3);
