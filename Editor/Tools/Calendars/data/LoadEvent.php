<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/In2iGui.php';
require_once '../../../Classes/Objects/Event.php';

$id = Request::getInt('id');
$event = Event::load($id);
$event->toUnicode();

$groups = $event->getCalendarIds();

In2iGui::sendObject(array('event' => $event, 'calendars' => $groups));
?>