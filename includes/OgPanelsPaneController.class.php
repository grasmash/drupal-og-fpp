<?php
/**
 * @file
 *
 * Contains the controller class for the Fieldable Panel Pane entity.
 */

/**
 * Entity controller class.
 */
class OgPanelsPaneController extends PanelsPaneController {

  public function access($op, $entity = NULL, $account = NULL) {
    // Check global level permissions in parent class.
    $access = parent::access($op, $entity, $account);

    // If global level access for this operation is not permitted, check for
    // a group context and group specific permissions.
    if (!$access) {
      if ($op == 'create') {
        // For backwards compatibility with a entity reference bug, we verify
        // $entity data type.
        if (is_object($entity)) {
          $entity = $entity->bundle;
        }
        return og_user_access_entity('create fieldable ' . $entity, 'fieldable_panels_pane', $entity);
      }
      elseif (og_is_group_content_type('fieldable_panels_pane', $entity->bundle)) {
        // We need to load $entity to populate properties required og.
        $entity = entity_load_single('fieldable_panels_pane', $entity->fpid);

        switch ($op) {
          case 'update':
            return og_user_access_entity('edit fieldable ' . $entity->bundle, 'fieldable_panels_pane', $entity);

          case 'delete':
            return og_user_access_entity('delete fieldable ' . $entity->bundle, 'fieldable_panels_pane', $entity);
        }
      }
    }

    return $access;
  }
}
