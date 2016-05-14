<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ModelAuditor implements ModelEventListener {

  static function _log($prefix,$event) {
    Log::debug($prefix . ': ' . ($event['type'] ? $event['type'] . ' : ' : '') . $event['id']);
  }

  static function objectCreated($event) {
    ModelAuditor::_log('Object created', $event);
  }

  static function objectDeleted($event) {
    ModelAuditor::_log('Object deleted', $event);
  }

  static function objectUpdated($event) {
    ModelAuditor::_log('Object updated', $event);
  }

  static function objectPublished($event) {
    ModelAuditor::_log('Object published', $event);
  }

  static function hierarchyUpdated($event) {
    ModelAuditor::_log('Hierarchy updated', $event);
  }
}