<?php

namespace Drupal\block_task\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "module_custom_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("module_custom")
 * )
 */
class ExampleBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity display repository service.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new ExampleBlock object.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin ID.
   * @param mixed $plugin_definition
   *   The plugin definition.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   The entity display repository service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityDisplayRepositoryInterface $entity_display_repository, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityDisplayRepository = $entity_display_repository;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_display.repository'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'node_id' => "",
      'view_mode' => "teaser",
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['node_id'] = [
      '#type' => 'entity_autocomplete',
      '#title' => 'Select Node',
      '#default_value' => Node::load($this->configuration['node_id']),
      '#target_type' => 'node',
    ];
    $view_modes = $this->entityDisplayRepository->getViewModeOptions('node');
    $form['view_mode'] = [
      '#type' => 'radios',
      '#title' => 'View Mode',
      '#default_value' => $this->configuration['view_mode'],
      '#options' => $view_modes,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['node_id'] = $form_state->getValue('node_id');
    $this->configuration['view_mode'] = $form_state->getValue('view_mode');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node_id = $this->configuration['node_id'];
    $view_mode = $this->configuration['view_mode'];
    $node = Node::load($node_id);
    $view_mode_build = $this->entityTypeManager->getViewBuilder('node');
    $node_view = $view_mode_build->view($node, $view_mode);
    return $node_view;
  }

}
