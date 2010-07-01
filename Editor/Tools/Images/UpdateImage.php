<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Image.php';
require_once 'ImagesController.php';

$id = requestPostNumber('id',0);
$title = requestPostText('title');
$description = requestPostText('description');
$searchable = requestPostCheckbox('searchable');
$groups = requestPostArray('group');

$image = Image::load($id);
$image->setTitle($title);
$image->setNote($description);
$image->setSearchable($searchable);
$image->update();
$image->changeGroups($groups);
$image->publish();

ImagesController::setUpdateHierarchy(true);

redirect('ImageProperties.php?id='.$id);
?>