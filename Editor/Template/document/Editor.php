<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../Include/Private.php';

header('Content-Type: text/html; charset=UTF-8');

$design = InternalSession::getPageDesign();
$language = InternalSession::getLanguage();

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
		<script type="text/javascript" src="js/combined.php?version='.$stamp.(Request::getBoolean('dev') ? '&dev=true' : '').'" charset="UTF-8"></script>
		<script type="text/javascript">
			op.context = "../../../";
			hui.ui.context = "../../../";
			hui.ui.language = "'.$language.'";
			controller.context = "'.ConfigurationService::getBaseUrl().'";
			controller.pageId = '.$pageId.';
			controller.changed = '.(PageService::isChanged($pageId) ? 'true' : 'false')."\n";
		if ($section==null) {
			echo "controller.setMainToolbar();\n";
		} else if ($section>0) {
			echo "controller.activeSection=".$section.";\n";
		}
		echo '</script>
	</head>
	<body class="editor">
	<div class="editor_body">
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
					<field label="{Text; da:Tekst}">
						<text-input key="text" multiline="true"/>
					</field>
					<field label="{Description; da:Beskrivelse}">
						<text-input key="description"/>
					</field>
					<field label="{Scope; da:Rækkevidde}">
						<radiobuttons key="scope" name="linkScope">
							<item value="page" text="{Entire page; da:Hele siden}"/>
							<item value="part" text="{Only this section; da:Kun dette afsnit}"/>
						</radiobuttons>
					</field>
				</fields>
				<space left="3" right="3" top="5">
				<fieldset legend="Link">
					<fields>
						<field label="{Page; da:Side}">
							<dropdown key="page" name="linkPage" source="pageSource"/>
						</field>
						<field label="{File; da:Fil}">
							<dropdown key="file" name="linkFile" source="fileSource"/>
						</field>
						<field label="{Address; da:Adresse}">
							<text-input key="url" name="linkUrl"/>
						</field>
						<field label="{E-mail; da:E-post}">
							<text-input key="email" name="linkEmail"/>
						</field>
					</fields>
				</fieldset>
				</space>
				<buttons top="5">
					<button text="{Delete; da:Slet}" name="deleteLink">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{No; da:Nej}"/>
					</button>
					<button text="{Cancel; da:Annuller}" name="cancelLink"/>
					<button text="{Create; da:Opret}" submit="true" highlighted="true" name="saveLink"/>
				</buttons>
			</formula>
		</window>
	
		<boundpanel name="linkPanel" variant="light">
			<space all="3" bottom="10">
				<rendering name="linkInfo"/>
			</space>
			<buttons align="center">
				<button text="{Edit; da:Rediger}" name="editLink" highlighted="true" variant="paper" small="true"/>
				<button text="{Delete; da:Slet}" name="deleteLinkPanel" variant="paper" small="true">
					<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{No; da:Nej}"/>
				</button>
				<button text="{Visit; da:Besøg}" name="visitLink" variant="paper" small="true"/>
				<!--
				<button text="{Only this section; da:Kun dette afsnit}" name="limitLinkToPart" variant="paper"/>
				-->
				<button text="{Cancel; da:Annuller}" name="cancelLinkPanel" variant="paper" small="true"/>
			</buttons>
		</boundpanel>
	
		<menu name="linkMenu">
			<item text="{Delete; da:Slet}" value="delete"/>
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
			<item text="{Copy section; da:Kopiér sektion}" value="copySection"/>
			<item text="{Cut section; da:Klip sektion}" value="cutSection"/>
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
		
		<menu name="partMenu">
			
		';
		$parts = PartService::getPartMenu();
		foreach ($parts as $part => $info) {
			if ($info=='divider') {
				$gui.='<divider/>';
				continue;
			}
			$gui.='<item text="'.$info['name'][$language].'" value="'.$part.'">';
			if (isset($info['children']) && is_array($info['children'])) {
				foreach ($info['children'] as $subPart => $subInfo) {
					$gui.='<item text="'.$subInfo['name'][$language].'" value="'.$subPart.'"/>';
				}
			}
			$gui.='</item>';
		}
		$gui.='
			<divider/>
			<item text="{Paste; da:Indsæt}" value="paste"/>
		</menu>

		<window width="300" name="columnWindow" padding="5" title="{Column; da:Kolonne}">
			<formula name="columnFormula">
				<fields labels="above">
					<field label="{Width...; da:Bredde...}">
						<radiobuttons key="preset" name="columnPreset">
							<item value="dynamic" text="{Match content; da:Efter indhold}"/>
							<item value="min" text="{Minimal; da:Mindst mulig}"/>
							<item value="max" text="{Maximal; da:Størst muligt}"/>
							<item value="specific" text="{Special; da:Speciel...}"/>
						</radiobuttons>
					</field>
					<field label="{Width; da:Bredde}">
						<style-length-input key="width" name="columnWidth"/>
					</field>
				</fields>
				<buttons top="5">
					<button text="{Delete; da:Slet}" name="deleteColumn">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{No; da:Nej}"/>
					</button>
					<button text="{Cancel; da:Annuller}" name="cancelColumn"/>
					<button text="{Update; da:Opdater}" submit="true" highlighted="true" name="saveColumn"/>
				</buttons>
			</formula>
		</window>

		<menu name="columnMenu">
			<item title="{Add column; da:Tilføj kolonne}" key="addColumn"/>
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
		echo '<td class="editor_column" data-id="'.$row['id'].'" id="column'.$row['id'].'"'.$columnWidth;
		echo ' onmouseover="controller.columnOver(this)" onmouseout="controller.columnOut(this)"';
		echo ' oncontextmenu="return controller.showColumnMenu(this,event,'.$row['id'].','.$row['index'].','.$rowId.','.$rowIndex.');">';
		displaySections($row['id'],$row['index'],$rowId,$rowIndex);
		echo '</td>';
		echo "\n";
	}
	Database::free($result);
}

