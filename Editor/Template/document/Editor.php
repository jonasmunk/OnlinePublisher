<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Services/PartService.php';
require_once '../../Classes/Parts/PartContext.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Core/SystemInfo.php';
require_once '../../Classes/Templates/DocumentTemplateController.php';

header('Content-Type: text/html; charset=UTF-8');

$design = InternalSession::getPageDesign();
$language = InternalSession::getLanguage();
$strings = array(
	'add_section' => array('da' => 'Tilføj afsnit','en' => 'Add section')
);

$section = null;
if (Request::exists('section')) {
	$section = Request::getInt('section',null);
}

$pageId = InternalSession::getPageId();

$stamp = SystemInfo::getDate();

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html>
		<head>
		<title>Editor</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="../../../hui/bin/minimized.css?version='.$stamp.'" />
		<link rel="stylesheet" type="text/css" href="../../../style/basic/css/parts.php?version='.$stamp.'" />
		<link rel="stylesheet" type="text/css" href="../../../style/basic/css/document.css?version='.$stamp.'" />
		<link rel="stylesheet" type="text/css" href="../../../style/'.$design.'/css/overwrite.css?version='.$stamp.'" />
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css?version='.$stamp.'" />
		<!--[if IE 8]><link rel="stylesheet" type="text/css" href="../../../hui/css/msie8.css?version='.$stamp.'"> </link><![endif]-->
		<!--[if lt IE 7]><link rel="stylesheet" type="text/css" href="../../../hui/css/msie6.css?version='.$stamp.'"> </link><![endif]-->
		<!--[if IE 7]><link rel="stylesheet" type="text/css" href="../../../hui/css/msie7.css?version='.$stamp.'"> </link><![endif]-->		
		<!--<link href="http://fonts.googleapis.com/css?family=Just+Me+Again+Down+Here|Cabin+Sketch:bold|Droid+Sans|Crimson+Text:regular,bold|Luckiest+Guy|Dancing+Script" rel="stylesheet" type="text/css" />-->
		<script type="text/javascript" src="js/combined.php?version='.$stamp.'" charset="UTF-8"></script>
		<script type="text/javascript">
			hui.ui.context = "../../../";
			hui.ui.language = "'.$language.'";
			controller.context = "'.$baseUrl.'";
			controller.pageId = '.$pageId.';
			controller.changed = '.(PageService::isChanged($pageId) ? 'true' : 'false')."\n";
		$parts = PartService::getParts();
		foreach ($parts as $part => $info) {
			echo "controller.parts.push({value:'".$part."',title:'".$info['name'][$language]."'});\n";
		}
		if ($section==null) {
			echo "controller.setMainToolbar();\n";
		} else if ($section>0) {
			echo "controller.activeSection=".$section.";\n";
		}
		echo '</script>
	</head>
	<body class="editor">
	<div class="editor_body">
		<form action="data/AddSection.php" name="SectionAdder" method="get" style="margin: 0px;">
			<input type="hidden" name="type"/>
			<input type="hidden" name="part"/>
			<input type="hidden" name="column"/>
			<input type="hidden" name="index"/>
		</form>
		';
	$partContext = DocumentTemplateController::buildPartContext($pageId);
	displayRows($pageId);
	echo '</div>';

