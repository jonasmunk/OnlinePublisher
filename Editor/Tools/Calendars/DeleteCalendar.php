<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Calendar.php';
require_once 'CalendarsController.php';

$id = requestGetNumber('id',0);

$calendar = Calendar::load($id);
$calendar->remove();

CalendarsController::setUpdateSelection(true);
redirect('Overview.php');
?>