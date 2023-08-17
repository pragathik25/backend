<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure custom_form settings for this site.
 */
class SettingsForm extends FormBase {
  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_form_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => 'First Name',
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => 'Last Name',
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => 'Email',
    ];
    $form['phone_no'] = [
      '#type' => 'tel',
      '#title' => 'Phone No',
    ];
    $form['gender'] = [
      '#type' => 'select',
      '#title' => 'Gender',
      '#options' => [
        'male' => 'Male',
        'female' => 'Female',
      ],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'submit the form',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->database->insert("custom_form_example")->fields([
      'first_name' => $form_state->getValue('first_name'),
      'last_name' => $form_state->getValue('last_name'),
      'email' => $form_state->getValue('email'),
      'phone_no' => $form_state->getValue('phone_no'),
      'gender' => $form_state->getValue('gender'),
    ])->execute();
  }

}
