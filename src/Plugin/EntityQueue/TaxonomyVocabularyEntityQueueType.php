<?php
/**
 * @file
 * Contains \Drupal\entityqueue\Plugin\EntityQueue\TaxonomyVocabularyEntityQueueType.
 */

namespace Drupal\entityqueue\Plugin\EntityQueue;

use Drupal\entityqueue\EntityQueueBase;

/**
 * Class TaxonomyVocabularyEntityQueueType
 * @package Drupal\entityqueue\Plugin\EntityQueue
 *
 * Implements a taxonomyvocabulary entityqueue type.
 *
 * @EntityQueueType(
 *   id = "EntityQueue_taxonomy_vocabulary",
 *   title = @Translation("Taxonomy Vocabulary"),
 *   entity_type = "taxonomy_vocabulary",
 * )
 */
class TaxonomyVocabularyEntityQueueType extends EntityQueueBase {

}
