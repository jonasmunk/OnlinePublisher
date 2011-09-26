<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/Request.php';

$id = Request::getInt('id',0);
$frame = Request::getInt('frame',0);
$position = Request::getString('position');

$title = Request::getString('title');
$alternative = Request::getString('alternative');

$targetId=0;
$targetValue='';
$targetType=Request::getString('type');
if ($targetType=='url') {
	$targetValue = Request::getString('url');
}
else if ($targetType=='page') {
	$targetId = Request::getString('page');
}
else if ($targetType=='file') {
	$targetId = Request::getString('file');
}
else if ($targetType=='email') {
	$targetValue = Request::getString('email');
}

$sql="update frame_link set title=".Database::text($title).
",alternative=".Database::text($alternative).
",target_type=".Database::text($targetType).
",target_id=".$targetId.
",target_value=".Database::text($targetValue).
" where id = ".$id;
Database::update($sql);

Response::redirect('EditFrameLinks.php?id='.$frame.'&position='.$position);
?>