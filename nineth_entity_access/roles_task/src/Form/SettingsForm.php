<?php

namespace Drupal\roles_task\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a form to configure role task settings.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a SettingsForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['roles_task.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'roles_task_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('roles_task.settings');

    $roles = $this->entityTypeManager->getStorage('user_role')->loadMultiple();
    $role_options = [];
    foreach ($roles as $role) {
      $role_options[$role->id()] = $role->label();
    }

    $content_types = $this->entityTypeManager->getStorage('node_type')->loadMultiple();
    $content_type_options = [];
    foreach ($content_types as $content_type) {
      $content_type_options[$content_type->id()] = $content_type->label();
    }

    $form['roles'] = [
      '#type' => 'checkboxes',
      '#title' => 'Roles',
      '#options' => $role_options,
      '#default_value' => $config->get('roles') ?: [],
    ];

    $form['content_types'] = [
      '#type' => 'checkboxes',
      '#title' => 'Content Types',
      '#options' => $content_type_options,
      '#default_value' => $config->get('content_types') ?: [],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('roles_task.settings');
    $config->set('roles', $form_state->getValue('roles'));
    $config->set('content_types', $form_state->getValue('content_types'));
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
