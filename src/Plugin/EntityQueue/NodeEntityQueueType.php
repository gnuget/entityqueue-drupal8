<?php
/**
 * @file
 * Contains \Drupal\entityqueue\Plugin\EntityQueue\NodeEntityQueueType.
 */

namespace Drupal\entityqueue\Plugin\EntityQueue;

use Drupal\entityqueue\EntityQueueBase;

/**
 * Class NodeEntityQueueType
 * @package Drupal\entityqueue\Plugin\EntityQueue
 *
 * Implements a node entityqueue type.
 *
 * @EntityQueueType(
 *   id = "EntityQueue_node",
 *   title = @Translation("Content"),
 *   entity_type = "node",
 * )
 */
class NodeEntityQueueType extends EntityQueueBase {

}
