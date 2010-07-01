<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Persongroup.php';
require_once 'Functions.php';

$id = requestPostNumber('id',0);
$title = requestPostText('title');
$description = requestPostText('description');

$persongroup = PersonGroup::load($id);
$persongroup->setTitle($title);
$persongroup->setNote($description);
$persongroup->update();

setUpdateHierarchy(true);
redirect('Persongroup.php?id='.$id);
?>