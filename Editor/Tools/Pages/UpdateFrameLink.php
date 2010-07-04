<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

$id = requestPostNumber('id',0);
$frame = requestPostNumber('frame',0);
$position = requestPostText('position');

$title = requestPostText('title');
$alternative = requestPostText('alternative');

$targetId=0;
$targetValue='';
$targetType=requestPostText('type');
if ($targetType=='url') {
	$targetValue = requestPostText('url');
}
else if ($targetType=='page') {
	$targetId = requestPostText('page');
}
else if ($targetType=='file') {
	$targetId = requestPostText('file');
}
else if ($targetType=='email') {
	$targetValue = requestPostText('email');
}

$sql="update frame_link set title=".Database::text($title).
",alternative=".Database::text($alternative).
",target_type=".Database::text($targetType).
",target_id=".$targetId.
",target_value=".Database::text($targetValue).
" where id = ".$id;
Database::update($sql);

redirect('EditFrameLinks.php?id='.$frame.'&position='.$position);
?>