<?php

namespace Drupal\sciensano_entry;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Entry entities.
 *
 * @ingroup sciensano_entry
 */
class EntryListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['eid'] = $this->t('Entry ID');
    $header['title'] = $this->t('Title');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\sciensano_entry\Entity\Entry $entity */
    $row['eid'] = $entity->id();
    $row['title'] = Link::createFromRoute(
      $entity->label(),
      'entity.hd_entry.edit_form',
      ['hd_entry' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
