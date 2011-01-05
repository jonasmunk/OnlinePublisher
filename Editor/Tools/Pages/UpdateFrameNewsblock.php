<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Request.php';

$id=Request::getInt('id',0);
$title=Request::getString('title');
$sortby=Request::getString('sortby');
$sortdir=Request::getString('sortdir');
$maxitems=Request::getInt('maxitems',0);
$timetype=Request::getString('timetype');
if ($timetype=='hours' || $timetype=='days' || $timetype=='weeks' || $timetype=='months' || $timetype=='years') {
	$timecount=Request::getInt('timecount',0);
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

$sql="update frame_newsblock set title=".Database::text($title).
",sortby=".Database::text($sortby).
",sortdir=".Database::text($sortdir).
",maxitems=".$maxitems.
",timetype=".Database::text($timetype).
",timecount=".$timecount.
",startdate=".Database::datetime($startdate).
",enddate=".Database::datetime($enddate).
" where id=".$id;

Database::update($sql);
//echo $sql;
Response::redirect('FrameNewsblockProperties.php?id='.$id);
?>