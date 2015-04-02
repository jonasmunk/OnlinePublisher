<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class WidgetPartController extends PartController
{
	function WidgetPartController() {
		parent::PartController('widget');
	}

	function createPart() {
		$part = new WidgetPart();
		$part->save();
		return $part;
	}

    function renderUsingDesign() {
        return true;
    }

	function display($part,$context) {
		return $this->render($part,$context);
	}

	function editor($part,$context) {
		return '<div id="part_widget_container">'.$this->render($part,$context).'</div>'.
        $this->buildHiddenFields([
			'key' => $part->getKey(),
			'data' => $part->getData()
        ]).
        $this->getEditorScript();
	}

	function getFromRequest($id) {
		$part = WidgetPart::load($id);
		$part->setKey(Request::getString('key'));
		$part->setData(Request::getString('data'));
		return $part;
	}

	function buildSub($part,$context) {
		$xml = '<widget xmlns="'.$this->getNamespace().'" key="'.Strings::escapeXML($part->getKey()).'">';
        if (DOMUtils::isValidFragment($part->getData())) {
            $xml.= $part->getData();
        }
		$xml.= '</widget>';
		return $xml;
	}

	function importSub($node,$part) {
        if ($widget = DOMUtils::getFirstChildElement($node,'widget')) {
            $part->setKey($widget->getAttribute('key'));
            $data = DOMUtils::getInnerXML($widget);
            $data = DOMUtils::stripNamespaces($data);
            $part->setData($data);
        }
	}


	function getToolbars() {
		return array(
			GuiUtils::getTranslated(array('Widget','da'=>'Widget')) =>
			'<script source="../../Parts/widget/toolbar.js"/>
		'
		);
	}

	function editorGui($part,$context) {
		$gui='
		<window title="{Widget; da:Widget}" icon="common/info" name="widgetDataWindow" width="400" padding="5" close="false">
            <formula name="widgetDataFormula">
                <fields labels="above">
                    <!--
                    <field label="Type">
            			<dropdown key="key">
                            <item value="key" text="Hey"/>
                        </dropdown>
                        <text-input key="key"/>
                    </field>-->
                    <field>
                        <code-input key="data"/>
                    </field>
                </fields>
            </formula>
		</window>
		';
		return UI::renderFragment($gui);
	}
}
?>