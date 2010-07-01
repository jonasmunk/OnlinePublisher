<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';
require_once '../../Classes/Part.php';
require_once '../../Classes/PartContext.php';

require_once 'Functions.php';

$selectedCell = getRequestTemplateSessionVar('frontpage','selectedCell','selectedCell',0);
$selectedSection = getRequestTemplateSessionVar('frontpage','selectedSection','selectedSection',0);
$layoutmode = getRequestTemplateSessionBool('frontpage','layoutmode','layoutmode',false);

if (!$selectedCell && !$selectedSection) {
	$onload = "parent.parent.Toolbar.location='Toolbar.php?'+Math.random();";
}
else {
	$onload = "";
}
$design=getPageDesign();

$partContext = new PartContext();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>style/<?=$design?>/editors/frontpage.css" />
<link rel="stylesheet" type="text/css" href="EditorStylesheet.css" />
<script language="JavaScript" src="../../../XmlWebGui/Scripts/In2iScripts.js"></script>
<script language="JavaScript" src="../../../XmlWebGui/Scripts/In2iRequest.js"></script>
<script language="JavaScript" src="../../../XmlWebGui/Scripts/In2iMenu.js"></script>
</head>
<body onload="<?=$onload?>">
<script>
var xwgPath='xmenu/';
var xwgIconset='';
</script>
<script language="JavaScript">
var menu = new In2iMenu;
<?
$parts = Part::getParts();
foreach ($parts as $part => $info) {
    echo 'menu.add(new In2iMenuItem("'.$info['name'].'",'.
    '"javascript: document.forms.SectionAdder.part.value=\''.$part.'\';'.
    'document.forms.SectionAdder.submit();",null,null));';
}
?>
document.write(menu);

var cellMenu = new In2iMenu;
cellMenu.add(new In2iMenuItem("Rediger celle","javascript: controller.editCell()",null,null));
cellMenu.add(new In2iMenuItem("Slet celle","javascript: controller.deleteCell()",null,null));
document.write(cellMenu);

var sectionMenu = new In2iMenu;
sectionMenu.add(new In2iMenuItem("Rediger afsnit","javascript: controller.editSection()",null,null));
sectionMenu.add(new In2iMenuItem("Slet afsnit","javascript: controller.deleteSection()",null,null));
sectionMenu.add(new In2iMenuItem("Flyt afsnit op","javascript: controller.moveSection(-1)",null,null));
sectionMenu.add(new In2iMenuItem("Flyt afsnit ned","javascript: controller.moveSection(1)",null,null));
sectionMenu.add(new In2iMenuSeparator());
sectionMenu.add(new In2iMenuItem("Rediger celle","javascript: controller.editCell()",null,null));
sectionMenu.add(new In2iMenuItem("Slet celle","javascript: controller.deleteCell()",null,null));
document.write(sectionMenu);

var controller = {};
controller.cellId=0;
controller.rowId=0;
controller.sectionId=0;

controller.sectionOver = function(cell) {
    cell.childNodes[1].style.borderColor='#abf';
}

controller.sectionOut = function(cell) {
    cell.childNodes[1].style.borderColor='';
}

controller.cellOver = function(cell) {
    cell.style.borderColor='#abf';
}

controller.cellOut = function(cell) {
    cell.style.borderColor='';
}

controller.showSectionMenu = function(element,event,sectionId,cellId) {
    this.cellId=cellId;
    this.sectionId=sectionId;
    in2iMenuHandler.showMenu(sectionMenu, element, null, event, true);
    N2i.Event.stop(event);
    return false
}

controller.showCellMenu = function(element,event,cellId,rowId) {
    this.cellId=cellId;
    this.rowId=rowId;
    in2iMenuHandler.showMenu(cellMenu, element, null, event, true);
    N2i.Event.stop(event);
    return false
}

controller.editCell = function() {
    document.location='Editor.php?selectedCell='+this.cellId;
}

