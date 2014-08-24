<?php

namespace Drupal\entityqueue\Form;

use Drupal\Core\Entity\EntityForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entityqueue\EntityQueuePluginManager;
use Drupal\entityqueue\QueueHandlerManager;
use Drupal\Core\Entity\EntityManagerInterface;

class EntityQueueForm extends EntityForm  {

  var $entityQuery;
  var $pluginManagerHandler;
  var $pluginManagerEntity;
  var $entityManager;

  /**
   * @param \Drupal\Core\Entity\Query\QueryFactory $entity_query
   * @param \Drupal\Core\Entity\EntityManagerInterface
   * @param \Drupal\entityqueue\QueueHandlerManager
   * @param \Drupal\entityqueue\EntityQueuePluginManager
   */
  public function __construct(QueryFactory $entity_query, EntityManagerInterface $entity_manager, QueueHandlerManager $plugin_manager_handler, EntityQueuePluginManager $plugin_manager_entity) {
    $this->entityQuery = $entity_query;
    $this->pluginManagerHandler = $plugin_manager_handler;
    $this->pluginManagerEntity = $plugin_manager_entity;
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query'),
      $container->get('entity.manager'),
      $container->get('plugin.manager.entityqueue.handler'),
      $container->get('plugin.manager.entityqueue.entity')
    );
  }

  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $entityqueue = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Administrative title'),
      '#maxlength' => 255,
      '#default_value' => $entityqueue->label(),
      '#description' => $this->t("This will appear in the administrative interface to easily identify it."),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $entityqueue->id(),
      '#machine_name' => [
        'exists' => [$this, 'exist'],
      ],
      '#disabled' => !$entityqueue->isNew(),
    ];

    $form['handler'] = [
      '#type' => 'select',
      '#title' => $this->t('Handler'),
      '#options' => $this->pluginManagerHandler->getAllEntityQueueHandlers(),
      '#default_value' => '',
      '#required' => true,
    ];

    $form['target_type'] = [
      '#type' => 'select',
      '#title' => t('Entity Type'),
      '#options' => $this->pluginManagerEntity->getAllEntityQueueTypes(),
      '#default_value' => $entityqueue->getType(),
      '#required' => true,
      '#ajax' => [
        'callback' => [$this, 'updateSelectedQueueType'],
        'wrapper' => 'edit-bundles',
        'method' => 'replace',
        'event' => 'change',
      ],
    ];

    $form['queue_tabs'] = [
      '#type' => 'vertical_tabs',
      '#title' => '',
    ];

    $form['queue_field_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Field Settings'),
      '#group' => 'queue_tabs'
    ];

    $type_plugin = $entityqueue->getEntityQueueTypePlugin();
    $options = $type_plugin->getBundles();

    $form['queue_field_settings']['target_bundles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Content types'),
      '#options' => $options,
      '#description' => 'The bundles of the entity type that can be referenced. Optional, leave empty for all bundles.',
      '#prefix' => '<div id="edit-bundles">',
      '#suffix' => '</div>',
    ];

    $form['queue_properties'] = [
      '#type' => 'details',
      '#title' => $this->t('Queue Properties'),
      '#group' => 'queue_tabs'
    ];

    $form['queue_properties']['min_size'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Restrict this queue to a minimum of'),
      '#default_value' => $entityqueue->min_size,
    ];

    $form['queue_properties']['max_size'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Restrict this queue to a minimum of'),
      '#default_value' => $entityqueue->max_size,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entityqueue = $this->entity;
    $status = $entityqueue->save();
    if ($status) {
      drupal_set_message($this->t('Saved the %label EntityQueue.', [
        '%label' => $entityqueue->label(),
      ]));
    }
    else {
      drupal_set_message($this->t('The %label EntityQueue was not saved.', [
        '%label' => $entityqueue->label(),
      ]));
    }
    $form_state['redirect_route']['route_name'] = 'entityqueue.list';
  }

  public function exist($id) {
    $entity = $this->entityQuery->get('entityqueue')
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

  /**
   *
   */
  public function updateSelectedQueueType($form, FormStateInterface $form_state) {
    return $form['queue_field_settings']['target_bundles'];
  }
}
