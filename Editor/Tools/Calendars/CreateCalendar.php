<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Calendar.php';
require_once 'CalendarsController.php';

$title = requestPostText('title');

$calendar = new Calendar();
$calendar->setTitle($title);
$calendar->create();
$calendar->publish();

CalendarsController::setUpdateSelection(true);

redirect('Calendar.php?id='.$calendar->getId());
?>