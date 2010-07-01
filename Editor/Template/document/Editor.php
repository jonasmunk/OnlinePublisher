<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Part.php';
require_once '../../Include/Session.php';
require_once '../../Classes/PartContext.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

if (Request::getBoolean('toggleLayoutMode')) {
	setDocumentLayoutMode(!getDocumentLayoutMode());
}
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
<script type="text/javascript">
controller.context='<?=$baseUrl?>';
<?
$parts = Part::getParts();
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
	if (requestGetNumber('row',0)>0) {
		setDocumentScroll('row'.requestGetNumber('row',0));
	}
}
if (requestGetExists('section')) {
	setDocumentSection(requestGetNumber('section',0));
	setDocumentColumn(0);
	setDocumentRow(0);
	if (requestGetNumber('section',0)>0) {
		setDocumentScroll('section'.requestGetNumber('section',0));
	}
}
if (requestGetExists('column')) {
	setDocumentColumn(requestGetNumber('column',0));
	setDocumentSection(0);
	if (requestGetNumber('column',0)>0) {
		setDocumentScroll('column'.requestGetNumber('column',0));
	}
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
<table border="0" width="100%" cellpadding="0" cellspacing="0" id="bottom">
<tr><td class="rowButtonCell">
<?
if (getDocumentLayoutMode()) {
	echo '<a href="AddRow.php?index='.($lastRowIndex+1).'"><img src="Graphics/Add.gif" width="14" height="14" border="0" class="Minicon"/></a>';
}
if (getDocumentScroll()!='') {
	echo '<script>n2i.scrollTo("'.getDocumentScroll().'");</script>';
}
?>
</td></tr></table>
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
		if (getDocumentLayoutMode()) {
			echo '<table border="0" width="100%" cellpadding="0" cellspacing="0" id="row'.$row['id'].'">'.
			'<tr>'.
			'<td class="rowButtonCell">'.
			'<a href="AddRow.php?index='.$row['index'].'"><img src="Graphics/Add.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
			'<a href="MoveRow.php?row='.$row['id'].'&dir=-1"><img src="Graphics/Up.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
			'<br/>'.
			'<a href="DeleteRow.php?row='.$row['id'].'" onclick="return confirm(\'Er du sikker p\u00e5 at du vil slette r\u00e6kken?\');"><img src="Graphics/Delete.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
			'<a href="MoveRow.php?row='.$row['id'].'&dir=1"><img src="Graphics/Down.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
			'</td>'.
			'<td valign="top" height="100%">';
			displayColumns($row['id'],$row['index']);
			echo '</td></tr></table>';
		}
		else {
			echo '<table border="0" width="100%" cellpadding="0" cellspacing="0" id="row'.$row['id'].'">'.
			'<tr>'.
			'<td valign="top" height="100%">'.			
			'<table border="0" width="100%" cellpadding="0" cellspacing="0">'.
			'<tr>';
			displayColumns($row['id'],$row['index']);
			echo '</td></tr></table>';
		}
		$lastIndex = $row['index'];
	}
	Database::free($result);
	return $lastIndex;
}

