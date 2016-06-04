<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Stream'] = [
  'table' => 'stream',
  'properties' => [
  ]
];

class Stream extends Object {

  function Stream() {
    parent::Object('stream');
  }

  static function load($id) {
    return Object::get($id,'stream');
  }

	function removeMore() {
		$items = Query::after('streamitem')->withProperty('stream_id',$this->id)->get();
		foreach ($items as $item) {
			$item->remove();
		}
	}

}