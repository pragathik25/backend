<?php

namespace Drupal\Controller_task\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller.
 */
class ControllerTaskController extends ControllerBase {

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a new ModuleCustomController object.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder service.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user service.
   */
  public function __construct(FormBuilderInterface $form_builder, AccountProxyInterface $current_user) {
    $this->formBuilder = $form_builder;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('form_builder'),
      $container->get('current_user')
    );
  }

  /**
   * Function.
   */
  public function build(Node $node) {
    $node_title = $node->getTitle();
    $current_user = User::load($this->currentUser->id());
    $form = $this->formBuilder->getForm('\Drupal\controller_task\Form\CustomForm', $node_title, $current_user);

    return $form;
  }

}