function displaySections($columnId,$columnIndex,$rowId,$rowIndex) {
	global $language,$section;
	$lastIndex=0;
	
	$sql="select document_section.*,part.type as part_type from document_section left join part on document_section.part_id=part.id where column_id=".$columnId." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$style=buildSectionStyle($row);
		if ($section==null) {
			echo '<div class="editor_section_adder_container"><div class="editor_section_adder" onclick="controller.showAdderMenu({element:this,event:event,columnId:'.$columnId.',sectionIndex:'.($row['index']).'}); return false"><div><span><em></em><strong></strong></span></div></div></div>';
		}
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
		echo '<div class="editor_section_adder_container"><div class="editor_section_adder" onclick="controller.showAdderMenu({element:this,event:event,columnId:'.$columnId.',sectionIndex:'.(($lastIndex+1)).'}); return false"><div><span><em></em><strong></strong></span></div></div></div>';
	}
	if ($section==null) {
		echo '<div style="padding: 5px;">'.
		'<a onclick="controller.showNewPartMenu({element:this,event:event,columnId:'.$columnId.',sectionIndex:'.($lastIndex+1).'}); return false" href="#" class="hui_button hui_button_paper hui_button_small hui_button_small_paper">'.
		'<span><span>'.GuiUtils::getTranslated(array('Add section','da'=>'Tilføj afsnit')).'</span></span>'.
		'</a>'.
		'</div>';
	} else {
		echo '<div style="padding: 5px;"><a class="hui_button hui_button_paper hui_button_small hui_button_small_paper '.($section!=null ? 'hui_button_disabled' : '').'"><span><span>'.GuiUtils::getTranslated(array('Add section','da'=>'Tilføj afsnit')).'</span></span></a></div>';
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
	global $partContext,$section;
	if (!$partType) {
		echo '<div class="editor_error">Error: The part could not be loaded (no type)</div>';
		return;
	}
	
	$ctrl = PartService::getController($partType);
	if ($ctrl) {
		$part = PartService::load($partType,$partId);
		if ($part) {
			echo '<div id="part'.$partId.'" style="'.$sectionStyle.'" class="part_section_'.$partType.' '.$ctrl->getSectionClass($part).' section editor_section '.($section!=null ? 'editor_section_inactive' : '').'"  oncontextmenu="return controller.showSectionMenu(this,event,'.$sectionId.','.$sectionIndex.','.$columnId.','.$columnIndex.','.$rowId.','.$rowIndex.');" onmouseover="controller.sectionOver(this,'.$sectionId.','.$columnId.','.$sectionIndex.')" onmouseout="controller.sectionOut(this,event)" data=\'{"part":'.$partId.'}\' onclick="controller.clickSection({event:event,node:this,id:'.$sectionId.'})">';
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