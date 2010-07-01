<?php
if (true) {
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/Link.php';

if (Request::getBoolean('link')) {
	$id = Request::getInt('id');
	$link = Link::load($id);
	$new = $link==null;
	if (!$link) {
		$link = new Link();
	}
	$gui='
	<gui xmlns="uri:In2iGui" title="Dokument">
		<controller source="js/LinkToolbar.js"/>
		<script>
		controller.id='.$id.';
		</script>
		<tabs small="true" below="true">
			<tab title="'.($new ? 'Nyt link' : 'Rediger link').'" background="light">
				<toolbar>
					<grid>
						<row>
							<cell label="Tekst:" width="200" right="10">
								<textfield name="text" value="'.In2iGui::escape($link->getText()).'"/>
							</cell>
							<cell label="Side:" width="200">
								<dropdown name="page" adaptive="true" value="'.$link->getPage().'">
									'.GuiUtils::buildPageItems().'
								</dropdown>
							</cell>
							<cell label="URL:" width="100">
								<textfield name="url" value="'.In2iGui::escape($link->getUrl()).'"/>
							</cell>
							<cell left="10">
							'.($new ? '
								<button title="Opret" small="true" rounded="true" name="create"/>
							' : '
								<button title="Opdater" small="true" rounded="true" name="update"/>
								<button title="Slet" small="true" rounded="true" name="delete"/>
							').'
							</cell>
						</row>
						<row>
							<cell label="Beskrivelse:" right="10">
								<textfield name="alternative" value="'.In2iGui::escape($link->getAlternative()).'"/>
							</cell>
							<cell label="Fil:" width="200">
								<dropdown name="file" adaptive="true" value="'.$link->getFile().'">
									'.GuiUtils::buildObjectItems('file').'
								</dropdown>
							</cell>
							<cell label="E-mail:" width="100">
								<textfield name="email" value="'.In2iGui::escape($link->getEmail()).'"/>
							</cell>
							<cell left="10">
								<button title="Annuller" click="document.location=\'Toolbar.php\'" small="true" rounded="true"/>
							</cell>
						</row>
					</grid>
				</toolbar>
			</tab>
		</tabs>
	</gui>';
} else {
	$gui='
	<gui xmlns="uri:In2iGui" title="Dokument">
		<controller source="js/Toolbar.js"/>
		<script>
		controller.pageId='.InternalSession::getPageId().';
		</script>
		<tabs small="true" below="true">
			<tab title="Dokument" background="light">
				<toolbar>
					<icon icon="common/close" title="Luk" name="close"/>
					<divider/>
					<icon icon="common/internet" overlay="upload" title="Udgiv" name="publish" disabled="'.(Page::isChanged(InternalSession::getPageId()) ? 'false' : 'true').'"/>
					<icon icon="common/view" title="Vis ændringer" name="preview"/>
					<icon icon="common/info" title="Egenskaber" name="properties"/>
					<divider/>
					<icon icon="layout/layout" title="Rediger layout" overlay="edit" name="editLayout"/>
					<divider/>
					<icon icon="common/link" title="Nyt link" overlay="new" name="newLink"/>
					<icon icon="common/link" title="Rediger links" overlay="edit" name="editLinks"/>
				</toolbar>
			</tab>
			<tab title="Avanceret" background="light">
				<toolbar>
					<icon icon="common/time" title="Historik" name="history"/>
				</toolbar>
			</tab>
		</tabs>
	</gui>';
}
In2iGui::render($gui);
exit;
}
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/DevelopmentMode.php';
require_once 'Functions.php';

if (requestGetExists('tab')) {
	setDocumentToolbarTab(requestGetText('tab'));
}
$tab = getDocumentToolbarTab();

$section = getDocumentSection();

