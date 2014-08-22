<?php

namespace Drupal\entityqueue\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;


class EntityQueueForm extends EntityForm {

  /**
   * @param \Drupal\Core\Entity\Query\QueryFactory $entity_query
   *   The entity query.
   */
  public function __construct(QueryFactory $entity_query, $entity_manager, $plugin_manager_handler, $plugin_manager_entity) {
    $this->entityQuery = $entity_query;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
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
      '#options' => \Drupal::service('plugin.manager.entityqueue.handler')->getAllEntityQueueHandlers(),
      '#default_value' => '',
      '#required' => true,
    ];

    $form['entityqueue_entity_type'] = [
      '#type' => 'select',
      '#title' => t('Entity Type'),
      '#options' => \Drupal::service('plugin.manager.entityqueue.entity')->getAllEntityQueueTypes(),
      '#default_value' => '',
      '#required' => true,
    ];

    $form['queue_tabs'] = [
      '#type' => 'vertical_tabs',
      '#title' => '',
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

    $form['queue_field_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Field Settings'),
      '#group' => 'queue_tabs'
    ];

    $form['queue_field_settings']['test'] = [
      '#type' => 'textfield',
      '#title' => $this->t('test'),
      '#default_value' => 'testing',
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
}