controller.deleteCell = function() {
    if (confirm('Er du sikker på at du vil slette cellen?\nHandlingen kan ikke fortrydes!')) {
        document.location='DeleteCell.php?id='+this.cellId;
    }
}

controller.editSection = function() {
    document.location='Editor.php?selectedSection='+this.sectionId;
}

controller.deleteSection = function() {
    if (confirm('Er du sikker på at du vil slette afsnittet?\nHandlingen kan ikke fortrydes!')) {
        document.location='DeleteSection.php?id='+this.sectionId;
    }
}

controller.moveSection = function(dir) {
	document.location='MoveSection.php?section='+this.sectionId+'&dir='+dir;
}
</script>
<?
if ($layoutmode) {
	buildBodyLayout();
} else {
	buildBody();
}
?>
<form action="AddSection.php" name="SectionAdder" method="post">
<input type="hidden" name="part"/>
<input type="hidden" name="cell"/>
<input type="hidden" name="position"/>
</form>
</body>
</html>

<?

function buildBody() {
	echo '<table border="0" cellspacing="2" cellpadding="0" width="100%">';
	$maxRowPosition = buildRows(getPageId());
	echo '</table>';
}

function buildBodyLayout() {
	echo '<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr><td height="14" align="center">';
	echo buildIcon('Add','AddRow.php?position=1');
	echo '</td></tr>
	<tr><td>';

	echo '<table border="0" cellspacing="2" cellpadding="0" width="100%">';
	$maxRowPosition = buildRowsLayout(getPageId());
	echo '</table>';

	echo '</td><td width="14">'.buildIcon('Add','AddColumn.php').'</td></tr>
	<tr><td height="14" align="center">'.
	buildIcon('Add','AddRow.php?position='.($maxRowPosition+1)).
	'</td></tr>
	</table>';
}


function buildRows($pageId) {
	global $layoutmode;
	$columnCount = getColumnCount();
	$maxRowPosition = 1;
	$lastCellRowCount = 1;
	$sql = "select * from frontpage_row where page_id=".$pageId." order by position";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		echo '<tr>';
		$lastCellRowCount+= buildCells($pageId,$row['id'],$columnCount,$lastCellRowCount);
		echo'</tr>';
		$maxRowPosition = $row['position'];
	}
	Database::free($result);
	return $maxRowPosition;
}

function buildRowsLayout($pageId) {
	global $layoutmode;
	$columnCount = getColumnCount();
/*	echo '<tr><td></td>';
	for ($i=1;$i<=$columnCount;$i++) {
		echo '<td height="14" align="center">'.
		buildIcon('DeleteColumn','#').
		'</td>';
	}
	echo '</tr>';*/
	$maxRowPosition = 0;
	$lastCellRowCount = 1;
	$sql = "select * from frontpage_row where page_id=".$pageId." order by position";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		echo '<tr>';
		echo '<td width="14">'.
		buildIcon('Delete','DeleteRow.php?row='.$row['id']).
		'</td>';
		$lastCellRowCount+= buildCells($pageId,$row['id'],$columnCount,$lastCellRowCount);
		echo'</tr>';
		$maxRowPosition = $row['position'];
	}
	Database::free($result);
	return $maxRowPosition;
}