$gui=
'<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<dock xmlns="uri:Dock" orientation="Top">'.
'<tabgroup align="left">';
if ($tab=='document') {
	$gui.='<tab title="Dokument" style="Hilited"/>';
}
else {
	$gui.='<tab title="Dokument" link="Toolbar.php?tab=document"/>';
}
if ($tab=='links') {
	$gui.='<tab title="Links" style="Hilited"/>';
}
else {
	$gui.='<tab title="Links" link="Toolbar.php?tab=links"/>';
}
if (DevelopmentMode::isDevelopmentMode()) {
	if ($tab=='advanced') {
		$gui.='<tab title="Avanceret" style="Hilited"/>';
	}
	else {
		$gui.='<tab title="Avanceret" link="Toolbar.php?tab=advanced"/>';
	}
}
$gui.=
'</tabgroup>'.
'<content>';
if ($tab=='links') {
	if (requestGetBoolean('insert')) {
		$gui.=insertLink();	
	}
	elseif (requestGetExists('edit')) {
		$gui.=editLink();	
	}
	else {
		$gui.=linksTab();	
	}
}
else if ($tab=='document') {
	$gui.=documentTab();
}
else if ($tab=='advanced') {
	$gui.=advancedTab();
}
$gui.=
'</content>'.
'</dock>'.
'</xmlwebgui>';

$elements = array("Dock","BarForm","Script");
writeGui($xwg_skin,$elements,$gui);


function documentTab() {
	$pageId = getPageId();
	return
	'<tool title="Luk" icon="Basic/Close" link="../../Tools/Pages/index.php" target="Desktop" help="Lukker redigering af siden og returnerer til oversigten over sider"/>'.
	'<divider/>'.
	(pageIsChanged()
	? '<tool title="Udgiv" icon="Basic/Internet" overlay="Upload" link="Publish.php" badge="!" badgestyle="Hilited" help="Udgiver �ndringer foretaget p� siden"/>'
	: '<tool title="Udgiv" icon="Basic/Internet" overlay="Upload" style="Disabled"/>'
	).
	'<tool title="Vis �ndringer" icon="Basic/View" link="../../Services/Preview/?id='.$pageId.'" target="Desktop" help="Visning af siden som den vil se ud n�r alle �ndringer er udgivet"/>'.
	'<tool title="Egenskaber" icon="Basic/Info" link="../../Tools/Pages/?action=pageproperties&amp;id='.getPageId().'" target="Desktop" help="Vis sidens egenskaber i side-v�rkt�jet"/>'.
	'<divider/>'.
	(getDocumentLayoutMode()
	? '<tool title="Luk layout" icon="Basic/Layout" overlay="Close" link="Editor.php?layoutmode=false" target="Editor" help="Lukker �ndring af layout"/>'
	: '<tool title="Rediger layout" icon="Basic/Layout" overlay="Edit" link="Editor.php?layoutmode=true" target="Editor" help="�ndring af sidens layout samt flytning af afsnit, r�kker og kolonner"/>'
	);
}

function advancedTab() {
	return
	'<tool title="Historik" icon="Basic/Time" link="../../Services/PageHistory/" target="Frame" help="Oversigt over tidligere versioner af dokumentet"/>';
}

function linksTab() {
	return
	'<tool title="Inds�t nyt link" icon="Web/Link" overlay="New" link="Toolbar.php?insert=true"/>'.
	'<tool title="Rediger links" icon="Web/Link" overlay="Edit" link="ListOfLinks.php" target="Frame"/>';
}

