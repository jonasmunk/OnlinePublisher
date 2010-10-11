<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Parts/HeaderPart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class HeaderPartController extends PartController
{
	function HeaderPartController() {
		parent::PartController('header');
	}
	
	function createPart() {
		$part = new HeaderPart();
		$part->setText('Velkommen');
		$part->setLevel(1);
		$part->save();
		return $part;
	}
	
	function getFromRequest($id) {
		$part = HeaderPart::load($id);
		$part->setText(Request::getEncodedString('text'));
		// Until Ajax posts all vars
		if (Request::exists('level')) {
			$part->setLevel(Request::getInt('level'));
			$part->setFontSize(Request::getString('fontSize'));
			$part->setFontFamily(Request::getString('fontFamily'));
			$part->setTextAlign(Request::getString('textAlign'));
			$part->setLineHeight(Request::getString('lineHeight'));
			$part->setColor(Request::getString('color'));
			$part->setLetterSpacing(Request::getString('letterSpacing'));
			$part->setFontWeight(Request::getString('fontWeight'));
			$part->setFontStyle(Request::getString('fontStyle'));
			$part->setWordSpacing(Request::getString('wordSpacing'));
			$part->setTextIndent(Request::getString('textIndent'));
			$part->setTextTransform(Request::getString('textTransform'));
			$part->setFontVariant(Request::getString('fontVariant'));
			$part->setTextDecoration(Request::getString('textDecoration'));
		}
		return $part;
	}
	
	function buildSub($part,$context) {
		$text = $part->getText();
		$text = StringUtils::escapeSimpleXML($text);
		$text = $context->decorateForBuild($text);
		$text = StringUtils::insertLineBreakTags($text,'<break/>');
		return 
			'<header level="'.$part->getLevel().'" xmlns="'.$this->getNamespace().'">'.
			$this->buildXMLStyle($part).
			$text.
			'</header>';
	}
	
		
	function getToolbars() {
		return array(
			'Punktopstilling' =>
			'
			<dropdown label="Niveau" name="level">
				<item value="1" title="Niveau 1"/>
				<item value="2" title="Niveau 2"/>
				<item value="3" title="Niveau 3"/>
				<item value="4" title="Niveau 4"/>
				<item value="5" title="Niveau 5"/>
				<item value="6" title="Niveau 6"/>
			</dropdown>
			<style-length label="St&#248;rrelse" name="fontSize"/>
			<segmented label="Placering" name="textAlign" allow-null="true">
				<item icon="style/text_align_left" value="left"/>
				<item icon="style/text_align_center" value="center"/>
				<item icon="style/text_align_right" value="right"/>
				<item icon="style/text_align_justify" value="justify"/>
			</segmented>
			<divider/>
			<dropdown label="Skrift" name="fontFamily" width="180">
				<item value="" title=""/>
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
			</dropdown>
			<style-length label="Linjeh&#248;jde" name="lineHeight"/>
			<textfield label="Farve" name="color" width="60"/>
			<segmented label="Fed" name="fontWeight" allow-null="true">
				<item icon="style/text_normal" value="normal"/>
				<item icon="style/text_bold" value="bold"/>
			</segmented>
			<segmented label="Kursiv" name="fontStyle" allow-null="true">
				<item icon="style/text_normal" value="normal"/>
				<item icon="style/text_italic" value="italic"/>
			</segmented>',
			
		'Avanceret' =>
			'
			<style-length label="Ord-mellemrum" name="wordSpacing"/>
			<style-length label="Tegn-mellemrum" name="letterSpacing"/>
			<style-length label="Indrykning" name="textIndent"/>
			<segmented label="Bogstaver" name="textTransform" allow-null="true">
				<item icon="style/text_normal" value="normal"/>
				<item icon="style/text_transform_capitalize" value="capitalize"/>
				<item icon="style/text_transform_uppercase" value="uppercase"/>
				<item icon="style/text_transform_lowercase" value="lowercase"/>
			</segmented>
			<segmented label="Variant" name="fontVariant" allow-null="true">
				<item icon="style/font_variant_normal" value="normal"/>
				<item icon="style/font_variant_smallcaps" value="small-caps"/>
			</segmented>
			<segmented label="Variant" name="textDecoration" allow-null="true">
				<item icon="style/text_normal" value="none"/>
				<item icon="style/text_decoration_underline" value="underline"/>
				<item icon="style/text_decoration_linethrough" value="line-through"/>
				<item icon="style/text_decoration_overline" value="overline"/>
			</segmented>
			'
			);
	}
}
?>