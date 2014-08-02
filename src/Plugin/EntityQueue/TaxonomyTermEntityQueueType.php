<?php
/**
 * @file
 * Contains \Drupal\entityqueue\Plugin\EntityQueue\TaxonomyTermEntityQueueType.
 */

namespace Drupal\entityqueue\Plugin\EntityQueue;

use Drupal\entityqueue\EntityQueueBase;

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
class TaxonomyTermEntityQueueType extends EntityQueueBase {

}
