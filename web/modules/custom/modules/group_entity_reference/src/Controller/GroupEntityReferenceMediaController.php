<?php

namespace Drupal\group_entity_reference\Controller;

use Drupal\Core\Config\Entity;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin;
use Drupal\Core\Render\RendererInterface;
use Drupal\group\Entity\GroupInterface;
use Drupal\group\Plugin\GroupRelationshipEnablerManagerInterface;
use Drupal\Entity\Controller\GroupRelationshipController;
use Drupal\user\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel;

/**
 * Returns responses for 'group_media' routes.
 */
class GroupEntityReferenceMediaController extends GroupRelationshipController {

    /**
     * {@inheritdoc}
     */
    public function addPage(GroupInterface $group, $create_mode = FALSE, $base_plugin_id = NULL) {

        $build = GroupRelationshipController::addPage($group, $create_mode, $base_plugin_id);

        // Do not interfere with redirects.
        if (!is_array($build)) {
            return $build;
        }

        // Overwrite the label and description for all of the displayed bundles.
        $media_storage_handler = $this->entityTypeManager->getStorage('media_type');
        $page_bundles = $this->addPageBundles($group, $create_mode, $base_plugin_id);

        // We want the $build['#bundles'] array to be sorted by bundle label, but it's
        // sorted by array index which is the bundle machine name.
        // We need to build a new array using the bundle label as the array index.
        $new_bundles = [];
        foreach ($page_bundles as $plugin_id => $bundle_name) {
            $pluginID = $bundle_name->getPlugin()->getPluginId();
            // Only handle Media types here. Content are handled by GroupEntityReferenceRelationshipController.
            if(strpos($pluginID, 'group_media:') !== 0) continue;
            if (!empty($build['#bundles'][$plugin_id])) {
                $plugin = $group->getGroupType()->getPlugin($pluginID);
                $plugin_bundle = $plugin->getPluginDefinition()->getEntityBundle();
                $bundle_label = $media_storage_handler->load($plugin_bundle)->label();
                $bundle_id = $media_storage_handler->load($plugin_bundle)->id();
                $bundle_desc = \Drupal::config('media.type.' . $bundle_id)->get('description');

                $new_bundles[$bundle_label] = $build['#bundles'][$plugin_id];
                $new_bundles[$bundle_label]['label'] = $bundle_label;
                $new_bundles[$bundle_label]['description'] = $bundle_desc;
                // build custom link text; this overrides the link text created in the GroupNodeDeriver
                $new_bundles[$bundle_label]['add_link']->setText(t('Add ' . $bundle_label));
            }
        }
        // Sort the new array by key (i.e. bundle label)
        ksort($new_bundles);
        $build['#bundles'] = $new_bundles;
        return $build;
    }

}