<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Streamitem'] = [
  'table' => 'streamitem',
  'properties' => [
    'streamId' => ['type' => 'int','column' => 'stream_id',
      'relation' => ['class' => 'Stream', 'property' => 'id']
    ],
    'data' => ['type' => 'string'],
    'hash' => ['type' => 'string'],
    'originalDate' => ['type' => 'datetime', 'column' => 'originaldate'],
    'retrievalDate' => ['type' => 'datetime', 'column' => 'retrievaldate']
  ]
];

class Streamitem extends Object {

  var $streamId;
  var $dataId;
  var $hash;
  var $originalDate;
  var $retrievalDate;

  function Streamitem() {
    parent::Object('streamitem');
  }

  static function load($id) {
    return Object::get($id,'streamitem');
  }

  function setStreamId($streamId) {
    $this->streamId = $streamId;
  }

  function getStreamId() {
    return $this->streamId;
  }

  function setData($data) {
    $this->data = $data;
  }

  function getData() {
    return $this->data;
  }

  function setHash($hash) {
    $this->hash = $hash;
  }

  function getHash() {
    return $this->hash;
  }

  function setOriginalDate($originalDate) {
    $this->originalDate = $originalDate;
  }

  function getOriginalDate() {
    return $this->originalDate;
  }

  function setRetrievalDate($retrievalDate) {
    $this->retrievalDate = $retrievalDate;
  }

  function getRetrievalDate() {
    return $this->retrievalDate;
  }

}