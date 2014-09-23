<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class MenuPartController extends PartController
{
	function MenuPartController() {
		parent::PartController('menu');
	}
	
	static function createPart() {
		$part = new MenuPart();
		$part->setHierarchyId(HierarchyService::getLatestId());
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		return '<div id="part_menu_container">'.$this->render($part,$context).'</div>'.

		$this->buildHiddenFields(array(
			'hierarchyId' => $part->getHierarchyId(),
			'variant' => $part->getVariant())).
		'<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/menu/editor.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function getFromRequest($id) {
		$part = MenuPart::load($id);
		$part->setHierarchyId(Request::getInt('hierarchyId'));
		$part->setVariant(Request::getString('variant'));
		return $part;
	}
	
	function buildSub($part,$context) {
		$xml='<menu xmlns="'.$this->getNamespace().'"' .
            ' hierarchy-id="' . Strings::escapeXML($part->getHierarchyId()) . '"' .
            ' variant="' . Strings::escapeXML($part->getVariant()) . '"' .
            '>';
        
        $pageIds = PartService::getPageIdsForPart($part->getId());
        if (count($pageIds) > 0) {
            $pageId = $pageIds[0];
            $item = HierarchyService::getItemByPageId($pageId);
            if ($item) {
                $xml.='<items>';
                $xml.='<item text="' . Strings::escapeXML($item->getTitle()) . '"/>';
                $xml.='</items>';                
            }
        }

		$xml.='</menu>';
		return $xml;
	}
	
	function importSub($node,$part) {
        $menu = DOMUtils::getFirstChildElement($node,'menu');
        if ($menu) {
            $part->setHierarchyId(intval($menu->getAttribute('hierarchy-id')));
            $part->setVariant($menu->getAttribute('variant'));            
        }
	}
	
	
	function getToolbars() {
		return array(
			GuiUtils::getTranslated(array('File','ad'=>'Fil')) =>
			'<script source="../../Parts/menu/toolbar.js"/>
			<icon icon="common/info" title="{Info; da:Info}" name="info"/>
			<divider/>
			<field label="{Variant; da:Variant}">
				<dropdown name="variant" width="200"/>
			</field>
		'
		);
	}
	
	
	
	function editorGui($part,$context) {
		$gui='
		<window title="{Add file; da:Tilføj fil}" name="fileUploadWindow" width="300" padding="10">
			<upload name="fileUpload" url="../../Parts/file/Upload.php" widget="upload">
				<placeholder 
					title="{Select a file on your computer; da:Vælg en fil på din computer...}" 
					text="{The file size can at most be; da:Filens størrelse må højest være} '.GuiUtils::bytesToString(FileSystemService::getMaxUploadSize()).'."/>
			</upload>
			<buttons align="center" top="10">
				<button name="cancelUpload" title="{Close; da:Luk}"/>
				<button name="upload" title="{Select file...; da:Vælg fil...}" highlighted="true"/>
			</buttons>
		</window>
		';
		return UI::renderFragment($gui);
	}
}
?>