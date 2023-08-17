<?php

namespace Drupal\custom_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for custom_form routes.
 */
class CustomFormController extends ControllerBase {
  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a SettingsForm object.
   *
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
   * Builds the response.
   */
  public function build() {

    $result = $this->database->select("custom_form_example", 'n')
      ->fields('n', ['id', 'first_name', 'last_name', 'email',
        'phone_no', 'gender',
      ])
      ->execute()->fetchAllAssoc('id');

    $rows = [];
    foreach ($result as $row => $content) {
      $rows[] = [
        'data' => [$content->id, $content->first_name, $content->last_name,
          $content->email, $content->phone_no, $content->gender,
        ],
      ];
    }

    $output = [
      '#theme' => 'custom_template',
      '#rows' => $rows,
    ];
    // print_r($rows);exit;
    return $output;
  }

}
