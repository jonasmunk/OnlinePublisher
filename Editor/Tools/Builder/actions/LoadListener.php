<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Builder
 */
require_once '../../../Include/Private.php';

if ($obj = Listener::load(Request::getId())) {
  $response = [
    'id' => $obj->getId(),
    'title' => $obj->getTitle(),
    'event' => $obj->getEvent(),
    'interval' => $obj->getInterval()
  ];
	$flow = Query::after('workflow')->withRelationFrom($obj)->first();
  if ($flow) {
    $response['runnable'] = $flow->getId();
  }
  Response::sendObject($response);
} else {
  Response::sendNotFound();
}
?>