function insertLink() {
	$pages=buildPages();
	$files=buildFiles();
	return
	'<form xmlns="uri:BarForm" name="Formula" target="Editor" method="post" action="AddLink.php">'.
	'<group>'.
	'<top>'.
	'<badge>Tekst:</badge>'.
	'<textfield name="text" multiline="true"/>'.
	'<badge>Link til:</badge>'.
	'<combo name="targetType">'.
	'<option title="Side:" value="page">'.
		'<select name="page">'.
		$pages.
		'</select>'.
	'</option>'.
	'<option title="Fil:" value="file">'.
		'<select name="file">'.
		$files.
		'</select>'.
	'</option>'.
	'<option title="Adresse:" value="url">'.
		'<textfield name="url"/>'.
	'</option>'.
	'<option title="E-post:" value="email">'.
		'<textfield name="email"/>'.
	'</option>'.
	'</combo>'.
	'<badge>Destination:</badge>'.
	'<select name="target">'.
	'<option title="Samme ramme" value=""/>'.
	'<option title="Nyt vindue" value="_blank"/>'.
	'<option title="Download" value="_download"/>'.
	'<option title="�verste ramme" value="_top"/>'.
	'<option title="Rammen over" value="_parent"/>'.
	'</select>'.
	'<button title="Inds�t" submit="true" style="Hilited"/>'.
	'</top>'.
	'<bottom>'.
	'<space/>'.
	'<space/>'.
	'<space/>'.
	'<badge>Beskrivelse:</badge>'.
	'<textfield name="alternative"/>'.
	'<button title="Annuller" link="Toolbar.php"/>'.
	'</bottom>'.
	'</group>'.
	'</form>'.
	'<script xmlns="uri:Script">
	function getSelection()
	{
		doc = parent.Frame.EditorFrame.getDocument();
		if (doc.getSelection) txt = doc.getSelection();
		else if (doc.selection) txt = doc.selection.createRange().text;
		else return;

		document.forms.Formula.text.value = txt;
	}
	getSelection();
	</script>';
}

function editLink() {
	$id=requestGetNumber('edit');
	$sql="select * from link where id=".$id;
	$row = Database::selectFirst($sql);
	$pages=buildPages();
	$files=buildFiles();

	return
	'<form xmlns="uri:BarForm" name="Formula" target="Editor" method="post" action="UpdateLink.php">'.
	'<hidden name="id">'.$id.'</hidden>'.
	'<group>'.
	'<top>'.
	'<badge>Tekst:</badge>'.
	'<textfield name="text" multiline="true">'.encodeXML($row['source_text']).'</textfield>'.
	'<badge>Link til:</badge>'.
	'<combo name="targetType" selected="'.$row['target_type'].'">'.
	'<option title="Side:" value="page">'.
		'<select name="page" selected="'.($row['target_type']=='page' ? $row['target_id'] : 0).'">'.
		$pages.
		'</select>'.
	'</option>'.
	'<option title="Fil:" value="file">'.
		'<select name="file" selected="'.($row['target_type']=='file' ? 'true' : 'false').'">'.
		$files.
		'</select>'.
	'</option>'.
	'<option title="Adresse:" value="url">'.
		'<textfield name="url">'.($row['target_type']=='url' ? encodeXML($row['target_value']) : '').'</textfield>'.
	'</option>'.
	'<option title="E-post:" value="email">'.
		'<textfield name="email">'.($row['target_type']=='email' ? encodeXML($row['target_value']) : '').'</textfield>'.
	'</option>'.
	'</combo>'.
	'<badge>Destination:</badge>'.
	'<select name="target" selected="'.$row['target'].'">'.
	'<option title="Samme ramme" value=""/>'.
	'<option title="Nyt vindue" value="_blank"/>'.
	'<option title="Download" value="_download"/>'.
	'<option title="�verste ramme" value="_top"/>'.
	'<option title="Rammen over" value="_parent"/>'.
	'</select>'.
	'<button title="Opdater" submit="true" style="Hilited"/>'.
	'</top>'.
	'<bottom>'.
	'<space/>'.
	'<space/>'.
	'<space/>'.
	'<badge>Beskrivelse:</badge>'.
	'<textfield name="alternative">'.encodeXML($row['alternative']).'</textfield>'.
	'<button title="Annuller" link="Toolbar.php"/>'.
	'</bottom>'.
	'</group>'.
	'</form>';
}

function buildPages() {
	$output="";
	$sql="select id,title from page order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.encodeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}

function buildFiles() {
	$output="";
	$sql="select id,title from object where type='file' order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.encodeXML(shortenString($row['title'],20)).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}

function pageIsChanged() {
	$output=false;
	$pageId = getPageId();
	$sql="select changed-published as delta from page where id=".$pageId;
	$row = Database::selectFirst($sql);
	if ($row['delta']>0) {
		$output=true;
	} 
	return $output;
}
?>