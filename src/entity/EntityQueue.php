<?php

namespace Drupal\entityqueue\entity;


use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\entityqueue\EntityQueueInterface;

/**
 * Defines the EntityQueue entity class.
 *
 * @ConfigEntityType(
 *   id = "entityqueue",
 *   label = @Translation("EntityQueue"),
 *   controllers = {
 *     "list_builder" = "Drupal\entityqueue\Controller\EntityQueueListBuilder",
 *     "form" = {
 *       "add" = "Drupal\entityqueue\Form\EntityQueueForm",
 *       "edit" = "Drupal\entityqueue\Form\EntityQueueForm",
 *       "delete" = "Drupal\entityqueue\Form\EntityQueueDeleteForm"
 *     }
 *   },
 *   config_prefix = "entityqueue",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   links = {
 *     "edit-form" = "entityqueue.edit",
 *     "delete-form" = "entityqueue.delete"
 *   }
 * )
 */
class EntityQueue  extends ConfigEntityBase implements EntityQueueInterface {

  /**
   * The EntityQueue ID.
   *
   * @var string
   */
  public $id;

  /**
   * @var string $label
   */
  public $label;
}
