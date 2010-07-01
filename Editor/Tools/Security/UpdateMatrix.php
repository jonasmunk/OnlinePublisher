<?
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Securityzone.php';
require_once '../../Classes/Page.php';


$added = requestPostText('added');
$removed = requestPostText('removed');

$addedItems = split(',',$added);
$removedItems = split(',',$removed);


foreach ($addedItems as $addedItem) {
	$pair = split('-',$addedItem);
	if ($pair[0]=='page') {
		$sql = "insert into securityzone_page (securityzone_id,page_id) values (".$pair[2].",".$pair[1].")";
		Database::insert($sql);
	} elseif ($pair[0]=='user') {
		$sql = "insert into securityzone_user (securityzone_id,user_id) values (".$pair[2].",".$pair[1].")";
		Database::insert($sql);
	}
}
foreach ($removedItems as $removedItem) {
	$pair = split('-',$removedItem);
	if ($pair[0]=='page') {
		$sql = "delete from securityzone_page where securityzone_id=".$pair[2]." and page_id=".$pair[1];
		Database::delete($sql);
	} elseif ($pair[0]=='user') {
		$sql = "delete from securityzone_user where securityzone_id=".$pair[2]." and user_id=".$pair[1];
		Database::delete($sql);
	}
}

Page::updateSecureStateOfAllPages();


redirect('Matrix.php');
?>