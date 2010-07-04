<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

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


$sql="select max(`index`) as `index` from frame_link where frame_id=".$frame." and position='".$position."'";
$result = Database::select($sql);
if ($row = Database::next($result)) {
	$index=$row['index']+1;
}
else {
	$index=1;
}
Database::free($result);

$sql="insert into frame_link (frame_id,`position`,title,alternative,`index`,target_type,target_id,target_value) values (".
$frame.
",".Database::text($position).
",".Database::text($title).
",".Database::text($alternative).
",".$index.
",".Database::text($targetType).
",".$targetId.
",".Database::text($targetValue).
")";
Database::insert($sql);


redirect('EditFrameLinks.php?id='.$frame.'&position='.$position);
?>