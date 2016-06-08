<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class WorkflowState {
  static $STRING = 'string';
  static $FILE = 'file';
  static $OBJECT = 'object';
  static $URL = 'url';

  var $status = 'undefined';
  var $type;
  var $data;

  function clean() {
    $this->status = 'undefined';
  }

  public function log($value='') {
    Log::debug($value);
  }

  public function setStringData($data) {
    $this->setData($data,WorkflowState::$STRING);
  }

  public function setFileData($data) {
    $this->setData($data,WorkflowState::$FILE);
  }

  public function setObjectData($data) {
    $this->setData($data,WorkflowState::$OBJECT);
  }

  public function setData($data, $type) {
    $this->type = $type;
    $this->data = $data;
  }

  public function getData() {
    return $this->data;
  }

  function fail() {
    $this->status = 'failed';
  }

  function isSuccess() {
    return $this->status != 'failed';
  }
}
?>