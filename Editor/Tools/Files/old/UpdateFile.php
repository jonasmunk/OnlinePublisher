<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';
require_once '../../Classes/File.php';
require_once 'Functions.php';

$id = requestPostNumber('id',0);
$title = requestPostText('title');
$description = requestPostText('description');
$searchable = requestPostCheckbox('searchable');

$file = File::load($id);
$file->setTitle($title);
$file->setNote($description);
$file->setSearchable($searchable);
$file->update();
$file->publish();

setToolSessionVar('files','updateHierarchy',true);

redirect('FileProperties.php?id='.$id);
?>