<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['AuthenticationPart'] = [
  'table' => 'part_authentication',
  'properties' => []
];

class AuthenticationPart extends Part
{
  function AuthenticationPart() {
    parent::Part('authentication');
  }
  
  static function load($id) {
    return Part::get('authentication',$id);
  }
}
?>