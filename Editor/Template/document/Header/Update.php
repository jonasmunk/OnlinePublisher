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

$pageId = getPageId();
$id = getDocumentSection();
$text = requestPostText('text');
$left = requestPostText('left');
$right = requestPostText('right');
$top = requestPostText('top');
$bottom = requestPostText('bottom');
$fontSize = requestPostText('fontSize');
$fontFamily = requestPostText('fontFamily');
$textAlign = requestPostText('textAlign');
$lineHeight = requestPostText('lineHeight');
$fontWeight = requestPostText('fontWeight');
$level = requestPostText('level');
$color = requestPostText('color');
$fontStyle = requestPostText('fontStyle');
$wordSpacing = requestPostText('wordSpacing');
$letterSpacing = requestPostText('letterSpacing');
$textDecoration = requestPostText('textDecoration');
$textIndent = requestPostText('textIndent');
$textTransform = requestPostText('textTransform');
$fontVariant = requestPostText('fontVariant');


$sql="update document_header set".
" text=".Database::text($text).
" ,level=".$level.
" ,fontsize=".Database::text($fontSize).
" ,fontfamily=".Database::text($fontFamily).
" ,textalign=".Database::text($textAlign).
" ,lineheight=".Database::text($lineHeight).
" ,fontweight=".Database::text($fontWeight).
" ,color=".Database::text($color).
" ,fontstyle=".Database::text($fontStyle).
" ,wordspacing=".Database::text($wordSpacing).
" ,letterspacing=".Database::text($letterSpacing).
" ,textdecoration=".Database::text($textDecoration).
" ,textindent=".Database::text($textIndent).
" ,texttransform=".Database::text($textTransform).
" ,fontvariant=".Database::text($fontVariant).
" where section_id=".$id;
Database::update($sql);

$sql="update document_section set".
" `left`=".Database::text($left).
",`right`=".Database::text($right).
",`top`=".Database::text($top).
",`bottom`=".Database::text($bottom).
" where id=".$id;
Database::update($sql);

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);


redirect('../Editor.php?section=');
?>