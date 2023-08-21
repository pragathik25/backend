<?php

/**
 * @file
 * Provide views data for quick_node_clone.module.
 */

/**
 * Implements hook_views_data_alter().
 */
function fourteen_view_field_views_data_alter(&$data) {
  $data['node']['node_link'] = [
    'field' => [
      'title' => t('Add node link'),
      'help' => t('Provide a node link to the Content.'),
      'id' => 'fourteen_view_field_example',
    ],
  ];
}
