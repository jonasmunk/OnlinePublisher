<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

$id = Request::getInt('id',0);

$sql="select target_type,target_id from hierarchy_item where id=".$id;
$row = Database::selectFirst($sql);

$targetType=$row['target_type'];
$targetId=$row['target_id'];

if ($targetType=='page') {
	redirect('EditPage.php?id='.$targetId);
} else {
	redirect('EditHierarchyItem.php?id='.$id);
}
?>