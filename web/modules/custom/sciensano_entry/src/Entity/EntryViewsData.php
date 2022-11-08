<?php

namespace Drupal\sciensano_entry\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Entry entities.
 */
class EntryViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
