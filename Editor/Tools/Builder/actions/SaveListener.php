<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Builder
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id) {
	$listener = Listener::load($data->id);
} else {
	$listener = new Listener();
}
if ($listener) {
	$listener->setTitle($data->title);
	$listener->setEvent($data->event);
	$listener->setInterval($data->interval);
	$listener->save();
  ObjectService::removeRelations($listener->getId());
  if ($flow = Workflow::load($data->runnable)) {
    RelationsService::relateObjectToObject($listener, $flow);
  }
	$listener->publish();
}
?>