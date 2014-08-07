<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class UI {
	
	static function renderFragment($gui) {
        return In2iGui::renderFragment($gui);
    }
    
    static function buildAbstractUI($xml) {
        $doc = DOMUtils::parse($xml);
        
        $root = $doc->documentElement;
        
        $children = DOMUtils::getChildElements($root);
        
        $gui = '<formula><group>';
        
        foreach ($children as $child) {
            if ($child->tagName=='text') {
                $gui.='<field label="' . Strings::escapeXML($child->getAttribute('label')) . '">';
                $gui.='<text-input key="' . Strings::escapeXML($child->getAttribute('key')) . '"/>';
                $gui.='</field>';
            }
        }
        
        $gui .= '</group></formula>';
        return UI::renderFragment($gui);
    }
}