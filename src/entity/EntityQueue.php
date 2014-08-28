<?php

namespace Drupal\entityqueue\entity;


use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\entityqueue\EntityQueueInterface;
use Drupal\Core\Plugin\DefaultSinglePluginBag;

/**
 * Defines the EntityQueue entity class.
 *
 * @ConfigEntityType(
 *   id = "entityqueue",
 *   label = @Translation("EntityQueue"),
 *   handlers = {
 *     "list_builder" = "Drupal\entityqueue\Controller\EntityQueueListBuilder",
 *     "form" = {
 *       "add" = "Drupal\entityqueue\Form\EntityQueueForm",
 *       "edit" = "Drupal\entityqueue\Form\EntityQueueForm",
 *       "delete" = "Drupal\entityqueue\Form\EntityQueueDeleteForm"
 *     }
 *   },
 *   config_prefix = "entityqueue",
 *   admin_permission = "administer entityqueue",
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
   * @var string.
   */
  public $id;

  /**
   * @var string $label.
   */
  public $label;

  /**
   * @var int $min_size.
   */
  public $min_size = 0;

  /**
   * @var int $max_size.
   */
  public $max_size = 10;

  /**
   * The ID of the EntityQueue Type plugin.
   *
   * @var string
   */
  public $target_type = '';


  /**
   * Handler
   */
  public $handler = 'simple';

  /**
   * The bundle of the entityqueue
   */
  public $target_bundles = '';

  /**
   * A bag to store the EntityQueueType plugin.
   *
   * @var \Drupal\Core\Plugin\DefaultSinglePluginBag
   */
  protected $entityqueueTypeBag;

  /**
   * An array to store and load the EntityQueue Type plugin configuration.
   * @var array
   */
  protected $entityQueueTypeConfig = array();

  /**
   * Overrides \Drupal\Core\Config\Entity\ConfigEntityBase::__construct();
   */
  public function __construct(array $values, $entity_type) {
    parent::__construct($values, $entity_type);

    if (!$this->type) {
      $this->type = 'EntityQueue_node';
    }

    $this->entityqueueTypeBag = new DefaultSinglePluginBag (
      \Drupal::service('plugin.manager.entityqueue.entity'),
      $this->type, $this->entityQueueTypeConfig
    );
  }

  /**
   * Get the Entityqueue type plugin for this entityqueue.
   */
  public function getEntityQueueTypePlugin($type) {

    if (empty($type)) {
      $type = $this->type;
    }

    return $this->entityqueueTypeBag->get($type);
  }

  /**
   *
   *
   */
  public function getTargetType() {
    return $this->target_type;
  }

  public function getHandler() {
    return $this->handler;
  }

  public function getTargetBundles() {
    return $this->target_bundles;
  }
}
