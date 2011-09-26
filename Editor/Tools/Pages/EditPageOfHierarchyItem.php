<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/Request.php';
require_once 'Functions.php';

$id = Request::getInt('id',0);

$sql="select target_type,target_id from hierarchy_item where id=".$id;
$row = Database::selectFirst($sql);

$targetType=$row['target_type'];
$targetId=$row['target_id'];

if ($targetType=='page') {
	Response::redirect('EditPage.php?id='.$targetId);
} else {
	Response::redirect('EditHierarchyItem.php?id='.$id);
}
?>