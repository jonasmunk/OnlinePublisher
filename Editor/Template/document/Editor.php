<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../Include/Private.php';

header('Content-Type: text/html; charset=UTF-8');

$design = InternalSession::getPageDesign();
$language = InternalSession::getLanguage();

$editedSection = null;
if (Request::exists('section')) {
	$editedSection = Request::getInt('section',null);
}

$pageId = InternalSession::getPageId();

$stamp = SystemInfo::getDate();
$cacheUrl = 'version'.$stamp.'/';
$cachePrefix = '?version='.$stamp;
if (ConfigurationService::isUrlRewrite()) {
    $cachePrefix = '';
}

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html>
		<head>
		<title>Editor</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		';
		echo '<link rel="stylesheet" type="text/css" href="../../../hui/'.$cacheUrl.'bin/minimized.css'.$cachePrefix.'" />
		<link rel="stylesheet" type="text/css" href="../../../style/'.$cacheUrl.'basic/css/parts.php'.$cachePrefix.'" />
		<link rel="stylesheet" type="text/css" href="../../../style/'.$cacheUrl.'basic/css/document.css'.$cachePrefix.'" />';
		if (file_exists($basePath.'style/'.$design.'/css/overwrite.css')) {
			echo '<link rel="stylesheet" type="text/css" href="../../../style/'.$cacheUrl.$design.'/css/editor.css'.$cachePrefix.'" />';
		}    
		if (file_exists($basePath.'style/'.$design.'/css/editor.css')) {
			echo '<link rel="stylesheet" type="text/css" href="../../../style/'.$cacheUrl.$design.'/css/editor.css'.$cachePrefix.'" />';
		}
    echo '
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css'.$cachePrefix.'" />
		<!--[if IE 8]><link rel="stylesheet" type="text/css" href="../../../hui/'.$cacheUrl.'css/msie8.css'.$cachePrefix.'"></link><![endif]-->
		<!--[if lt IE 7]><link rel="stylesheet" type="text/css" href="../../../hui/'.$cacheUrl.'css/msie6.css'.$cachePrefix.'"></link><![endif]-->
		<!--[if IE 7]><link rel="stylesheet" type="text/css" href="../../../hui/'.$cacheUrl.'css/msie7.css'.$cachePrefix.'"></link><![endif]-->
		<!--[if lt IE 9]><script type="text/javascript" src="../../../hui/'.$cacheUrl.'bin/compatibility.min.js'.$cachePrefix.'" charset="UTF-8"></script><![endif]-->
		<script type="text/javascript" src="'.$cacheUrl.'js/combined.php'.$cachePrefix.'" charset="UTF-8"></script>
		<script type="text/javascript">
			op.context = "../../../";
			hui.ui.context = "../../../";
			hui.ui.language = "'.$language.'";
			controller.context = "'.ConfigurationService::getBaseUrl().'";
			controller.pageId = '.$pageId.';
			controller.changed = '.(PageService::isChanged($pageId) ? 'true' : 'false')."\n";
		if ($editedSection==null) {
			echo "controller.setMainToolbar();\n";
		} else if ($editedSection>0) {
			echo "controller.activeSection=".$editedSection.";\n";
		}
		echo '</script>
	</head>
	<body class="editor'.($editedSection!=null ? ' editor_edit_section_mode' : '').'">
	<div class="editor_body">
		';
	
	$partContext = DocumentTemplateController::buildPartContext($pageId);
	
	displayRows($pageId);
	
	echo '</div>';

