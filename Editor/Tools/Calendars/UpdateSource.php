<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Calendarsource.php';
require_once 'CalendarsController.php';

$id = Request::getInt('id');
$title = Request::getString('title');
$url = Request::getString('url');
$displayTitle = Request::getString('displayTitle');
$filter = Request::getString('filter');
$syncInterval = Request::getInt('syncInterval');

$source = Calendarsource::load($id);
$source->setTitle($title);
$source->setUrl($url);
$source->setDisplayTitle($displayTitle);
$source->setFilter($filter);
$source->setSyncInterval($syncInterval);
$source->update();
$source->publish();

CalendarsController::setUpdateSelection(true);

redirect('Source.php?id='.$id);
?>