function buildCells($pageId,$rowId,$columnCount,$lastCellRowCount) {
	global $selectedCell,$layoutmode;
	$sql = "select * from frontpage_cell where page_id=".$pageId." and row_id=".$rowId." order by position";
	$result = Database::select($sql);
	$cols = 0;
	$maxRows = 1;
	while ($row = Database::next($result)) {
		$selected = $row['id']==$selectedCell;
		if ($row['type']=='box') {
			$class="Box";
		} else {
			$class="";
		}
		echo '<td colspan="'.$row['columns'].'" rowspan="'.$row['rows'].'"'.
		    (!$selected ?
		    ' onmouseover="controller.cellOver(this);" onmouseout="controller.cellOut(this);"'
		    : '').
		    ' oncontextmenu="return controller.showCellMenu(this,event,'.$row['id'].','.$rowId.');"'.
			($selected ? ' class="Cell'.$class.' CellSelected" id="selectedCellTD"' : ' class="Cell'.$class.'"').
			($row['width']!='' ? ' width="'.$row['width'].'"' : '').
			($row['height']!='' ? ' height="'.$row['height'].'"' : '').
			' valign="top">';
		if ($layoutmode || $selected) {
			echo '<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td valign="top" width="1%">';
			if ($selected) {
				echo buildIcon('Close','Editor.php?selectedCell=0');
			} else {
				echo buildIcon('Edit','Editor.php?selectedCell='.$row['id']);
			}
			echo '</td><td width="99%">';
		}
		if ($row['title']!='') {
			echo '<div class="Title">'.encodeXML($row['title']).'</div>';
		}
		if ($layoutmode) {
			echo 
			($selected
			    ? buildSelectedCellContentLayout($row)
			    : buildCellContentLayout($row)
			);
		}
		else {
			echo 
			($selected
			    ? buildSelectedCellContentLayout($row)
			    : buildCellContent($row)
			);
		}
		if ($layoutmode || $selected) {
			echo '</td></tr></table>';
		}
		echo '</td>';
		$cols+=$row['columns'];
		if ($row['rows']>$maxRows) $maxRows=$row['rows'];
	}
	Database::free($result);
	for ($i=$cols;$i<($columnCount-$lastCellRowCount+1);$i++) {
		echo '<td class="CellNonexisting">&nbsp;</td>';
	}
	return $maxRows;
}

function buildCellContent(&$cellRow) {
	global $selectedSection;
	$out='<table cellspacing="0" cellpadding="0" width="100%">';
	
	$maxPosition = 0;
	$sql = "select frontpage_section.*,part.type from frontpage_section,part where frontpage_section.part_id=part.id and cell_id=".$cellRow['id']." order by position";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$selected = ($row['id']==$selectedSection);
		if ($selected) {
			setPartContextSessionVar('toolbar.tab','');
			setPartContextSessionVar('part.id',$row['id']);
			setPartContextSessionVar('part.type',$row['type']);
			setPartContextSessionVar('delete.url','../../Template/frontpage/DeleteSection.php?id='.$row['id']);
			setPartContextSessionVar('delete.target','Editor');
			setPartContextSessionVar('form.path','parent.Frame.EditorFrame.getDocument().forms.PartForm');
			setPartContextSessionVar('cancel.url','../../Template/frontpage/Editor.php?selectedSection=0');
			setPartContextSessionVar('cancel.target','Editor');
			$out.='<tr><td width="1%" nowrap valign="top">'.
			buildIcon('Close','Editor.php?selectedSection=0').
			buildIcon('Save','javascript: document.forms.PartForm.submit();').
			'</td><td class="SectionSelected" id="selectedSection">'.
			editPart($row['type'],$row['part_id']).
			'<script type="text/javascript">'.
			'parent.parent.Toolbar.location=\'PartToolbar.php?section='.$row['id'].'&amp;\'+Math.random();'.
			'</script>'.
			'</td></tr>';
		}
		else {
			$out.=
			'<tr onmouseover="controller.sectionOver(this);"'.
			' onmouseout="controller.sectionOut(this);"'.
			' oncontextmenu="return controller.showSectionMenu(this,event,'.$row['id'].','.$row['cell_id'].');">'.
			'<td width="1%" nowrap valign="top">'.
			buildNewSectionButton($cellRow['id'],$row['position']).
			buildIcon('Edit','Editor.php?selectedSection='.$row['id']).
			'</td><td class="part-section-'.$row['type'].' Section">'.displayPart($row['type'],$row['part_id']).'</td></tr>';
		}
		$maxPosition = $row['position'];
	}
	Database::free($result);
	$out.=
	'<tr><td>'.
	buildNewSectionButton($cellRow['id'],$maxPosition+1).
	'</td></tr></table>';
	return $out;
}

