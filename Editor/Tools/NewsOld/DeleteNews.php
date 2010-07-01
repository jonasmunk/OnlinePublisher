<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/News.php';
require_once 'NewsController.php';

$id = requestGetNumber('id',0);

$news = News::load($id);
$news->remove();

NewsController::setUpdateHierarchy(true);

redirect(NewsController::getBaseWindow());
?>