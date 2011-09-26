<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Core/Request.php';

$return = Request::getString('return');
$hierarchyId = Request::getInt('hierarchy',0);
$id = Request::getInt('id',0);

$title = Request::getString('title');
$alternative = Request::getString('alternative');
$target = Request::getString('target');
$hidden = Request::getCheckbox('hidden');

$targetId=0;
$targetValue='';
$targetType = Request::getString('type');
if ($targetType=='url') {
	$targetValue = Request::getString('url');
}
else if ($targetType=='page') {
	$targetId = Request::getString('page');
}
else if ($targetType=='pageref') {
	$targetId = Request::getString('pageref');
}
else if ($targetType=='file') {
	$targetId = Request::getString('file');
}
else if ($targetType=='email') {
	$targetValue = Request::getString('email');
}


$sql="update hierarchy_item set".
" title=".Database::text($title).
",alternative=".Database::text($alternative).
",target_type=".Database::text($targetType).
",target_id=".$targetId.
",target_value=".Database::text($targetValue).
",target=".Database::text($target).
",hidden=".Database::boolean($hidden).
" where id=".$id;
Database::update($sql);

// Mark hierarchy as changed
$sql="update hierarchy set changed=now() where id=".$hierarchyId;
Database::update($sql);


InternalSession::setToolSessionVar('pages','updateHier',true);
Response::redirect($return);
?>