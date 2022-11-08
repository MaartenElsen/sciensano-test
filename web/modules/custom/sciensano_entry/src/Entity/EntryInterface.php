<?php

namespace Drupal\sciensano_entry\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Entry entities.
 *
 * @ingroup sciensano_entry
 */
interface EntryInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Entry title.
   *
   * @return string
   *   Title of the Entry.
   */
  public function getName();

  /**
   * Sets the Entry title.
   *
   * @param string $name
   *   The Entry title.
   *
   * @return \Drupal\sciensano_entry\Entity\EntryInterface
   *   The called Entry entity.
   */
  public function setName($name);

  /**
   * Gets the Entry creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Entry.
   */
  public function getCreatedTime();

  /**
   * Sets the Entry creation timestamp.
   *
   * @param int $timestamp
   *   The Entry creation timestamp.
   *
   * @return \Drupal\sciensano_entry\Entity\EntryInterface
   *   The called Entry entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Entry revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Entry revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\sciensano_entry\Entity\EntryInterface
   *   The called Entry entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Entry revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Entry revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\sciensano_entry\Entity\EntryInterface
   *   The called Entry entity.
   */
  public function setRevisionUserId($uid);

}
