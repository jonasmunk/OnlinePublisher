<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Parts/LegacyPartController.php';
require_once '../../Include/Session.php';
require_once '../../Classes/PartContext.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

header('Content-Type: text/html; charset=iso-8859-1');

$design = getPageDesign();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Editor</title>
<link rel="stylesheet" type="text/css" href="../../../In2iGui/bin/minimized.css" />
<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>style/<?=$design?>/editors/document.css" />
<link rel="stylesheet" type="text/css" href="Stylesheet.css" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="StylesheetIE.css" />
<![endif]-->
<? if (Request::getBoolean('dev')) { ?>
<script type="text/javascript" src="../../../In2iGui/bin/combined.js" charset="UTF-8"></script>
<? } else { ?>
<script type="text/javascript" src="../../../In2iGui/bin/minimized.js" charset="UTF-8"></script>
<? } ?>
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="../../../In2iGui/css/msie8.css"> </link>
<![endif]-->
<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="../../../In2iGui/css/msie6.css"> </link>
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="../../../In2iGui/css/msie7.css"> </link>
<![endif]-->
<script type="text/javascript">
In2iGui.context='../../../';
</script>
<script type="text/javascript" src="js/Controller.js"></script>
<script type="text/javascript" src="../../Services/Parts/js/parts.js"></script>
<script type="text/javascript" src="<?=$baseUrl?>style/basic/js/OnlinePublisher.js"></script>
<script type="text/javascript">
controller.context='<?=$baseUrl?>';
<?
$parts = LegacyPartController::getParts();
foreach ($parts as $part => $info) {
?>
controller.parts.push({value:'<?=$part?>',title:'<?=$info['name']?>'});
<?
}
?>
</script>
<?
if (requestGetExists('row')) {
	setDocumentRow(requestGetNumber('row',0));
	setDocumentColumn(0);
	setDocumentSection(0);
}
if (requestGetExists('section')) {
	setDocumentSection(requestGetNumber('section',0));
	setDocumentColumn(0);
	setDocumentRow(0);
}
if (requestGetExists('column')) {
	setDocumentColumn(requestGetNumber('column',0));
	setDocumentSection(0);
}
if (getDocumentColumn()>0) {
?>
<script>parent.parent.Toolbar.location='ColumnToolbar.php?'+Math.random();</script>
<?
}
else if (getDocumentSection()==0) {
?>
<script>parent.parent.Toolbar.location='Toolbar.php?'+Math.random();</script>
<?
}
else if (getDocumentSection()!=0) {
	echo '<script type="text/javascript">controller.activeSection='.getDocumentSection().';</script>';
}
?>
</head>
<body>
<div class="editor_body">
<form action="AddSection.php" name="SectionAdder" method="get" style="margin: 0px;">
<input type="hidden" name="type"/>
<input type="hidden" name="part"/>
<input type="hidden" name="column"/>
<input type="hidden" name="index"/>
</form>
<?
$partContext = buildPartContext();
$lastRowIndex=displayRows();
?>
</div>
</body>
</html>

<?

function buildPartContext() {
	$context = new PartContext();
	$pageId = getPageId();
	
	//////////////////// Find links ///////////////////
	$sql="select * from link where page_id=".$pageId." and source_type='text'";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$context -> addDisplayLink(escapeHTML($row['source_text']),'Toolbar.php?link=true&amp;id='.$row['id'],'Toolbar','common',$row['alternative']);
	}
	Database::free($result);
	
	/////////////////// Return ///////////////////////
	return $context;
}

function displayRows() {
	$selected = getDocumentRow();
	$pageId = getPageId();
	$lastIndex = 0;
	$sql="select * from document_row where page_id=".$pageId." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		echo '<table border="0" width="100%" cellpadding="0" cellspacing="0" id="row'.$row['id'].'"><tr>';
		displayColumns($row['id'],$row['index']);
		echo '</tr></table>';
		echo "\n";
		$lastIndex = $row['index'];
	}
	Database::free($result);
	return $lastIndex;
}

function displayColumns($rowId,$rowIndex) {
	$lastIndex = 0;
	$pageId = getPageId();
	$selectedColumn = getDocumentColumn();
	$sql="select * from document_column where page_id=".$pageId." and row_id=".$rowId." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$columnWidth=$row['width'];
		if ($columnWidth!='') {
			if ($columnWidth=='min') {
				$columnWidth=' width="1%"';
			}
			elseif ($columnWidth=='max') {
				$columnWidth=' width="100%"';
			}
			else {
				$columnWidth=' width="'.$columnWidth.'"';
			}
		}
		echo "\n";
		echo '<td class="column'.($selectedColumn==$row['id'] ? 'Selected' : '').'" id="column'.$row['id'].'"'.$columnWidth.' onmouseover="controller.columnOver(this)" onmouseout="controller.columnOut(this)"  oncontextmenu="controller.showColumnMenu(this,event,'.$row['id'].','.$row['index'].','.$rowId.','.$rowIndex.');return false;">';
		displaySections($row['id'],$row['index'],$rowId,$rowIndex);
		echo '</td>';
		echo "\n";
		$lastIndex = $row['index'];
	}
	Database::free($result);
	return $lastIndex;
}