if ($section==null) {
	$gui = '
		<source name="pageSource" url="../../Services/Model/Items.php?type=page"/>
		<source name="fileSource" url="../../Services/Model/Items.php?type=file"/>
		<window width="400" name="linkWindow" padding="5" title="Link">
			<formula name="linkFormula">
				<fields labels="above">
					<field label="Tekst">
						<text-input key="text" multiline="true"/>
					</field>
					<field label="Beskrivelse">
						<text-input key="description"/>
					</field>
					<field label="Omfang">
						<radiobuttons key="scope" name="linkScope">
							<item value="page" text="Hele siden"/>
							<item value="part" text="Kun dette afsnit"/>
						</radiobuttons>
					</field>
				</fields>
				<space left="3" right="3" top="5">
				<fieldset legend="Link">
					<fields>
						<field label="Side">
							<dropdown key="page" name="linkPage" source="pageSource"/>
						</field>
						<field label="Fil">
							<dropdown key="file" name="linkFile" source="fileSource"/>
						</field>
						<field label="Adresse">
							<text-input key="url" name="linkUrl"/>
						</field>
						<field label="E-post">
							<text-input key="email" name="linkEmail"/>
						</field>
					</fields>
				</fieldset>
				</space>
				<buttons top="5">
					<button text="Slet" name="deleteLink">
						<confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
					</button>
					<button text="Annuller" name="cancelLink"/>
					<button text="Opret" submit="true" highlighted="true" name="saveLink"/>
				</buttons>
			</formula>
		</window>
	
		<boundpanel name="linkPanel" variant="light">
			<space all="3" bottom="10">
				<rendering name="linkInfo"/>
			</space>
			<buttons align="center">
				<button text="Rediger" name="editLink" highlighted="true" variant="paper" small="true"/>
				<button text="Slet" name="deleteLinkPanel" variant="paper" small="true">
					<confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
				</button>
				<button text="Besøg" name="visitLink" variant="paper" small="true"/>
				<!--
				<button text="Kun dette afsnit" name="limitLinkToPart" variant="paper"/>
				-->
				<button text="Annuller" name="cancelLinkPanel" variant="paper" small="true"/>
			</buttons>
		</boundpanel>
	
		<menu name="linkMenu">
			<item text="Slet" value="delete"/>
		</menu>
	
		<menu name="columnMenu">
			<item text="{Add column; da:Tilføj kolonne}" value="addColumn"/>
			<item text="{Edit column; da:Instil kolonne}" value="editColumn"/>
			<item text="{Move right; da:Flyt kolonne til højre}" value="moveColumnRight"/>
			<item text="{Move left; da:Flyt kolonne til venstre}" value="moveColumnLeft"/>
			<item text="{Delete column; da:Slet kolonne}" value="deleteColumn"/>
			<divider/>
			<item text="{Move up; da:Flyt op}" value="moveRowUp"/>
			<item text="{Move down; da:Flyt ned}" value="moveRowDown"/>
			<item text="{Add row; da:Tilføj række}" value="addRow"/>
			<item text="{Delete row; da:Slet række}" value="deleteRow"/>
		</menu>
	
		<menu name="sectionMenu">
			<item text="{Edit section; da:Rediger sektion}" value="editSection"/>
			<item text="{Delete section; da:Slet sektion}" value="deleteSection"/>
			<item text="{Move section up; da:Flyt sektion op}" value="moveSectionUp"/>
			<item text="{Move section down; da:Flyt sektion ned}" value="moveSectionDown"/>
			<divider/>
			<item text="{Column ; da:Kolonne}">
				<item text="{Add column; da:Tilføj kolonne}" value="addColumn"/>
				<item text="{Edit column; da:Instil kolonne}" value="editColumn"/>
				<item text="{Move right; da:Flyt kolonne til højre}" value="moveColumnRight"/>
				<item text="{Move left; da:Flyt kolonne til venstre}" value="moveColumnLeft"/>
				<item text="{Delete column; da:Slet kolonne}" value="deleteColumn"/>
			</item>
			<item text="{Row ; da:Række}">
				<item text="{Move up; da:Flyt op}" value="moveRowUp"/>
				<item text="{Move down; da:Flyt ned}" value="moveRowDown"/>
				<item text="{Add row; da:Tilføj række}" value="addRow"/>
				<item text="{Delete row; da:Slet række}" value="deleteRow"/>
			</item>
		</menu>

		<window width="300" name="columnWindow" padding="5" title="Kolonne">
			<formula name="columnFormula">
				<fields labels="above">
					<field label="Bredde...">
						<radiobuttons key="preset" name="columnPreset">
							<item value="dynamic" text="Efter indhold"/>
							<item value="min" text="Mindst mulig"/>
							<item value="max" text="Størst muligt"/>
							<item value="specific" text="Speciel..."/>
						</radiobuttons>
					</field>
					<field label="Speciel bredde">
						<style-length-input key="width" name="columnWidth"/>
					</field>
				</fields>
				<buttons top="5">
					<button text="Slet" name="deleteColumn">
						<confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
					</button>
					<button text="Annuller" name="cancelColumn"/>
					<button text="Opdater" submit="true" highlighted="true" name="saveColumn"/>
				</buttons>
			</formula>
		</window>

		<menu name="columnMenu">
			<item title="Tilføj kolonne" key="addColumn"/>
		</menu>


	';
	echo In2iGui::renderFragment($gui);
}
echo '</body></html>';
?>























<?php
function displayRows($pageId) {
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
	$sql="select * from document_column where row_id=".Database::int($rowId)." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$columnWidth=$row['width'];
		if ($columnWidth!='') {
			if ($columnWidth=='min') {
				$columnWidth=' style="width: 1%"';
			}
			elseif ($columnWidth=='max') {
				$columnWidth=' style="width: 100%"';
			}
			else {
				$columnWidth=' style="width: '.$columnWidth.'"';
			}
		}
		echo "\n";
		echo '<td class="editor_column" id="column'.$row['id'].'"'.$columnWidth;
		echo ' onmouseover="controller.columnOver(this)" onmouseout="controller.columnOut(this)"';
		echo ' oncontextmenu="return controller.showColumnMenu(this,event,'.$row['id'].','.$row['index'].','.$rowId.','.$rowIndex.');">';
		displaySections($row['id'],$row['index'],$rowId,$rowIndex);
		echo '</td>';
		echo "\n";
	}
	Database::free($result);
}

