<?php

namespace Drupal\custom_form_task\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Custom form task form.
 */
class ExampleForm extends FormBase {

  /**
   * The logger service.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * Constructs an ExampleForm object.
   *
   * @param \Drupal\Core\Logger\LoggerChannelInterface $logger
   *   The logger service.
   */
  public function __construct(LoggerChannelInterface $logger) {
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('logger.factory')->get('custom_form_task')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_form_task_example';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#attached']['library'][] = "custom_form_task/config_lib";
    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#required' => TRUE,
    ];

    $form['no_last_name'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('No Last Name'),
      '#attributes' => ['id' => 'no-last-name'],
    ];

    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
      // '#states' => [
      // 'visible' => [
      // ':input[name="no_last_name"]' => ['checked' => FALSE],
      // ],
      // ],
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->logger->error('error');
    $this->logger->warning('warning');
    $this->logger->notice('Form submitted with first name: @first_name and last name: @last_name', [
      '@first_name' => $form_state->getValue('first_name'),
      '@last_name' => $form_state->getValue('last_name'),
    ]);

    $this->messenger()->addStatus($this->t('The form has been submitted.'));
    $form_state->setRedirect('<front>');
  }

}
