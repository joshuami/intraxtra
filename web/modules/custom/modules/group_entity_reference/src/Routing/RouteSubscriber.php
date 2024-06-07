<?php

namespace Drupal\group_entity_reference\Routing;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\Core\Routing\RoutingEvents;
use Drupal\search_api_page\Routing\SearchApiPageRoutes;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {
  /**
   * The logger channel factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The language manager service.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  public function __construct(
      LoggerChannelFactoryInterface $logger_factory,
      EntityTypeManagerInterface $entity_type_manager,
      LanguageManagerInterface $language_manager
    ) {
      $this->loggerFactory = $logger_factory;
      $this->entityTypeManager = $entity_type_manager;
      $this->languageManager = $language_manager;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // override routes from the Group gnode and groupmedia modules to set our own controller::method
    if ($route = $collection->get('entity.group_relationship.group_node_create_page')) {
      $route->setDefault('_controller', '\Drupal\group_entity_reference\Controller\GroupEntityReferenceRelationshipController::addPage');
    }
    if ($route = $collection->get('entity.group_relationship.group_node_add_page')) {
      $route->setDefault('_controller', '\Drupal\group_entity_reference\Controller\GroupEntityReferenceRelationshipController::addPage');
    }
    if ($route = $collection->get('entity.group_relationship.group_media_create_page')) {
        $route->setDefault('_controller', '\Drupal\group_entity_reference\Controller\GroupEntityReferenceMediaController::addPage');
    }
    // override group create content route to set our own title callback so we can customize it
    if ($route = $collection->get('entity.group_relationship.create_form')) {
        $route->setDefault('_title_callback', '\Drupal\group_entity_reference\Controller\GroupEntityReferenceRelationshipController::createFormTitle');
    }
  }
}
