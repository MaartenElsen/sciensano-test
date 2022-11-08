<?php

/**
 * @file
 * Contains hd_entry.page.inc.
 *
 * Page callback for Entry entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Entry templates.
 *
 * Default template: hd_entry.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_hd_entry(array &$variables) {
  // Fetch Entry Entity Object.
  $hd_entry = $variables['elements']['#hd_entry'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
