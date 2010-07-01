<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Authentication
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$id = getPageId();
$title = requestPostText('title');


$sql="update authentication set".
" title=".sqlText($title).
" where page_id=".$id;
Database::update($sql);

$sql="update page set".
" changed=now(),dynamic=1".
" where id=".$id;
Database::update($sql);

redirect('Editor.php');
?>