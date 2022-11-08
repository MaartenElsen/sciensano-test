<?php

namespace Drupal\sciensano_entry;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\sciensano_entry\Entity\EntryInterface;

/**
 * Defines the storage handler class for Entry entities.
 *
 * This extends the base storage class, adding required special handling for
 * Entry entities.
 *
 * @ingroup sciensano_entry
 */
class EntryStorage extends SqlContentEntityStorage implements EntryStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(EntryInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {hd_entry_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {hd_entry_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(EntryInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {hd_entry_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('hd_entry_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
