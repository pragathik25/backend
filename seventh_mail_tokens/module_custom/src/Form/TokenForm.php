<?php

namespace Drupal\module_custom\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Extension\ModuleHandler;

/**
 * Implements the example form.
 */
class TokenForm extends ConfigFormBase {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Extension\ModuleHandler
   */
  protected $moduleHandler;

  /**
   * Constructs a SettingsForm object.
   *
   * @param \Drupal\Core\Extension\ModuleHandler $moduleHandler
   *   The module Handler service.
   */
  public function __construct(ModuleHandler $moduleHandler) {
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('module_handler')
    );
  }

  const CONFIGNAME = "module_custom.settings";

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'token_form';
  }

  /**
   * Function.
   */
  protected function getEditableConfigNames() {
    return [
      static::CONFIGNAME,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::CONFIGNAME);
    $form['subject'] = [
      '#type' => 'textfield',
      '#title' => 'Subject',
      '#default_value' => $config->get("subject"),
    ];

    $text_format = 'full_html';
    if ($config->get('text')['format']) {
      $text_format = $config->get('text')['format'];
    }
    $form['text'] = [
      '#type' => 'text_format',
      '#title' => 'Text',
      '#format' => $text_format,
      '#default_value' => $config->get("text")['value'],
    ];

    if ($this->moduleHandler->moduleExists('token')) {
      $form['tokens'] = [
        '#title' => $this->t('Tokens'),
        '#type' => 'container',
      ];
      $form['tokens']['help'] = [
        '#theme' => 'token_tree_link',
        '#token_types' => [
          'node',
        ],
        '#global_types' => FALSE,
        '#dialog' => TRUE,
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config(static::CONFIGNAME);
    $config->set("subject", $form_state->getValue('subject'));
    $config->set("text", $form_state->getValue('text'));
    $config->save();
  }

}
