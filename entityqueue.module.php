<?php
/**
 * Implements hook_permission().
 */
function entityqueue_permission() {
  $permissions = array(
    'administer entityqueues' => array(
      'title' => t('Administer entityqueues'),
      'description' => t('Create and edit entityqueues.'),
    ),
  );

  return $permissions;
}