function displayColumns($rowId,$rowIndex) {
	$buttonData='';
	if (getDocumentLayoutMode()) {
		$buttonData.='<tr>';
	}
	$columnData='<tr>';
	$lastIndex = 0;
	$pageId = getPageId();
	$selectedColumn = getDocumentColumn();
	$sql="select * from document_column where page_id=".$pageId." and row_id=".$rowId." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		if (getDocumentLayoutMode()) {
			$buttonData.='<td class="colButtonCell">'.
			'<a href="AddColumn.php?index='.$row['index'].'&row='.$rowId.'"><img src="Graphics/Add.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
			'</td>'.
			'<td align="center">'.
			'<div style="width: 70px;">'.
			'<a href="MoveColumn.php?column='.$row['id'].'&dir=-1"><img src="Graphics/Left.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
			($selectedColumn==$row['id']
			? '<a href="Editor.php?column=0"><img src="Graphics/Close.gif" width="14" height="14" border="0" class="Minicon"/></a>'
			: '<a href="Editor.php?column='.$row['id'].'"><img src="Graphics/Edit.gif" width="14" height="14" border="0" class="Minicon"/></a>'
			).
			//'<a href="DeleteColumn.php?column='.$row['id'].'"><img src="Graphics/Delete.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
			'<a href="MoveColumn.php?column='.$row['id'].'&dir=1"><img src="Graphics/Right.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
			'</div>'.
			'</td>';
			$columnData.='<td></td>';
		}
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
		$columnData.='<td class="column'.($selectedColumn==$row['id'] ? 'Selected' : (getDocumentLayoutMode() ? 'Design' : '')).'" id="column'.$row['id'].'"'.$columnWidth.' onmouseover="controller.columnOver(this)" onmouseout="controller.columnOut(this)"  oncontextmenu="controller.showColumnMenu(this,event,'.$row['id'].','.$row['index'].','.$rowId.','.$rowIndex.');return false;">';
		$columnData.=displaySections($row['id'],$row['index'],$rowId,$rowIndex).
		'<div style="width:1px;height:1px;"></div>'.
		'</td>';
		$lastIndex = $row['index'];
	}
	Database::free($result);
	if (getDocumentLayoutMode()) {
		$buttonData.='<td class="colButtonCell">'.
		'<a href="AddColumn.php?index='.($lastIndex+1).'&row='.$rowId.'"><img src="Graphics/Add.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
		'</td></tr>';
		$columnData.='<td></td>';
	}
	echo $buttonData.$columnData;
	/*echo '<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">'.
	$buttonData.$columnData.
	'</table>';*/
	return $lastIndex;
}

function displaySections($columnId,$columnIndex,$rowId,$rowIndex) {
	$selected = getDocumentSection();
	$lastIndex=0;
	$output='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
	
	$sql="select document_section.*,part.type as part_type from document_section left join part on document_section.part_id=part.id where column_id=".$columnId." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$style=buildSectionStyle($row);
		if ($row['id']==$selected) {
			$output.=
			'<tr id="section'.$row['id'].'">'.
			//'<td class="secButtonCell" id="section'.$row['id'].'" nowrap="nowrap">'.
			//'<a href="Editor.php?section=" title="Luk afsnittet uden at gemme ændriger"><img src="Graphics/Close.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
			//'<a href="javascript: saveSection();" title="Gem ændringer i afsnittet" style="cursor: pointer;"><img src="Graphics/Save.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
			//'<div style="width: 34px;"/>'.
			//'</td>'.
			sectionEditor($row['id'],$row['type'],$style,$row['part_id'],$row['part_type'],$row);
		}
		else {
			$output.='<tr id="section'.$row['id'].'" onmouseover="controller.sectionOver(this,'.$row['id'].','.$columnId.','.$row['index'].')" onmouseout="controller.sectionOut(this,event)">';
			if (getDocumentLayoutMode()) {
				$output.=
				'<td class="secButtonCell" nowrap="nowrap">'.
				'<div style="width: 34px;">'.
				'<a href="MoveSection.php?section='.$row['id'].'&dir=-1"><img src="Graphics/Up.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
				'<a href="MoveSection.php?section='.$row['id'].'&dir=1"><img src="Graphics/Down.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
				'</div>'.
				'</td>';
/*			}
			else {
				if ($selected==0) {
					$output.=
					'<a onclick="controller.showNewPartMenu(this,event,'.$columnId.','.$row['index'].'); return false" title="Opret et nyt afsnit" style="cursor: pointer;"><img src="Graphics/Add.gif" width="14" height="14" border="0" class="Minicon"/></a>'.
					'<a href="Editor.php?section='.$row['id'].'" title="Rediger afsnittet"><img src="Graphics/Edit.gif" width="14" height="14" border="0" class="Minicon"/></a>';
				} else {
					$output.=
					'<img src="Graphics/Add.gif" width="14" height="14" border="0" class="Minicon Disabled"/>'.
					'<img src="Graphics/Edit.gif" width="14" height="14" border="0" class="Minicon Disabled"/>';
				}
			}*/
			}
			$output.=displaySection($row['id'],$row['type'],$row['index'],$style,$row['part_id'],$row['part_type'],$columnId,$columnIndex,$rowId,$rowIndex);
		}
		$output.='</tr>';
		$lastIndex = $row['index'];
	}
	Database::free($result);
	if (!getDocumentLayoutMode()) {
		if ($selected==0) {
			$output.='<tr><td><a onclick="controller.showNewPartMenu(this,event,'.$columnId.','.($lastIndex+1).'); return false" title="Opret et nyt afsnit" style="cursor: pointer;"><img src="Graphics/Add.gif" width="14" height="14" border="0" class="Minicon"/></a></td></tr>';
		} else {
			$output.='<tr><td><img src="Graphics/Add.gif" width="14" height="14" border="0" class="Minicon Disabled"/>';
		}
	}
	$output.='</table>';
	return $output;
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
		return ''.displayPart($partId,$partType,$sectionIndex,$sectionStyle,$sectionId,$columnId,$columnIndex,$rowId,$rowIndex).'</td>';
	}
}

