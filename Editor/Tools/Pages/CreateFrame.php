<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';

$name = Request::getString('name');
$title = Request::getString('title');
$hierarchy = Request::getInt('hierarchy',0);

$sql="insert into frame (name,title,hierarchy_id) values (".
Database::text($name).",".Database::text($title).",".$hierarchy.")";
$id=Database::insert($sql);

redirect('EditFrame.php?id='.$id);
?>