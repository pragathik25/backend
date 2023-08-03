<?php

namespace Drupal\template_task\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for template_task routes.
 */
class TemplateTaskController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {
    $config = $this->config('template_task.settings');
    $title = $config->get('title');
    $text = $config->get('text')['value'];
    $text_value = strip_tags($text);
    $color = $config->get('color');

    return [
      '#theme' => 'controller_template',
      '#text' => $text_value,
      '#title' => $title,
      '#color' => $color,
    ];
  }

}
