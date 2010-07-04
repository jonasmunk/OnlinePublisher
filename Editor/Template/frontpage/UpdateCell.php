<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$pageId = getPageId();
$id = requestPostNumber('id');
$rows = requestPostNumber('rows',1);
$columns = requestPostNumber('columns',1);
$title = requestPostText('title');
$type = requestPostText('type');
$width = requestPostText('width');
$height = requestPostText('height');

$sql="update frontpage_cell set".
" rows=".$rows.
",`columns`=".$columns.
",`title`=".Database::text($title).
",`type`=".Database::text($type).
",`width`=".Database::text($width).
",`height`=".Database::text($height).
" where id=".$id;
Database::update($sql);

$sql="update page set".
" changed=now()".
" where id=".$pageId;
Database::update($sql);

redirect('Editor.php?selectedCell=0');
?>