function displayPart($partId,$partType,$sectionIndex,$sectionStyle,$sectionId,$columnId,$columnIndex,$rowId,$rowIndex) {
	global $partContext;
	$part = Part::load($partType,$partId);
	return 
		'<td style="'.$sectionStyle.'" class="part_section_'.$partType.' '.$part->getSectionClass().' sectionNotSelected"  oncontextmenu="controller.showSectionMenu(this,event,'.$sectionId.','.$sectionIndex.','.$columnId.','.$columnIndex.','.$rowId.','.$rowIndex.'); return false;">'.
		$part->display($partContext).
		'</td>';
}

function sectionEditor($sectionId,$type,$sectionStyle,$partId,$partType,$row) {
	global $baseUrl, $design;
	if ($type=='part') {
		return partEditor($partId,$partType,$sectionId,$sectionStyle,$row);
	}
}

function partEditor($partId,$partType,$sectionId,$sectionStyle,$row) {
	global $partContext;
	setPartContextSessionVar('part.id',$partId);
	setPartContextSessionVar('part.type',$partType);
	setPartContextSessionVar('form.path','parent.Frame.EditorFrame.getDocument().forms.PartForm');
	$part = Part::load($partType,$partId);
	$data = 
	'<td style="'.$sectionStyle.'" id="selectedSectionTD" class="part_section_'.$partType.' '.$part->getSectionClass().' sectionSelected">'.
	'<form name="PartForm" action="UpdatePart.php" method="post">'.
	'<input type="hidden" name="id" value="'.$partId.'"/>'.
	'<input type="hidden" name="section" value="'.$sectionId.'"/>'.
	'<input type="hidden" name="left" value="'.encodeXML($row['left']).'"/>'.
	'<input type="hidden" name="right" value="'.encodeXML($row['right']).'"/>'.
	'<input type="hidden" name="bottom" value="'.encodeXML($row['bottom']).'"/>'.
	'<input type="hidden" name="top" value="'.encodeXML($row['top']).'"/>'.
	'<input type="hidden" name="width" value="'.encodeXML($row['width']).'"/>'.
	'<input type="hidden" name="float" value="'.encodeXML($row['float']).'"/>'.
	$part->editor($partContext).
	'</form></td>'.
	'<script type="text/javascript">'.
	'parent.parent.Toolbar.location=\'PartToolbar.php?section='.$sectionId.'&amp;\'+Math.random();'.
	'function saveSection() {
		document.forms.PartForm.submit();
	}'.
	'</script>';
	return $data;
}
?>