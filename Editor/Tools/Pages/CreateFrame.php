<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

$name = requestPostText('name');
$title = requestPostText('title');
$hierarchy = requestPostNumber('hierarchy',0);

$sql="insert into frame (name,title,hierarchy_id) values (".
sqlText($name).",".sqlText($title).",".$hierarchy.")";
$id=Database::insert($sql);

redirect('EditFrame.php?id='.$id);
?>