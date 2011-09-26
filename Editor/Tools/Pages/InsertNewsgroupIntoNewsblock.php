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
$newsgroups = Request::getArray('newsgroups');

for ($i=0;$i<count($newsgroups);$i++) {
	$sql="insert into frame_newsblock_newsgroup (frame_newsblock_id, newsgroup_id)".
	" values (".$id.",".$newsgroups[$i].")";
	Database::insert($sql);
}

Response::redirect('FrameNewsblockNewsgroups.php?id='.$id);
?>