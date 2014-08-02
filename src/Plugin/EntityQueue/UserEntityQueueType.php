<?php
/**
 * @file
 * Contains \Drupal\entityqueue\Plugin\EntityQueue\UserEntityQueueType.
 */

namespace Drupal\entityqueue\Plugin\EntityQueue;

use Drupal\entityqueue\EntityQueueBase;

/**
 * Class UserEntityQueueType
 * @package Drupal\entityqueue\Plugin\EntityQueue
 *
 * Implements a user entityqueue type.
 *
 * @EntityQueueType(
 *   id = "EntityQueue_user",
 *   title = @Translation("User"),
 *   entity_type = "user",
 * )
 */
class UserEntityQueueType extends EntityQueueBase {

}
