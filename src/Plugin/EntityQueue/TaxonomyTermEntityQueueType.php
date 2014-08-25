<?php
/**
 * @file
 * Contains \Drupal\entityqueue\Plugin\EntityQueue\TaxonomyTermEntityQueueType.
 */

namespace Drupal\entityqueue\Plugin\EntityQueue;

use Drupal\entityqueue\EntityQueueTypeBase;

/**
 * Class TaxonomyTermEntityQueueType
 * @package Drupal\entityqueue\Plugin\EntityQueue
 *
 * Implements a taxonomyterm entityqueue type.
 *
 * @EntityQueueType(
 *   id = "EntityQueue_taxonomy_term",
 *   title = @Translation("Taxonomy Term"),
 *   entity_type = "taxonomy_term",
 * )
 */
class TaxonomyTermEntityQueueType extends EntityQueueTypeBase {

  /**
   * Return the list of content types.
   *
   * @return Array.
   */
  public function getBundles() {

    $entityManager =  \Drupal::entityManager();

    $options = [];
    foreach ($entityManager->getStorage('taxonomy_vocabulary')->loadMultiple() as $type) {
      if ($entityManager->getAccessControlHandler('taxonomy_term')->createAccess($type->type)) {
        $options[$type->vid] = $type->name;
      }
    }
    return $options;
  }
}
