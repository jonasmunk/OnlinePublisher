<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class RichtextPartController extends PartController
{
	function RichtextPartController() {
		parent::PartController('richtext');
	}
	
	function createPart() {
		$part = new RichtextPart();
		$part->setHtml('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>');
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
		
	function editor($part,$context) {
		$modern = SettingService::getSetting('part','richtext','experimetal');
		if ($modern) {
			return
			'<div id="part_richtext">'.$this->render($part,$context).'</div>'.
			'<input type="hidden" name="html" value="'.StringUtils::escapeXML(StringUtils::fromUnicode($part->getHtml())).'"/>'.
			'<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/richtext/script.js" type="text/javascript" charset="utf-8"></script>';
		} else {
			return
			'<textarea class="Part-richtext" id="PartRichtextTextarea" name="html" style="width: 100%; height: 250px;">'.
			StringUtils::escapeXML($part->getHtml()).
			'</textarea>'.
			'<script language="javascript" type="text/javascript" src="'.ConfigurationService::getBaseUrl().'Editor/Libraries/tinymce/tiny_mce.js"></script>
			<script language="javascript" type="text/javascript">
			tinyMCE.init({
				mode : "textareas",
				theme : "advanced",
				entity_encoding : "numeric",
				convert_fonts_to_spans : true,
				language : "en",
				content_css : "'.ConfigurationService::getBaseUrl().'style/'.$context->getDesign().'/editors/'.$context->getTemplate().'_richtext.css",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_path_location : "bottom", //preview,emotions,iespell,flash,advimage,
				plugins : "table,save,advhr,advlink,insertdatetime,zoom,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable",
				//theme_advanced_buttons1_add_before : "save,newdocument,separator",
				theme_advanced_buttons1 : "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator,undo,redo,separator,formatselect,fontselect,fontsizeselect",
				theme_advanced_buttons2 : "bold,italic,underline,separator,justifyleft,justifycenter,justifyright,jusityfull,separator,bullist,numlist,separator,indent,outdent,separator,forecolor,backcolor,separator,sub,sup,separator,hr,advhr,separator,link,unlink,anchor", // preview,
				theme_advanced_buttons3 : "visualaid,tablecontrols,separator,insertdate,inserttime,charmap,separator,fullscreen,removeformat,cleanup,code",
				theme_advanced_disable : "image,help,styleselect",
				plugin_insertdate_dateFormat : "%d-%m-%Y",
				plugin_insertdate_timeFormat : "%H:%M:%S",
				extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
				theme_advanced_resize_horizontal : false,
				theme_advanced_resizing : true
			});
			</script>';
		}
	}
	
	function getFromRequest($id) {
		$part = RichtextPart::load($id);
		$part->setHtml(Request::getString('html'));
		return $part;
	}
	
	function buildSub($part,$context) {
		$html = $part->getHtml();
		if (DOMUtils::isValidFragment(StringUtils::toUnicode($html))) {
			return '<richtext xmlns="'.$this->getNamespace().'" valid="true">'.
			$html.
			'</richtext>';
		} else {
			Log::debug('RichtextPartController: The markup is invalid...');
			Log::debug($html);
			return 
			'<richtext xmlns="'.$this->getNamespace().'" valid="false">'.
			'<![CDATA['.$html.']]>'.
			'</richtext>';
		}
	}
	
	function importSub($node,$part) {
		if ($richtext = DOMUtils::getFirstDescendant($node,'richtext')) {
			if ($richtext->getAttribute('valid')=='false') {
				$part->setHtml(DOMUtils::getText($richtext));
			} else {
				$str = DOMUtils::getInnerXML($richtext);
				$str = DOMUtils::stripNamespaces($str);
				$part->setHtml($str);
			}
		}
		
	}
}
?>