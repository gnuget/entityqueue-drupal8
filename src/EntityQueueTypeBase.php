<?php
/**
 * @file
 * Contains the EntityQueueTypeBase class.
 */

namespace Drupal\entityqueue;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EntityQueueTypeBase
 * @package Drupal\entityqueue\Plugin\EntityQueue
 */
abstract class EntityQueueBase extends PluginBase {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configuration += $this->defaultConfiguration();
  }
}
