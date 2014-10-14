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
        $part->setDepth(1);
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
			'variant' => $part->getVariant(),
			'depth' => $part->getDepth()
        )).
		'<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/menu/editor.js" type="text/javascript" charset="utf-8"></script>';
	}

	function getFromRequest($id) {
		$part = MenuPart::load($id);
		$part->setHierarchyId(Request::getInt('hierarchyId'));
		$part->setVariant(Request::getString('variant'));
		$part->setDepth(Request::getInt('depth'));
		return $part;
	}

	function buildSub($part,$context) {
		$xml='<menu xmlns="'.$this->getNamespace().'"' .
            ' hierarchy-id="' . Strings::escapeXML($part->getHierarchyId()) . '"' .
            ' variant="' . Strings::escapeXML($part->getVariant()) . '"' .
            ' depth="' . Strings::escapeXML($part->getDepth()) . '"' .
            '>';
        $depth = $part->getDepth() > 0 ? $part->getDepth() : 100;
        $pageIds = PartService::getPageIdsForPart($part->getId());
        if (count($pageIds) > 0) {
            $pageId = $pageIds[0];
            $item = HierarchyService::getItemByPageId($pageId);
            if ($item) {
                $xml.= '<items>';
                $xml.=HierarchyService::hierarchyTraveller($item->getHierarchyId(),$item->getId(),false,$depth);
                $xml.= '</items>';
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
            $part->setDepth(intval($menu->getAttribute('depth')));
        }
	}

	function getToolbars() {
		return array(
			GuiUtils::getTranslated(array('Menu','da'=>'Menu')) =>
			'<script source="../../Parts/menu/toolbar.js"/>
			<icon icon="common/info" title="{Info; da:Info}" name="info"/>
			<divider/>
			<field label="{Variant; da:Variant}">
				<dropdown name="variant" width="120">
                    <item value="" text="Default"/>
                    <item value="dropdown" text="Drop down"/>
                    <item value="bar" text="Bar"/>
                    <item value="tree" text="Tree"/>
                </dropdown>
			</field>
			<field label="{Depth; da:Dybde}">
				<number-input name="depth" min="0" max="20" width="60"/>
			</field>
		'
		);
	}
}
?>