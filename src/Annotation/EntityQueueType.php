<?php
/**
 * @file
 * Contains the EntityQueueType annotation plugin.
 */

namespace Drupal\entityqueue\Annotation;

use Drupal\Component\Annotation\Plugin;


/**
 * Defines a EntityQueueType annotation object.
 *
 * @Annotation
 */
class EntityQueueType extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The title of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $title;

  /**
   * The entity type the EntityQueue type supports.
   *
   * @var string
   */
  public $entity_type;

}