if ($editedSection==null) {
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
				<button text="{Edit; da:Rediger}" name="editLink" highlighted="true" variant="light" small="true"/>
				<button text="{Delete; da:Slet}" name="deleteLinkPanel" variant="light" small="true">
					<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{No; da:Nej}"/>
				</button>
				<button text="{Visit; da:Besøg}" name="visitLink" variant="light" small="true"/>
				<!--
				<button text="{Only this section; da:Kun dette afsnit}" name="limitLinkToPart" variant="light"/>
				-->
				<button text="{Cancel; da:Annuller}" name="cancelLinkPanel" variant="light" small="true"/>
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
			<item text="{Edit row; da:Instil række}" value="editRow"/>
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
				<item text="{Edit row; da:Instil række}" value="editRow"/>
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
		
		<window width="300" name="rowWindow" padding="5" title="{Row; da:Række}">
			<formula name="rowFormula">
				<fields>
					<field label="{Top; da:Top}">
						<style-length-input key="top"/>
					</field>
					<field label="{Bottom; da:Bund}">
						<style-length-input key="bottom"/>
					</field>
				</fields>
				<buttons top="5">
					<button text="{Delete; da:Slet}" name="deleteRow">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{No; da:Nej}"/>
					</button>
					<button text="{Cancel; da:Annuller}" name="cancelRow"/>
					<button text="{Update; da:Opdater}" submit="true" highlighted="true" name="saveRow"/>
				</buttons>
			</formula>
		</window>

		<window width="300" name="columnWindow" padding="5" title="{Column; da:Kolonne}">
			<formula name="columnFormula">
				<space all="5">
				<fields labels="above">
					<field label="{Width...; da:Bredde...}">
						<radiobuttons key="preset" name="columnPreset">
							<item value="dynamic" text="{Match content; da:Efter indhold}"/>
							<item value="min" text="{Minimal; da:Mindst mulig}"/>
							<item value="max" text="{Maximal; da:Størst muligt}"/>
							<item value="specific" text="{Special; da:Speciel...}"/>
						</radiobuttons>
					</field>
					<field label="{Special width; da:Speciel bredde}">
						<style-length-input key="width" name="columnWidth"/>
					</field>
				</fields>
				<columns flexible="true">
					<column>
						<field label="{Left; da:Venstre}">
							<style-length-input key="left"/>
						</field>
						<field label="{Right; da:Højre}">
							<style-length-input key="right"/>
						</field>
					</column>
					<column>
						<field label="{Top; da:Top}">
							<style-length-input key="top"/>
						</field>
						<field label="{Bottom; da:Bund}">
							<style-length-input key="bottom"/>
						</field>
					</column>
				</columns>
				</space>
				<buttons top="5">
					<button text="{Delete; da:Slet}" name="deleteColumn">
						<confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{No; da:Nej}"/>
					</button>
					<button text="{Cancel; da:Annuller}" name="cancelColumn"/>
					<button text="{Update; da:Opdater}" submit="true" highlighted="true" name="saveColumn"/>
				</buttons>
			</formula>
		</window>

		<window width="300" name="importWindow" padding="5" title="{Import; da:Importering}">
			<upload name="importUpload" url="actions/ImportUpload.php" widget="upload">
				<placeholder title="{Select an file on you computer...; da:Vælg en fil på din computer...}" text="{Image can be in the format JPEG, PNG or GIF. The file size can at most be; da: Billeders format skal være JPEG, PNG eller GIF. Filens størrelse må højest være} '.GuiUtils::bytesToString(FileSystemService::getMaxUploadSize()).'."/>
			</upload>
			<buttons align="center" top="10">
				<button name="cancelImport" title="{Close; da:Luk}"/>
				<button name="upload" title="{Select image...; da:Vælg billede...}" highlighted="true"/>
			</buttons>
		</window>
	';
	echo UI::renderFragment($gui);
}
echo '</body></html>';
?>























<?php
function displayRows($pageId) {
	
	$structure = DocumentTemplateEditor::getStructure($pageId);
	
	foreach ($structure as $row) {
		echo '<table border="0" width="100%" cellpadding="0" cellspacing="0" id="row'.$row['id'].'" style="';
		if ($row['top']) {
			echo 'margin-top: '.$row['top'].';';
		}
		if ($row['bottom']) {
			echo 'margin-bottom: '.$row['bottom'].';';
		}
		echo '"><tr>';
		displayColumns($row);
		echo '</tr></table>';
		echo "\n";
	}
}

function displayColumns(&$row) {
	
	foreach ($row['columns'] as $column) {
		$style = '';
		$columnWidth = $column['width'];
		if ($columnWidth!='') {
			if ($columnWidth=='min') {
				$style.= 'width: 1%;';
			}
			elseif ($columnWidth=='max') {
				$style.= 'width: 100%;';
			}
			else {
				$style.= 'width: '.$columnWidth.';';
			}
		}
		if ($column['left']) {
			$style.= 'padding-left: '.$column['left'].';';
		}
		if ($column['right']) {
			$style.= 'padding-right: '.$column['right'].';';
		}
		if ($column['top']) {
			$style.= 'padding-top: '.$column['top'].';';
		}
		if ($column['bottom']) {
			$style.= 'padding-bottom: '.$column['bottom'].';';
		}
		echo "\n";
		echo '<td class="editor_column" data-id="'.$column['id'].'" id="column'.$column['id'].'" style="'.$style.'"';
		echo ' onmouseover="controller.columnOver(this)" onmouseout="controller.columnOut(this)"';
		echo ' oncontextmenu="return controller.showColumnMenu(this,event,'.$column['id'].','.$row['index'].','.$row['id'].','.$row['index'].');">';
		displaySections($column,$row);
		echo "</td>\n";
	}
}

