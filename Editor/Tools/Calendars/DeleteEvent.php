<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Event.php';
require_once 'CalendarsController.php';

$id = requestGetNumber('id');

$event = Event::load($id);
$event->remove();

CalendarsController::setUpdateSelection(true);
redirect(CalendarsController::getBaseWindow());
?>