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

// Load info about item
$sql="select * from frame_link where id=".$id;
$row = Database::selectFirst($sql);
$frame=$row['frame_id'];
$position=$row['position'];

// Delete item
$sql="delete from frame_link where id=".$id;
Database::delete($sql);

// Fix indexes
$sql="select id from frame_link where frame_id=".$frame." and position=".Database::text($position)." order by `index`";
$result = Database::select($sql);
$index=1;
while ($row = Database::next($result)) {
	$sql="update frame_link set `index`=".$index." where id=".$row['id'];
	Database::update($sql);
	$index++;
}
Database::free($result);
	

Response::redirect('EditFrameLinks.php?id='.$frame.'&position='.$position);
?>