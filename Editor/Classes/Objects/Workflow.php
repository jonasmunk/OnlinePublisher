<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Workflow'] = [
    'table' => 'workflow',
    'properties' => [
      'recipe' => ['type'=>'string']
    ]
];

class Workflow extends Object {
  var $recipe;

  function Workflow() {
    parent::Object('workflow');
  }

  static function load($id) {
    return Object::get($id,'workflow');
  }

  function setRecipe($recipe) {
      $this->recipe = $recipe;
  }

  function getRecipe() {
      return $this->recipe;
  }

}