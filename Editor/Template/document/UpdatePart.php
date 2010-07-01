<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Part.php';
require_once '../../Classes/Page.php';
require_once '../../Include/XmlWebGui.php';

$pageId = getPageId();
$id = requestPostNumber('id');
$section = requestPostNumber('section');
$top = requestPostText('top');
$left = requestPostText('left');
$bottom = requestPostText('bottom');
$right = requestPostText('right');
$float = requestPostText('float');
$width = requestPostText('width');


// update the section
$sql="update document_section set".
" `left`=".sqlText($left).
",`right`=".sqlText($right).
",`top`=".sqlText($top).
",`bottom`=".sqlText($bottom).
",`float`=".sqlText($float).
",`width`=".sqlText($width).
" where id=".$section;
Database::update($sql);

// Update the part
$sql="select * from part where id=".$id;
if ($row = Database::selectFirst($sql)) {
	$part = Part::load($row['type'],$id);
	$part -> update();
}

// Mark the page as changed
Page::markChanged(getPageId());


redirect('Editor.php?section=0');
?>