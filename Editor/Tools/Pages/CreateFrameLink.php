<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';

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