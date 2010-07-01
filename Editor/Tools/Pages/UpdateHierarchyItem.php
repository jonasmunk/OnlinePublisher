<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';

$return = requestPostText('return');
$hierarchyId = requestPostNumber('hierarchy',0);
$id = requestPostNumber('id',0);

$title = requestPostText('title');
$alternative = requestPostText('alternative');
$target = requestPostText('target');
$hidden = requestPostCheckbox('hidden');

$targetId=0;
$targetValue='';
$targetType = requestPostText('type');
if ($targetType=='url') {
	$targetValue = requestPostText('url');
}
else if ($targetType=='page') {
	$targetId = requestPostText('page');
}
else if ($targetType=='pageref') {
	$targetId = requestPostText('pageref');
}
else if ($targetType=='file') {
	$targetId = requestPostText('file');
}
else if ($targetType=='email') {
	$targetValue = requestPostText('email');
}


$sql="update hierarchy_item set".
" title=".sqlText($title).
",alternative=".sqlText($alternative).
",target_type=".sqlText($targetType).
",target_id=".$targetId.
",target_value=".sqlText($targetValue).
",target=".sqlText($target).
",hidden=".sqlBoolean($hidden).
" where id=".$id;
Database::update($sql);

// Mark hierarchy as changed
$sql="update hierarchy set changed=now() where id=".$hierarchyId;
Database::update($sql);


setToolSessionVar('pages','updateHier',true);
redirect($return);
?>