function buildCellContentLayout(&$cellRow) {
	$out='<table cellspacing="0" cellpadding="0" width="100%">';
	$maxPosition = 0;
	$sql = "select frontpage_section.*,part.type from frontpage_section,part where frontpage_section.part_id=part.id and cell_id=".$cellRow['id']." order by position";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$out.='<tr><td>'.displayPart($row['type'],$row['part_id']).'</td></tr>';
		$maxPosition = $row['position'];
	}
	Database::free($result);
	$out.='</table>';
	return $out;
}

function buildSelectedCellContentLayout(&$cellRow) {
	$out='<table cellspacing="0" cellpadding="0" width="100%">';
	$maxPosition = 0;
	$sql = "select frontpage_section.*,part.type from frontpage_section,part where frontpage_section.part_id=part.id and cell_id=".$cellRow['id']." order by position";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$out.='<tr><td>'.displayPart($row['type'],$row['part_id']).'</td></tr>';
		$maxPosition = $row['position'];
	}
	Database::free($result);
	$out.='</table>';
	$out.=
	'<form name="CellFormula" method="post" action="UpdateCell.php">'.
	'<input type="hidden" name="id" value="'.$cellRow['id'].'"/>'.
	'<input type="hidden" name="rows" value="'.$cellRow['rows'].'"/>'.
	'<input type="hidden" name="columns" value="'.$cellRow['columns'].'"/>'.
	'<input type="hidden" name="title" value="'.encodeXML($cellRow['title']).'"/>'.
	'<input type="hidden" name="type" value="'.$cellRow['type'].'"/>'.
	'<input type="hidden" name="width" value="'.$cellRow['width'].'"/>'.
	'<input type="hidden" name="height" value="'.$cellRow['height'].'"/>'.
	'</form>'.
	'<form name="CellDeleteFormula" method="get" action="DeleteCell.php">'.
	'<input type="hidden" name="id" value="'.$cellRow['id'].'"/>'.
	'</form>'.
	'<script type="text/javascript">
	parent.parent.Toolbar.location="CellToolbar.php?"+Math.random();
	</script>';
	return $out;
}

function buildNewSectionButton($cellId,$position) {
	return
	'<a title="'.$position.'" onclick="document.forms.SectionAdder.cell.value=\''.$cellId.'\';document.forms.SectionAdder.position.value=\''.$position.'\'; in2iMenuHandler.showMenu(menu, this); N2i.Event.stop(event); return false;" href="javascript: void(0);">'.
	'<img src="Graphics/Add.gif" width="14" height="14" border="0" class="Icon"/>'.
	'</a>';
}

function displayPart($type,$id) {
	global $partContext;
	$part = Part::load($type,$id);
	return $part->display($partContext);
}

function editPart($type,$id) {
	global $partContext;
	$part = Part::load($type,$id);
	$data = '<form name="PartForm" action="UpdatePart.php" method="post">'.
	'<input type="hidden" name="id" value="'.$id.'"/>'.
	'<input type="hidden" name="left"/>'.
	'<input type="hidden" name="right"/>'.
	'<input type="hidden" name="bottom"/>'.
	'<input type="hidden" name="top"/>'.
	$part->editor($partContext).
	'</form>';
	return $data;
}

function buildIcon($icon,$link,$confirmation='') {
	return '<a href="'.$link.'"'.
	($confirmation!='' ? ' onclick="return confirm(\''.$confirmation.'\')"' : '').
	'>'.
	'<img src="Graphics/'.$icon.'.gif" width="14" height="14" border="0" class="Icon"/>'.
	'</a>';
}
?>