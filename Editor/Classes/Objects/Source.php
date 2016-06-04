<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Source'] = [
    'table' => 'source',
    'properties' => [
      'url' => ['type'=>'string'],
      'synchronized' => ['type' => 'datetime'],
      'interval' => ['type' => 'int']
    ]
];

class Source extends Object {
  var $url;
  var $synchronized;
  var $interval;

  function Source() {
    parent::Object('source');
  }

  static function load($id) {
    return Object::get($id,'source');
  }

  function setUrl($url) {
    $this->url = $url;
  }

  function getUrl() {
    return $this->url;
  }

  function setSynchronized($synchronized) {
    $this->synchronized = $synchronized;
  }

  function getSynchronized() {
    return $this->synchronized;
  }

  function setInterval($interval) {
    $this->interval = $interval;
  }

  function getInterval() {
    return $this->interval;
  }
}