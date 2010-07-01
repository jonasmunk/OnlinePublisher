<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Include/Functions.php';
require_once '../../../Include/XmlWebGui.php';
require_once '../Functions.php';

$id = getDocumentSection();
$pageId = getPageId();
$data = requestPostText('data');
$left = requestPostText('left');
$right = requestPostText('right');
$top = requestPostText('top');
$bottom = requestPostText('bottom');


$sql="update document_richtext set".
" data=".sqlText($data).
" where section_id=".$id;
Database::update($sql);

$sql="update document_section set".
" `left`=".sqlText($left).
",`right`=".sqlText($right).
",`top`=".sqlText($top).
",`bottom`=".sqlText($bottom).
" where id=".$id;
Database::update($sql);

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);


redirect('../Editor.php?section=');
?>