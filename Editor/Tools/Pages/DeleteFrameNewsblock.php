<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

$id = requestGetNumber('id',0);


// Load info about item
$sql="select * from frame_newsblock where id=".$id;
$row = Database::selectFirst($sql);
$frame=$row['frame_id'];

// Delete item
$sql="delete from frame_newsblock where id=".$id;
Database::delete($sql);

// Fix indexes
$sql="select id from frame_newsblock where frame_id=".$frame." order by `index`";
$result = Database::select($sql);
$index=1;
while ($row = Database::next($result)) {
	$sql="update frame_newsblock set `index`=".$index." where id=".$row['id'];
	Database::update($sql);
	$index++;
}
Database::free($result);

$sql="delete from frame_newsblock_newsgroup where frame_newsblock_id=".$id;
Database::delete($sql);
	

redirect('FrameNews.php?id='.$frame);

?>