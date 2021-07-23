<?php

namespace Drush\Commands\behat_drush_endpoint\expand\Fields\Drupal8;

use Drupal\Driver\Fields\Drupal8\AbstractHandler;
use Drupal\paragraphs\Entity\Paragraph;

/**
 *  Entity reference revisions for Drupal 8.
 */
class EntityReferenceRevisionsHandler extends AbstractHandler {

  /**
   * {@inheritdoc}
   */
  public function expand($values) {
    if ($this->fieldConfig->getSetting('handler') == 'default:paragraph') {
      return $this->paragraphProcess($values);
    }
    return $values;
  }

  /**
   * Process paragraph multi-level nesting
   * Theoretically support unlimited levels of nesting, only tested one level of nesting.
   */
  public function paragraphProcess($values) {
    $handler_settings = $this->fieldConfig->getSetting('handler_settings');
    $paragraph_can_use = $handler_settings['target_bundles'];
    foreach ($values as $key => &$value) {
      if (!isset($paragraph_can_use[$value->type])) {
        unset($value);
      }
      _drush_behat_expand_entity_fields('paragraph', $value);
      $data = array();
      foreach ($value as $filed => $filed_value) {
        $data[$filed] = $filed_value;
      }
      $paragraph = Paragraph::create($data);
      $paragraph->save();
      $value = $paragraph;
    }
    return $values;
  }
}
