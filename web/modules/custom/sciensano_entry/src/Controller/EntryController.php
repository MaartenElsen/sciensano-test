<?php

namespace Drupal\sciensano_entry\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\sciensano_entry\Entity\EntryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EntryController.
 *
 *  Returns responses for Entry routes.
 */
class EntryController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Entry revision.
   *
   * @param int $hd_entry_revision
   *   The Entry revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($hd_entry_revision) {
    $hd_entry = $this->entityTypeManager()->getStorage('hd_entry')
      ->loadRevision($hd_entry_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('hd_entry');

    return $view_builder->view($hd_entry);
  }

  /**
   * Page title callback for a Entry revision.
   *
   * @param int $hd_entry_revision
   *   The Entry revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($hd_entry_revision) {
    $hd_entry = $this->entityTypeManager()->getStorage('hd_entry')
      ->loadRevision($hd_entry_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $hd_entry->label(),
      '%date' => $this->dateFormatter->format($hd_entry->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Entry.
   *
   * @param \Drupal\sciensano_entry\Entity\EntryInterface $hd_entry
   *   A Entry object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(EntryInterface $hd_entry) {
    $account = $this->currentUser();
    $hd_entry_storage = $this->entityTypeManager()->getStorage('hd_entry');

    $langcode = $hd_entry->language()->getId();
    $langname = $hd_entry->language()->getName();
    $languages = $hd_entry->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $hd_entry->label()]) : $this->t('Revisions for %title', ['%title' => $hd_entry->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all entry revisions") || $account->hasPermission('administer entry entities')));
    $delete_permission = (($account->hasPermission("delete all entry revisions") || $account->hasPermission('administer entry entities')));

    $rows = [];

    $vids = $hd_entry_storage->revisionIds($hd_entry);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\sciensano_entry\Entity\EntryInterface $revision */
      $revision = $hd_entry_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $hd_entry->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.hd_entry.revision', [
            'hd_entry' => $hd_entry->id(),
            'hd_entry_revision' => $vid,
          ]))->toString();
        }
        else {
          $link = $hd_entry->toLink($date)->toString();
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.hd_entry.translation_revert', [
                'hd_entry' => $hd_entry->id(),
                'hd_entry_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.hd_entry.revision_revert', [
                'hd_entry' => $hd_entry->id(),
                'hd_entry_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.hd_entry.revision_delete', [
                'hd_entry' => $hd_entry->id(),
                'hd_entry_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['hd_entry_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
