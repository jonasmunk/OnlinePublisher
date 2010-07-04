<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

$id=requestPostNumber('id',0);
$title=requestPostText('title');
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

$sql="update frame_newsblock set title=".Database::text($title).
",sortby=".Database::text($sortby).
",sortdir=".Database::text($sortdir).
",maxitems=".$maxitems.
",timetype=".Database::text($timetype).
",timecount=".$timecount.
",startdate=".sqlTimestamp($startdate).
",enddate=".sqlTimestamp($enddate).
" where id=".$id;

Database::update($sql);
//echo $sql;
redirect('FrameNewsblockProperties.php?id='.$id);
?>