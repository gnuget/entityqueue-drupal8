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
  public function __construct(QueryFactory $entity_query) {
    $this->entityQuery = $entity_query;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
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

    $form['id'] = [
      '#type' => 'select',
      '#title' => $this->t('Handler'),
      '#options' => \Drupal::service('plugin.manager.entityqueue.handler')->getAllEntityQueueHandlers(),
      '#default_value' => '',
    ];

    $form['queue_properties'] = array(
      '#type' => 'fieldset',
      '#title' => t('Queue Properties'),
    );

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

    $form['queue_properties']['entityqueue_entity_type'] = array(
      '#type' => 'select',
      '#title' => t('Entity Type'),
      '#options' => \Drupal::service('plugin.manager.entityqueue.entity')->getAllEntityQueueTypes(),
      '#default_value' => '',
    );

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
