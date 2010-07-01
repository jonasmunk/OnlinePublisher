<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';
require_once '../../Classes/Image.php';

require_once 'ImagesController.php';

$id = requestGetNumber('id',0);
$close = ImagesController::getBaseWindow();

$image = Image::load($id);
$image->remove();

ImagesController::setUpdateHierarchy(true);

redirect($close);
?>