function displaySections($columnId,$columnIndex,$rowId,$rowIndex) {
	global $language,$strings,$section;
	$lastIndex=0;
	
	$sql="select document_section.*,part.type as part_type from document_section left join part on document_section.part_id=part.id where column_id=".$columnId." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$style=buildSectionStyle($row);
		echo '<div id="section'.$row['id'].'"';
		if ($row['width']) {
			echo ' style="width: '.$row['width'].'"';
		}
		echo '>';
		if ($row['id']==$section) {
			sectionEditor($row['id'],$row['type'],$style,$row['part_id'],$row['part_type'],$row);
		}
		else {
			displaySection($row['id'],$row['type'],$row['index'],$style,$row['part_id'],$row['part_type'],$columnId,$columnIndex,$rowId,$rowIndex);
		}
		echo '</div>';
		$lastIndex = $row['index'];
	}
	Database::free($result);
	if ($section==null) {
		echo '<div style="padding: 5px;">'.
		'<a onclick="controller.showNewPartMenu({element:this,event:event,columnId:'.$columnId.',sectionIndex:'.($lastIndex+1).'}); return false" href="#" class="hui_button hui_button_paper hui_button_small hui_button_small_paper">'.
		'<span><span>'.$strings['add_section'][$language].'</span></span>'.
		'</a>'.
		'</div>';
	} else {
		echo '<div style="padding: 5px;"><a class="hui_button hui_button_paper hui_button_small hui_button_small_paper"><span><span>'.$strings['add_section'][$language].'</span></span></a></div>';
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
	if (!$partType) {
		echo '<div class="editor_error">Error: The part could not be loaded (no type)</div>';
		return;
	}
	
	$ctrl = PartService::getController($partType);
	if ($ctrl) {
		$part = PartService::load($partType,$partId);
		if ($part) {
			echo '<div id="part'.$partId.'" style="'.$sectionStyle.'" class="part_section_'.$partType.' '.$ctrl->getSectionClass($part).' section editor_section"  oncontextmenu="return controller.showSectionMenu(this,event,'.$sectionId.','.$sectionIndex.','.$columnId.','.$columnIndex.','.$rowId.','.$rowIndex.');" onmouseover="controller.sectionOver(this,'.$sectionId.','.$columnId.','.$sectionIndex.')" onmouseout="controller.sectionOut(this,event)" data=\'{"part":'.$partId.'}\' onclick="controller.clickSection({event:event,node:this,id:'.$sectionId.'})">';
			echo $ctrl->display($part,$partContext);
			echo '</div>';			
		} else {
			echo '<div class="editor_error">Error: The part could not be loaded</div>';
		}
	}
}

function sectionEditor($sectionId,$type,$sectionStyle,$partId,$partType,$row) {
	if ($type=='part') {
		partEditor($partId,$partType,$sectionId,$sectionStyle,$row);
	}
}

function partEditor($partId,$partType,$sectionId,$sectionStyle,$row) {
	global $partContext;
	$ctrl = PartService::getController($partType);
	if (!$ctrl) {
		return;
	}
	$part = PartService::load($partType,$partId);
	if (!$part) {
		return;
	}
	echo
	'<div style="'.$sectionStyle.'" id="selectedSection" class="part_section_'.$partType.' '.$ctrl->getSectionClass($part).' section_selected">'.
	'<form name="PartForm" action="data/UpdatePart.php" method="post" charset="utf-8">'.
	'<input type="hidden" name="id" value="'.$partId.'"/>'.
	'<input type="hidden" name="part_type" value="'.$partType.'"/>'.
	'<input type="hidden" name="section" value="'.$sectionId.'"/>'.
	'<input type="hidden" name="left" value="'.StringUtils::escapeXML($row['left']).'"/>'.
	'<input type="hidden" name="right" value="'.StringUtils::escapeXML($row['right']).'"/>'.
	'<input type="hidden" name="bottom" value="'.StringUtils::escapeXML($row['bottom']).'"/>'.
	'<input type="hidden" name="top" value="'.StringUtils::escapeXML($row['top']).'"/>'.
	'<input type="hidden" name="width" value="'.StringUtils::escapeXML($row['width']).'"/>'.
	'<input type="hidden" name="float" value="'.StringUtils::escapeXML($row['float']).'"/>';
	echo $ctrl->editor($part,$partContext);
	echo '</form></div>';
	if (method_exists($ctrl,'editorGui')) {
		echo $ctrl->editorGui($part,$partContext);
	}
	echo '<script type="text/javascript">
	try {
		parent.frames[0].location="PartToolbar.php?sectionId='.$sectionId.'&partId='.$partId.'&partType='.$partType.'&'.time().'"
	} catch(e) {
		hui.log("Unable to set toolbar");hui.log(e);
	};'.
	'function saveSection() {
		document.forms.PartForm.submit();
	}'.
	'</script>';
}
?>