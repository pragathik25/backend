<?php

namespace Drupal\Controller_task\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CustomDemo class.
 */
class CustomForm extends FormBase {

  /**
   * The Database service.
   *
   * @var Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs InviteByEmail .
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database service.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
    );
  }

  /**
   * Function.
   */
  public function getFormId() {
    return 'module_custom_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $node_title = NULL , $current_user = NULL) {

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => 'Title',
      '#default_value' => $node_title,
    ];

    $form['user'] = [
      '#type' => 'entity_autocomplete',
      '#title' => 'User',
      '#target_type' => 'user',
      '#default_value' => $current_user,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Save the configuration',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->database->insert("custom_form")->fields([
      'title' => $form_state->getValue("title"),
      'user' => $form_state->getValue("user"),
    ])->execute();
  }

}
