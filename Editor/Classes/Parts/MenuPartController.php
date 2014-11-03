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

	function isLiveEnabled() {
		return !true;
	}

	static function createPart() {
		$part = new MenuPart();
		$part->setHierarchyId(0);
        $part->setDepth(1);
		$part->save();
		return $part;
	}

	function display($part,$context) {
		return $this->render($part,$context);
	}

	function editor($part,$context) {
		return '<div id="part_menu_container">'.$this->render($part,$context).'</div>'.

        $this->buildHiddenFields([
			'hierarchyId' => $part->getHierarchyId(),
			'variant' => $part->getVariant(),
			'header' => $part->getHeader(),
			'depth' => $part->getDepth()
        ]).
		'<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/menu/editor.js" type="text/javascript" charset="utf-8"></script>';
	}

	function getFromRequest($id) {
		$part = MenuPart::load($id);
		$part->setHierarchyId(Request::getInt('hierarchyId'));
		$part->setVariant(Request::getString('variant'));
		$part->setDepth(Request::getInt('depth'));
		$part->setHeader(Request::getString('header'));
		return $part;
	}

	function buildSub($part,$context) {
		$xml='<menu xmlns="'.$this->getNamespace().'"' .
            ' hierarchy-id="' . Strings::escapeXML($part->getHierarchyId()) . '"' .
            ' variant="' . Strings::escapeXML($part->getVariant()) . '"' .
            ' depth="' . Strings::escapeXML($part->getDepth()) . '"' .
            '>';
        
        if (Strings::isNotBlank($part->getHeader())) {
            $xml.= '<header>' . Strings::escapeXML($part->getHeader()) . '</header>';
        }
        
        $depth = $part->getDepth() > 0 ? $part->getDepth() : 100;
        
        $hierarchyId = $part->getHierarchyId();
        $itemId = 0;
        
        if ($hierarchyId == 0) {
            $pageIds = PartService::getPageIdsForPart($part->getId());
            if (count($pageIds) > 0) {
                $pageId = $pageIds[0];
                $item = HierarchyService::getItemByPageId($pageId);
                $hierarchyId = $item->getHierarchyId();
                $itemId = $item->getId();
            }            
        }
        if ($hierarchyId > 0) {
            $xml.= '<items>';
            $xml.= HierarchyService::hierarchyTraveller($hierarchyId,$itemId,false,$depth);
            $xml.= '</items>';
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
            $part->setHeader(DOMUtils::getFirstChildText($menu,'header'));
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
                    <item value="tree" text="Tree"/>
                    <item value="bar" text="Bar"/>
                    <item value="dropdown" text="Drop down"/>
                </dropdown>
			</field>
			<field label="{Depth; da:Dybde}">
				<number-input name="depth" min="0" max="20" width="60"/>
			</field>
		'
		);
	}
  
	function editorGui($part,$context) {
		$gui='
		<window title="Menu" name="partMenuWindow" width="300" close="true" padding="5">
            ' . $this->_getFormula() . '
        </window>';
		return UI::renderFragment($gui);
    }
    
    function _getFormula() {
        return '
            <formula name="partMenuFormula">
                <fields>
                    <field label="{Header;da:Overskrift}">
                        <text-input key="header"/>
                    </field>
                    <field label="{Hierarchy;da:Hierarki}">
                        <dropdown key="hierarchyId">
                            <item text="{- Same as page -; da:- Samme som side -}" value="0"/>
                            ' . $this->_getHierarchyItems() . '
                        </dropdown>
                    </field>
                </fields>
            </formula>
        ';
    }
    
    function _getHierarchyItems() {
        $gui = '';
        $hiers = Hierarchy::search();
        foreach ($hiers as $hierarchy) {
            $gui.= '<item value="' . $hierarchy->getId() . '" title="' . $hierarchy->getName() . '"/>';
        }
        return $gui;
    }
    
	function getUI() {
		return array(
			array(
				'icon' => 'monochrome/text',
				'key' => 'menu',
				'body' => _getFormula()
			)
		);
	}
}
?>