<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$event = Event::load($id);

$groups = $event->getCalendarIds();

Response::sendObject(array('event' => $event, 'calendars' => $groups));
?>