<?php
/**
 * @file
 * Contains \Drupal\entityqueue\Plugin\EntityQueue\FileEntityQueueType.
 */

namespace Drupal\entityqueue\Plugin\EntityQueue;

use Drupal\entityqueue\EntityQueueBase;

/**
 * Class FileEntityQueueType
 * @package Drupal\entityqueue\Plugin\EntityQueue
 *
 * Implements a file entityqueue type.
 *
 * @EntityQueueType(
 *   id = "EntityQueue_file",
 *   title = @Translation("File"),
 *   entity_type = "file",
 * )
 */
class FileEntityQueueType extends EntityQueueBase {

}