function displaySections(&$column,&$row) {
	global $language,$editedSection;
	$lastIndex=0;
		
	foreach ($column['sections'] as $sectionRow) {
		$style=buildSectionStyle($sectionRow);
		if ($editedSection==null) {
			echo '<div class="editor_section_adder_container">'.
					'<div class="editor_section_adder" data=\'{"columnId":'.$column['id'].',"sectionIndex":'.$sectionRow['index'].'}\' onclick="controller.showAdderMenu({element:this,event:event}); return false">'.
						'<div><span><em></em><strong></strong></span></div>'.
					'</div>'.
				'</div>';
		}
		echo '<div id="section'.$sectionRow['id'].'"';
		if ($sectionRow['width']) {
			echo ' style="width: '.$sectionRow['width'].'"';
		}
		echo '>';
		if ($sectionRow['id']==$editedSection) {
			partEditor($sectionRow);
		}
		else {
			displayPart($sectionRow,$column,$row);
		}
		echo '</div>';
		$lastIndex = $sectionRow['index'];
	}
	if ($editedSection==null) {
		echo '<div class="editor_section_adder_container">'.
				'<div class="editor_section_adder" data=\'{"columnId":'.$column['id'].',"sectionIndex":'.($lastIndex+1).'}\' onclick="controller.showAdderMenu({element:this,event:event}); return false">'.
					'<div><span><em></em><strong></strong></span></div>'.
				'</div>'.
			'</div>';
		echo '<div style="padding: 5px;">'.
		'<a onclick="controller.showNewPartMenu({element:this,event:event,columnId:'.$column['id'].',sectionIndex:'.($lastIndex+1).'}); return false" href="#" class="hui_button hui_button_light hui_button_small hui_button_small_light">'.
		'<span><span>'.GuiUtils::getTranslated(array('Add section','da'=>'Tilføj afsnit')).'</span></span>'.
		'</a>'.
		'</div>';
	} else {
		echo '<div style="padding: 5px;"><a class="hui_button hui_button_light hui_button_small hui_button_small_light '.($editedSection!=null ? 'hui_button_disabled' : '').'"><span><span>'.GuiUtils::getTranslated(array('Add section','da'=>'Tilføj afsnit')).'</span></span></a></div>';
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

function displayPart(&$sectionRow,&$column,&$row) {
	global $partContext;
	if (!$sectionRow['partType']) {
		echo '<div class="editor_error">Error: The part could not be loaded (no type)</div>';
		return;
	}
	
	$ctrl = PartService::getController($sectionRow['partType']);
	if ($ctrl) {
		$part = PartService::load($sectionRow['partType'],$sectionRow['partId']);
		if ($part) {
			echo '<div id="part'.$part->getId().'" style="'.buildSectionStyle($sectionRow).'"';
				echo ' class="editor_section part_section_'.$part->getType().' '.$ctrl->getSectionClass($part).'"';
				echo ' oncontextmenu="return controller.showSectionMenu(this,event,'.$sectionRow['id'].','.$sectionRow['index'].','.$column['id'].','.$column['index'].','.$row['id'].','.$row['index'].');"';
				echo ' onmouseover="controller.sectionOver(this,'.$sectionRow['id'].','.$column['id'].','.$sectionRow['index'].')"';
				echo ' onmouseout="controller.sectionOut(this,event)"';
				echo ' data=\'{"part":'.$part->getId().'}\' onclick="controller.clickSection({event:event,node:this,id:'.$sectionRow['id'].'})">';
			echo $ctrl->display($part,$partContext);
			echo '</div>';			
		} else {
			echo '<div class="editor_error">Error: The part could not be loaded</div>';
		}
	}
}

function partEditor($section) {
	global $partContext;
	$ctrl = PartService::getController($section['partType']);
	if (!$ctrl) {
		return;
	}
	$part = PartService::load($section['partType'],$section['partId']);
	if (!$part) {
		return;
	}
	echo
	'<div style="'.buildSectionStyle($section).'" id="selectedSection" class="part_section_'.$section['partType'].' '.$ctrl->getSectionClass($part).' editor_section_selected">'.
	'<form name="PartForm" action="data/UpdatePart.php" method="post" charset="utf-8">'.
	'<input type="hidden" name="id" value="'.$part->getId().'"/>'.
	'<input type="hidden" name="part_type" value="'.$part->getType().'"/>'.
	'<input type="hidden" name="section" value="'.$section['id'].'"/>'.
	'<input type="hidden" name="left" value="'.Strings::escapeXML($section['left']).'"/>'.
	'<input type="hidden" name="right" value="'.Strings::escapeXML($section['right']).'"/>'.
	'<input type="hidden" name="bottom" value="'.Strings::escapeXML($section['bottom']).'"/>'.
	'<input type="hidden" name="top" value="'.Strings::escapeXML($section['top']).'"/>'.
	'<input type="hidden" name="width" value="'.Strings::escapeXML($section['width']).'"/>'.
	'<input type="hidden" name="float" value="'.Strings::escapeXML($section['float']).'"/>';
	echo $ctrl->editor($part,$partContext);
	echo '</form></div>';
	if (method_exists($ctrl,'editorGui')) {
		echo $ctrl->editorGui($part,$partContext);
	}
	echo '<script type="text/javascript">
	try {
		parent.frames[0].location="PartToolbar.php?sectionId='.$section['id'].'&partId='.$part->getId().'&partType='.$part->getType().'&'.time().'"
	} catch(e) {
		hui.log("Unable to set toolbar");hui.log(e);
	};'.
	'function saveSection() {
		document.forms.PartForm.submit();
	}'.
	'</script>';
}
?>