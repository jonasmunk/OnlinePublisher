<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Part.php';
require_once '../../Include/XmlWebGui.php';

$pageId = getPageId();
$id = requestPostNumber('id');

$sql="select * from part where id=".$id;
if ($row = Database::selectFirst($sql)) {
	$part = Part::load($row['type'],$id);
	$part -> update();
}

$sql="update page set".
" changed=now()".
" where id=".$pageId;
Database::update($sql);

redirect('Editor.php?selectedSection=0');
?>