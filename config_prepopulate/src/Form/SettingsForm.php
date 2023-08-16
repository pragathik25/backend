<?php

namespace Drupal\config_prepopulate\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure config_prepopulate settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_prepopulate_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['config_prepopulate.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = \Drupal::config('config_prepopulate.settings');
    $tags = $config->get('tags');
    $term_name = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tags);
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => 'Title',
      '#default_value' => $this->config('config_prepopulate.settings')->get('title'),
    ];
    $form['tags'] = [
      '#type' => 'entity_autocomplete',
      '#title' => 'Tags',
      '#target_type' => 'taxonomy_term',
      '#selection_settings' => [
        'target_bundles' => ['tags'],
      ],
      '#default_value' => $term_name,
    ];
    $form['advanced'] = [
      '#type' => 'checkbox',
      '#title' => 'Advanced',
      '#default_value' => $this->config('config_prepopulate.settings')->get('advanced'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('config_prepopulate.settings')
      ->set('title', $form_state->getValue('title'))
      ->set('tags', $form_state->getValue('tags'))
      ->set('advanced', $form_state->getValue('advanced'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
