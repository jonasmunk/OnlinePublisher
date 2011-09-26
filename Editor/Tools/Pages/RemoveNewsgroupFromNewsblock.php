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



$sql='delete from frame_newsblock_newsgroup where id='.Request::getInt('id',0);
Database::delete($sql);

Response::redirect('FrameNewsblockNewsgroups.php?id='.Request::getInt('newsblock',0));
?>