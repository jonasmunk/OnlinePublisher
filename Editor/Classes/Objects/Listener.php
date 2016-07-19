<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Listener'] = [
  'table' => 'listener',
  'properties' => [
    'event' => ['type' => 'string'],
    'latestExecution' => ['type' => 'datetime', 'column' => 'latest_execution'],
    'interval' => ['type' => 'int']
  ]
];

class Listener extends Object {
  var $event;
  var $latestExecution;
  var $interval;

  function Listener() {
    parent::Object('listener');
  }

  static function load($id) {
    return Object::get($id,'listener');
  }

  function setEvent($event) {
    $this->event = $event;
  }

  function getEvent() {
    return $this->event;
  }

  function setLatestExecution($latestExecution) {
    $this->latestExecution = $latestExecution;
  }

  function getLatestExecution() {
    return $this->latestExecution;
  }

  function setInterval($interval) {
    $this->interval = $interval;
  }

  function getInterval() {
    return $this->interval;
  }

}