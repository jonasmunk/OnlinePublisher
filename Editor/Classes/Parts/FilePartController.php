<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/FilePart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
require_once($basePath.'Editor/Classes/Services/FileService.php');

class FilePartController extends PartController
{
	function FilePartController() {
		parent::PartController('file');
	}
	
	function createPart() {
		$part = new FilePart();
		$part->setFileId(FileService::getLatestFileId());
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		global $baseUrl;
		return '<div id="part_file_container">'.$this->render($part,$context).'</div>'.
		'<input type="hidden" name="fileId" value="'.$part->getFileId().'"/>'.
		'<script src="'.$baseUrl.'Editor/Parts/file/script.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function getFromRequest($id) {
		$fileId = Request::getInt('fileId');
		$part = FilePart::load($id);
		$part->setFileId($fileId);
		return $part;
	}
	
	function buildSub($part,$context) {
		$xml='<file xmlns="'.$this->getNamespace().'">';
		$sql="select object.data,file.type from object,file where file.object_id = object.id and object.id=".Database::int($part->getFileId());
		if ($row = Database::selectFirst($sql)) {
			$xml.='<info type="'.FileService::mimeTypeToLabel($row['type']).'"/>';
			$xml.=$row['data'];
		}
		$xml.='</file>';
		return $xml;
	}
	
	function importSub($node,$part) {
		if ($object = DOMUtils::getFirstDescendant($node,'object')) {
			if ($id = intval($object->getAttribute('id'))) {
				$part->setFileId($id);
			}
		}
	}
	
	
	function getToolbars() {
		return array(
			'Fil' =>
			'<script source="../../Parts/file/toolbar.js"/>
			<icon icon="common/new" title="Tilføj fil" name="addFile"/>
			<icon icon="common/search" title="V&#230;lg fil" name="chooseFile"/>
		'
		);
	}
	
	
	
	function editorGui($part,$context) {
		$gui='
		<window title="Tilføj fil" name="fileUploadWindow" width="300" padding="10">
			<upload name="fileUpload" url="../../Parts/file/Upload.php" widget="upload">
				<placeholder title="Vælg en fil på din computer..." text="Filens størrelse må højest være '.GuiUtils::bytesToString(FileSystemService::getMaxUploadSize()).'."/>
			</upload>
			<buttons align="center" top="10">
				<button name="cancelUpload" title="Luk"/>
				<button name="upload" title="Vælg fil..." highlighted="true"/>
			</buttons>
		</window>
		<!--
		<window title="Avanceret" name="imageAdvancedWindow" width="300">
			<formula name="imageAdvancedFormula">
				<group>
					<text label="Tekst" multiline="true" key="text"/>
					<checkbox key="greyscale" label="Gråtone"/>
					<dropdown label="Ramme" key="frame">
						<item title="Ingen" value=""/>
						<item title="Let" value="light"/>
						<item title="Elegant" value="elegant"/>
						<item title="Skygge" value="shadow_slant"/>
					</dropdown>
					<buttons>
						<button name="pasteImage" text="Indsæt fra udklipsholder"/>
					</buttons>
				</group>
			</formula>
		</window>
		-->
		';
		return In2iGui::renderFragment($gui);
	}
	
	function setLatestUploadId($id) {
		$_SESSION['part.file.latest_upload_id'] = $id;
	}
	
	function getLatestUploadId() {
		return $_SESSION['part.file.latest_upload_id'];
	}	
}
?>