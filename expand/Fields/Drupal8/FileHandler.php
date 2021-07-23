<?php

namespace Drush\Commands\behat_drush_endpoint\expand\Fields\Drupal8;

use Drupal\Driver\Fields\Drupal8\EntityReferenceHandler;

/**
 * Rewrite FileHandler to save multi-file saved.
 *
 * @see Drupal\Driver\Fields\Drupal8\FileHandler
 */
class FileHandler extends EntityReferenceHandler {

  /**
   * {@inheritdoc}
   */
  public function expand($values) {
    $return = array();
    foreach ($values as $url) {
      $file = $this->saveFile($url);
      $return[] = array(
        'target_id' => $file->id(),
        'filename' => 'Behat test file',
      );
    }
    return $return;
  }

  /**
   * Save remote image to local.
   */
  protected function saveFile($url, $name = '') {
    $data = file_get_contents($url);
    if (FALSE === $data) {
      throw new \Exception("Error reading file");
    }
    $parse_url = parse_url($url);
    $basename = basename($parse_url['path']);
    $ext = explode('.',  $basename);
    if (!$name) {
      $name = uniqid();
    }
    $file = file_save_data($data, 'private://' . $name . '.' . $ext[count($ext)-1]);
    if (FALSE === $file) {
      throw new \Exception("Error saving file");
    }
    $file->save();
    return $file;
  }

}
