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
$title = Request::getString('title');
$sortby = Request::getString('sortby');
$sortdir = Request::getString('sortdir');
$maxitems = Request::getInt('maxitems',0);
$timetype = Request::getString('timetype');
if ($timetype=='hours' || $timetype=='days' || $timetype=='weeks' || $timetype=='months' || $timetype=='years') {
	$timecount = Request::getInt('timecount',0);
}
else {
	$timecount=0;
}

$startdate = NULL;
$enddate = NULL;
if ($timetype=='interval') {
	$startdate = Request::getDateTime('startdate');
	$enddate = Request::getDateTime('enddate');
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
",".Database::datetime($startdate).
",".Database::datetime($enddate).
")";
$newId=Database::insert($sql);


redirect('FrameNewsblockProperties.php?id='.$newId);
?>