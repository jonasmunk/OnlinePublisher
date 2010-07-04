<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.QuickTimePlayer
 */

require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$id = getPageId();
$title = requestPostText('title');
$text = requestPostText('text');
$file = requestPostNumber('file',0);
$width = requestPostNumber('width',0);
$height = requestPostNumber('height',0);

$sql="update quicktimeplayer set".
" title=".Database::text($title).
",text=".Database::text($text).
",file_id=".$file.
",width=".$width.
",height=".$height.
" where page_id=".$id;
Database::update($sql);

$sql="update page set".
" changed=now()".
" where id=".$id;
Database::update($sql);

redirect('Editor.php');
?>