<?php

namespace Drupal\fourteen_view_field\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides Example field handler.
 *
 * @ViewsField("fourteen_view_field_example")
 *
 * @DCG
 * The plugin needs to be assigned to a specific table column through
 * hook_views_data() or hook_views_data_alter().
 * For non-existent columns (i.e. computed fields) you need to override
 * self::query() method.
 */
class Example extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['text'] = ['default' => $this->getDefaultLabel()];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $form['text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Text to display'),
      '#default_value' => $this->options['text'],
    ];
    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function query() {}

  /**
   * Returns the default label for the link.
   *
   * @return string
   *   The default link label.
   */
  protected function getDefaultLabel() {
    return $this->t('Node');
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $node = $this->getEntity($values);

    if (!$node) {
      return '';
    }

    $url = Url::fromRoute('entity.node.canonical', ['node' => $node->id()]);

    if (!$url->access()) {
      return '';
    }

    return [
      '#type' => 'link',
      '#url' => $url,
      '#title' => $this->options['text'] ?: $this->getDefaultLabel(),
    ];
  }

}
