<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

$frame = requestPostNumber('frame',0);
$title = requestPostText('title');
$sortby=requestPostText('sortby');
$sortdir=requestPostText('sortdir');
$maxitems=requestPostNumber('maxitems',0);
$timetype=requestPostText('timetype');
if ($timetype=='hours' || $timetype=='days' || $timetype=='weeks' || $timetype=='months' || $timetype=='years') {
	$timecount=requestPostNumber('timecount',0);
}
else {
	$timecount=0;
}

$startdate = NULL;
$enddate = NULL;
if ($timetype=='interval') {
	$startdate = requestPostDateTime('startdate');
	$enddate = requestPostDateTime('enddate');
}

$sql="select max(`index`) as `index` from frame_newsblock where frame_id=".$frame;
$result = Database::select($sql);
if ($row = Database::next($result)) {
	$index=$row['index']+1;
}
else {
	$index=1;
}
Database::free($result);

$sql="insert into frame_newsblock (frame_id,title,`index`,sortby,sortdir,maxitems,timetype,timecount,startdate,enddate) values (".
$frame.
",".Database::text($title).
",".$index.
",".Database::text($sortby).
",".Database::text($sortdir).
",".$maxitems.
",".Database::text($timetype).
",".$timecount.
",".sqlTimestamp($startdate).
",".sqlTimestamp($enddate).
")";
$newId=Database::insert($sql);


redirect('FrameNewsblockProperties.php?id='.$newId);
?>