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
$color = requestPostText('color');
$fontStyle = requestPostText('fontStyle');
$wordSpacing = requestPostText('wordSpacing');
$letterSpacing = requestPostText('letterSpacing');
$textDecoration = requestPostText('textDecoration');
$textIndent = requestPostText('textIndent');
$textTransform = requestPostText('textTransform');
$fontVariant = requestPostText('fontVariant');
$imageId = requestPostNumber('imageId',0);
$imageFloat = requestPostText('imageFloat');

$sql="update document_text set".
" text=".sqlText($text).
" ,fontsize=".sqlText($fontSize).
" ,fontfamily=".sqlText($fontFamily).
" ,textalign=".sqlText($textAlign).
" ,lineheight=".sqlText($lineHeight).
" ,fontweight=".sqlText($fontWeight).
" ,color=".sqlText($color).
" ,fontstyle=".sqlText($fontStyle).
" ,wordspacing=".sqlText($wordSpacing).
" ,letterspacing=".sqlText($letterSpacing).
" ,textdecoration=".sqlText($textDecoration).
" ,textindent=".sqlText($textIndent).
" ,texttransform=".sqlText($textTransform).
" ,fontvariant=".sqlText($fontVariant).
" ,image_id=".$imageId.
" ,imagefloat=".sqlText($imageFloat).
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