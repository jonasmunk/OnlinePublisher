<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

$id=requestGetNumber('id',0);


$sql='delete from frame where id='.$id;
Database::delete($sql);

$sql='delete from frame_link where frame_id='.$id;
Database::delete($sql);

$sql="select * from frame_newsblock where frame_id=".$id;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql='delete from frame_newsblock_newsgroup where frame_newsblock_id='.$row['id'];
	Database::delete($sql);
}
Database::free($result);

$sql="delete from frame_newsblock where frame_id=".$id;
Database::delete($sql);


redirect('Frames.php');
?>