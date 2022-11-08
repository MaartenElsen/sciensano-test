<?php

namespace Drupal\sciensano_entry\Plugin\Block;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a block to the entry react component.
 *
 * @Block(
 *   id = "entry_react_block",
 *   admin_label = @Translation("Entry react block"),
 * )
 */
class EntryReactBlock extends BlockBase {

  /**
   * {@inheritDoc}
   */
  public function build() {
    return [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attached' => [
        'library' => [
          'sciensano_entry/react',
        ],
      ],
    ];
  }
}
