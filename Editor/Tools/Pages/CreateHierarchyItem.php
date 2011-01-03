<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/InternalSession.php';
require_once 'Functions.php';

$hierarchyId=requestPostNumber('hierarchy');
$return = InternalSession::getToolSessionVar('pages','rightFrame');

$title = requestPostText('title');
$alternative = requestPostText('alternative');
$parent = requestPostNumber('parent');
$target = requestPostText('target');

$targetId=0;
$targetValue='';
$targetType=requestPostText('type');
if ($targetType=='url') {
	$targetValue = requestPostText('url');
}
else if ($targetType=='page') {
	$targetId = requestPostNumber('page');
}
else if ($targetType=='pageref') {
	$targetId = requestPostNumber('pageref');
}
else if ($targetType=='file') {
	$targetId = requestPostNumber('file');
}
else if ($targetType=='email') {
	$targetValue = requestPostText('email');
}


$sql="select max(`index`) as `index` from hierarchy_item where parent=".$parent." and hierarchy_id=".$hierarchyId;
$result = Database::select($sql);
if ($row = Database::next($result)) {
	$index=$row['index']+1;
}
else {
	$index=1;
}
Database::free($result);

$sql="insert into hierarchy_item (title,alternative,type,hierarchy_id,parent,`index`,target_type,target_id,target_value,target) values (".
Database::text($title).
",".Database::text($alternative).
",'item'".
",".$hierarchyId.
",".$parent.
",".$index.
",".Database::text($targetType).
",".$targetId.
",".Database::text($targetValue).
",".Database::text($target).
")";
Database::insert($sql);

// Mark hierarchy as changed
$sql="update hierarchy set changed=now() where id=".$hierarchyId;
Database::update($sql);

InternalSession::setToolSessionVar('pages','updateHier',true);
redirect($return);
?>