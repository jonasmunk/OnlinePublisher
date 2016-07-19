<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['View'] = [
  'table' => 'view',
  'properties' => [
    'path' => ['type' => 'string']
  ]
];

class View extends Object {

  var $path;

  function View() {
    parent::Object('view');
  }

  static function load($id) {
    return Object::get($id,'view');
  }

  function setPath($path) {
    $this->path = $path;
  }

  function getPath() {
    return $this->path;
  }

}