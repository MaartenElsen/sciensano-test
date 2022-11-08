<?php

namespace Drupal\sciensano_entry;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface EntryStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Entry revision IDs for a specific Entry.
   *
   * @param \Drupal\sciensano_entry\Entity\EntryInterface $entity
   *   The Entry entity.
   *
   * @return int[]
   *   Entry revision IDs (in ascending order).
   */
  public function revisionIds(EntryInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Entry author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Entry revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\sciensano_entry\Entity\EntryInterface $entity
   *   The Entry entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(EntryInterface $entity);

  /**
   * Unsets the language for all Entry with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
