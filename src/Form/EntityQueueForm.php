<?php

namespace Drupal\entityqueue\Form;

use Drupal\Core\Entity\EntityForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entityqueue\EntityQueuePluginManager;
use Drupal\entityqueue\QueueHandlerManager;

class EntityQueueForm extends EntityForm  {

  var $entityQuery;
  var $pluginManagerHandler;
  var $pluginManagerEntity;
  var $entityManager;

  /**
   * @param \Drupal\Core\Entity\Query\QueryFactory $entity_query
   * @param \Drupal\entityqueue\QueueHandlerManager
   * @param \Drupal\entityqueue\EntityQueuePluginManager
   */
  public function __construct(QueryFactory $entity_query, QueueHandlerManager $plugin_manager_handler, EntityQueuePluginManager $plugin_manager_entity) {
    $this->entityQuery = $entity_query;
    $this->pluginManagerHandler = $plugin_manager_handler;
    $this->pluginManagerEntity = $plugin_manager_entity;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query'),
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
      '#default_value' => $entityqueue->getHandler(),
      '#required' => true,
      '#disabled' => !$entityqueue->isNew(),
    ];

    $form['target_type'] = [
      '#type' => 'select',
      '#title' => t('Entity Type'),
      '#options' => $this->pluginManagerEntity->getAllEntityQueueTypes(),
      '#default_value' => $entityqueue->getTargetType(),
      '#required' => true,
      '#ajax' => [
        'callback' => [$this, 'updateSelectedQueueType'],
        'wrapper' => 'edit-bundles',
        'method' => 'replace',
        'event' => 'change',
      ],
      '#disabled' => !$entityqueue->isNew(),
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
      '#default_value' => $entityqueue->getTargetBundles(),
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
    $form_state->setRedirect('entityqueue.list');
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
    $entityqueue = $this->entity;
    $type_plugin = $form_state->getValue('target_type');
    $type_plugin = $entityqueue->getEntityQueueTypePlugin($type_plugin);

    $options = $type_plugin->getBundles();

    // Deleting the old options.
    $children = \Drupal\Core\Render\Element::children($form['queue_field_settings']['target_bundles']);
    foreach ($children as $child) {
      unset($form['queue_field_settings']['target_bundles'][$child]);
    }

    // Adding the new ones.
    foreach ($options as $option) {
      $form['queue_field_settings']['target_bundles'][$option] = [
        '#type' => 'checkbox',
        '#title' => $option,
      ];
    }

    return $form['queue_field_settings']['target_bundles'];
  }
}