function displaySections($columnId,$columnIndex,$rowId,$rowIndex) {
	$selected = getDocumentSection();
	$lastIndex=0;
	
	$sql="select document_section.*,part.type as part_type from document_section left join part on document_section.part_id=part.id where column_id=".$columnId." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$style=buildSectionStyle($row);
		if ($row['id']==$selected) {
			echo '<div id="section'.$row['id'].'">';
			sectionEditor($row['id'],$row['type'],$style,$row['part_id'],$row['part_type'],$row);
		}
		else {
			echo '<div id="section'.$row['id'].'">';
			displaySection($row['id'],$row['type'],$row['index'],$style,$row['part_id'],$row['part_type'],$columnId,$columnIndex,$rowId,$rowIndex);
		}
		echo '</div>';
		$lastIndex = $row['index'];
	}
	Database::free($result);
	if ($selected==0) {
		echo '<div><a onclick="controller.showNewPartMenu(this,event,'.$columnId.','.($lastIndex+1).'); return false" title="Opret et nyt afsnit" style="cursor: pointer;"><img src="Graphics/Add.gif" width="14" height="14" border="0" class="Minicon"/></a></div>';
	} else {
		echo '<div><img src="Graphics/Add.gif" width="14" height="14" border="0" class="Minicon Disabled"/></div>';
	}
}

function buildSectionStyle(&$row) {
	$style='';
	if ($row['left']!='') $style.='padding-left: '.$row['left'].';';
	if ($row['right']!='') $style.='padding-right: '.$row['right'].';';
	if ($row['top']!='') $style.='padding-top: '.$row['top'].';';
	if ($row['bottom']!='') $style.='padding-bottom: '.$row['bottom'].';';
	return $style;
}

function displaySection($sectionId,$type,$sectionIndex,$sectionStyle,$partId,$partType,$columnId,$columnIndex,$rowId,$rowIndex) {
	if ($type=='part') {
		displayPart($partId,$partType,$sectionIndex,$sectionStyle,$sectionId,$columnId,$columnIndex,$rowId,$rowIndex);
	}
}

function displayPart($partId,$partType,$sectionIndex,$sectionStyle,$sectionId,$columnId,$columnIndex,$rowId,$rowIndex) {
	global $partContext;
	$part = LegacyPartController::load($partType,$partId);
	echo 
		'<div style="'.$sectionStyle.'" class="part_section_'.$partType.' '.$part->getSectionClass().' section"  oncontextmenu="controller.showSectionMenu(this,event,'.$sectionId.','.$sectionIndex.','.$columnId.','.$columnIndex.','.$rowId.','.$rowIndex.'); return false;" onmouseover="controller.sectionOver(this,'.$sectionId.','.$columnId.','.$sectionIndex.')" onmouseout="controller.sectionOut(this,event)">'.
		$part->display($partContext).
		'</div>';
}

function sectionEditor($sectionId,$type,$sectionStyle,$partId,$partType,$row) {
	global $baseUrl, $design;
	if ($type=='part') {
		partEditor($partId,$partType,$sectionId,$sectionStyle,$row);
	}
}

function partEditor($partId,$partType,$sectionId,$sectionStyle,$row) {
	global $partContext;
	setPartContextSessionVar('part.id',$partId);
	setPartContextSessionVar('part.type',$partType);
	setPartContextSessionVar('form.path','parent.Frame.EditorFrame.getDocument().forms.PartForm');
	$part = LegacyPartController::load($partType,$partId);
	echo
	'<div style="'.$sectionStyle.'" id="selectedSectionTD" class="part_section_'.$partType.' '.$part->getSectionClass().' section_selected">'.
	'<form name="PartForm" action="UpdatePart.php" method="post">'.
	'<input type="hidden" name="id" value="'.$partId.'"/>'.
	'<input type="hidden" name="section" value="'.$sectionId.'"/>'.
	'<input type="hidden" name="left" value="'.StringUtils::escapeXML($row['left']).'"/>'.
	'<input type="hidden" name="right" value="'.StringUtils::escapeXML($row['right']).'"/>'.
	'<input type="hidden" name="bottom" value="'.StringUtils::escapeXML($row['bottom']).'"/>'.
	'<input type="hidden" name="top" value="'.StringUtils::escapeXML($row['top']).'"/>'.
	'<input type="hidden" name="width" value="'.StringUtils::escapeXML($row['width']).'"/>'.
	'<input type="hidden" name="float" value="'.StringUtils::escapeXML($row['float']).'"/>'.
	$part->editor($partContext).
	'</form></div>'.
	'<script type="text/javascript">'.
	'parent.parent.Toolbar.location=\'PartToolbar.php?section='.$sectionId.'&amp;\'+Math.random();'.
	'function saveSection() {
		document.forms.PartForm.submit();
	}'.
	'</script>';
}
?>