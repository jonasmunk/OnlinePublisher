<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class FontService {
	
	
	static function getFontItems() {
		return '<item value="" title=""/>
				<item value="sans-serif" title="*Sans-serif*"/>
				<item value="Verdana,sans-serif" title="Verdana"/>
				<item value="Tahoma,Geneva,sans-serif" title="Tahoma"/>
				<item value="Trebuchet MS,Helvetica,sans-serif" title="Trebuchet"/>
				<item value="Geneva,Tahoma,sans-serif" title="Geneva"/>
				<item value="Helvetica,sans-serif" title="Helvetica"/>
				<item value="Arial,Helvetica,sans-serif" title="Arial"/>
				<item value="Arial Black,Gadget,Arial,sans-serif" title="Arial Black"/>
				<item value="Impact,Charcoal,Arial Black,Gadget,Arial,sans-serif" title="Impact"/>
				<item value="serif" title="*Serif*"/>
				<item value="Times New Roman,Times,serif" title="Times New Roman"/>
				<item value="Times,Times New Roman,serif" title="Times"/>
				<item value="Book Antiqua,Palatino,serif" title="Book Antiqua"/>
				<item value="Palatino,Book Antiqua,serif" title="Palatino"/>
				<item value="Georgia,Book Antiqua,Palatino,serif" title="Georgia"/>
				<item value="Garamond,Times New Roman,Times,serif" title="Garamond"/>
				<item value="cursive" title="*Kursiv*"/>
				<item value="Comic Sans MS,cursive" title="Comic Sans"/>
				<item value="monospace" title="*Monospace*"/>
				<item value="Courier New,Courier,monospace" title="Courier New"/>
				<item value="Courier,Courier New,monospace" title="Courier"/>
				<item value="Lucida Console,Monaco,monospace" title="Lucida Console"/>
				<item value="Monaco,Lucida Console,monospace" title="Monaco"/>
				<item value="fantasy" title="*Fantasi*"/>
				<item value="\'Cabin Sketch\', arial, serif;" title="Cabin Sketch"/>
				<item value="\'Just Me Again Down Here\', arial, serif;" title="Just Me Again Down Here"/>
				<item value="\'Droid Sans\', arial, serif;" title="Droid Sans"/>
				<item value="\'Crimson Text\', arial, serif;" title="Crimson Text"/>
				<item value="\'Luckiest Guy\', arial, serif;" title="Luckiest Guy"/>
				<item value="\'Dancing Script\', arial, serif;" title="Dancing Script"/>
		';
	}
}