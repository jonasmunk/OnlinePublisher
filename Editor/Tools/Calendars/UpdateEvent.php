<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Event.php';
require_once 'CalendarsController.php';

$id = requestPostNumber('id');
$location = requestPostText('location');
$title = requestPostText('title');
$note = requestPostText('note');
$calendars = requestPostArray('calendars');
$startdate = requestPostDateTime('startdate');
$enddate = requestPostDateTime('enddate');
	
$event = Event::load($id);
$event->setTitle($title);
$event->setLocation($location);
$event->setNote($note);
$event->setStartdate($startdate);
$event->setEnddate($enddate);
$event->update();
$event->publish();

$event->updateCalendarIds($calendars);

CalendarsController::setUpdateSelection(true);
redirect(CalendarsController::getBaseWindow());
?>