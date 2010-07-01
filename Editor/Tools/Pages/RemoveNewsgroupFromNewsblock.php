<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';



$sql='delete from frame_newsblock_newsgroup where id='.requestGetNumber('id',0);
Database::delete($sql);

redirect('FrameNewsblockNewsgroups.php?id='.requestGetNumber('newsblock',0));
?>