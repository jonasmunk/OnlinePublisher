<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Imagegroup.php';
require_once 'ImagesController.php';

$id = requestGetNumber('id',0);

$group = ImageGroup::load($id);
$group->remove();

ImagesController::setUpdateHierarchy(true);
redirect('Library.php');
?>