<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Calendar.php';
require_once 'CalendarsController.php';

$id = requestPostNumber('id');
$title = requestPostText('title');

$calendar = Calendar::load($id);
$calendar->setTitle($title);
$calendar->update();
$calendar->publish();

CalendarsController::setUpdateSelection(true);

redirect('Calendar.php?id='.$id);
?>