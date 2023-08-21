<?php

namespace Drupal\config_prepopulate\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure config_prepopulate settings for this site.
 */
class SettingsForm extends ConfigFormBase {
  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */

  protected $configFactory;
  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a SettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The module Handler service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The module Handler service.
   */
  public function __construct(ConfigFactoryInterface $configFactory, EntityTypeManagerInterface $entityTypeManager) {
    $this->configFactory = $configFactory;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }

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
    $config = $this->configFactory->get('config_prepopulate.settings');
    $tags = $config->get('tags');
    $term_name = $this->entityTypeManager->getStorage('taxonomy_term')->load($tags);
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => 'Title',
      '#default_value' => $config->get('title'),
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
      '#default_value' => $config->get('advanced'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable('config_prepopulate.settings')
      ->set('title', $form_state->getValue('title'))
      ->set('tags', $form_state->getValue('tags'))
      ->set('advanced', $form_state->getValue('advanced'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
