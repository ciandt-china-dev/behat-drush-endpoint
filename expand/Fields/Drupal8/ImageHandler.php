<?php

namespace Drush\Commands\behat_drush_endpoint\expand\Fields\Drupal8;

/**
 * Rewrite ImageHandler to support multi-image saved.
 *
 * @see Drupal\Driver\Fields\Drupal8\ImageHandler
 */
class ImageHandler extends FileHandler {

  /**
   * {@inheritdoc}
   */
  public function expand($values) {
    $return = array();
    foreach ($values as $image) {
      $file = $this->saveFile($image);
      $return[] = array(
        'target_id' => $file->id(),
        'alt' => 'Behat test image',
        'title' => 'Behat test image',
      );
    }
    return $return;
  }

}
