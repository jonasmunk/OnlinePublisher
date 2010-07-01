<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Newsgroup.php';
require_once 'NewsController.php';

$id = requestGetNumber('id',0);

$group = NewsGroup::load($id);
$group->remove();

NewsController::setUpdateHierarchy(true);
redirect('Library.php');
?>