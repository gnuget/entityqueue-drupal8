<?php

namespace Drupal\entityqueue\Controller;


use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

class EntityQueueListBuilder extends ConfigEntityListBuilder {

  public function buildHeader() {
    $header['label'] = $this->t('EntityQueue');
    $header['id'] = $this->t('Machine Name');

    return $header + parent::buildHeader();
  }

  public function buildRow(EntityInterface $entity) {
    $row['label'] = $this->getLabel($entity);
    $row['id'] = $entity->id();

    return $row + parent::buildRow($entity);
  }

} 