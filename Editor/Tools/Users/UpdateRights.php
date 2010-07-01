<?
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

$added = requestPostText('added');
$removed = requestPostText('removed');

$addedItems = split(',',$added);
$removedItems = split(',',$removed);


foreach ($addedItems as $addedItem) {
	$pair = split('-',$addedItem);
	if ($pair[0]=='tool') {
		$sql = "insert into user_permission (user_id,entity_type,entity_id,permission) values (".$pair[2].",'tool',".$pair[1].",'use')";
		Database::insert($sql);
	}
}
foreach ($removedItems as $removedItem) {
	$pair = split('-',$removedItem);
	if ($pair[0]=='tool') {
		$sql = "delete from user_permission where user_id=".$pair[2]." and entity_type='tool' and entity_id=".$pair[1];
		Database::delete($sql);
	}
}


redirect('Rights.php');
?>