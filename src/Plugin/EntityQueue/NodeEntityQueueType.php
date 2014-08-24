<?php
/**
 * @file
 * Contains \Drupal\entityqueue\Plugin\EntityQueue\NodeEntityQueueType.
 */

namespace Drupal\entityqueue\Plugin\EntityQueue;

use Drupal\entityqueue\EntityQueueTypeBase;

/**
 * Class NodeEntityQueueType
 *
 * Implements a node entityqueue type.
 *
 * @EntityQueueType(
 *   id = "EntityQueue_node",
 *   title = @Translation("Content"),
 *   entity_type = "node",
 * )
 */
class NodeEntityQueueType extends EntityQueueTypeBase {

  /**
   * Return the list of content types.
   *
   * @return Array.
   */
  public function getBundles() {

    $entityManager =  \Drupal::entityManager();

    $options = [];
    foreach ($entityManager->getStorage('node_type')->loadMultiple() as $type) {
      if ($entityManager->getAccessControlHandler('node')->createAccess($type->type)) {
        $options[$type->type] = $type->name;
      }
    }
    return $options;
  }
}
