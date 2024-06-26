<?php

namespace Drupal\group_views_join_link\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\group\Entity\GroupInterface;
use Drupal\group\Entity\GroupRelationship;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides group membership route controllers.
 *
 * This only controls the routes that are not supported out of the box by the
 * plugin base \Drupal\group\Plugin\Group\Relation\GroupRelationBase.
 */
class GroupMembershipController extends ControllerBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The entity form builder.
   *
   * @var \Drupal\Core\Entity\EntityFormBuilderInterface
   */
  protected $entityFormBuilder;

  /**
   * Constructs a new GroupMembershipController.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Entity\EntityFormBuilderInterface $entity_form_builder
   *   The entity form builder.
   */
  public function __construct(AccountInterface $current_user, EntityFormBuilderInterface $entity_form_builder) {
    $this->currentUser = $current_user;
    $this->entityFormBuilder = $entity_form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('entity.form_builder')
    );
  }

  /**
   * Provides the form for joining a group.
   *
   * @param \Drupal\group\Entity\GroupInterface $group
   *   The group to join.
   *
   * @return array
   *   A group join form.
   */
  public function follow(GroupInterface $group) {
    /** @var \Drupal\group\Plugin\GroupRelationshipEnablerInterface $plugin */
    $plugin_id = 'group_membership';
    $relationship_type_storage = \Drupal::entityTypeManager()->getStorage('group_relationship_type');
    $group_type_id = $group->getGroupType()->id();

    // Pre-populate a group membership with the current user.
    $group_relationship = GroupRelationship::create([
      'type' => $relationship_type_storage->getRelationshipTypeId($group_type_id, $plugin_id),
      'gid' => $group->id(),
      'entity_id' => $this->currentUser->id(),
      'group_roles' => ["open-member"],
    ]);

    return $this->entityFormBuilder->getForm($group_relationship, 'group-join');
  }

  /**
   * The _title_callback for the join form route.
   *
   * @param \Drupal\group\Entity\GroupInterface $group
   *   The group to join.
   *
   * @return string
   *   The page title.
   */
  public function followTitle(GroupInterface $group) {
    return $this->t('Follow group %label', ['%label' => $group->label()]);
  }

}