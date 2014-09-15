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

	function isLiveEnabled() {
		return true;
	}

	function display($part,$context) {
		return $this->render($part,$context);
	}

	function editor($part,$context) {
		$modern = SettingService::getSetting('part','richtext','experimetal');
		if ($modern) {
			return
			'<div id="part_richtext">'.$this->render($part,$context).'</div>'.
			'<input type="hidden" name="html" value="'.Strings::escapeXML(Strings::fromUnicode($part->getHtml())).'"/>'.
			'<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/richtext/script.js" type="text/javascript" charset="utf-8"></script>';
		} else {
			return
			'<textarea class="Part-richtext" id="PartRichtextTextarea" name="html" style="width: 100%; height: 250px;">'.
			Strings::escapeXML($part->getHtml()).
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
	
	function beforeSave($part) {
        $existing = LinkService::getPartLinks($part->getId());
        $linksFound = [];
        $linksById = [];
        foreach ($existing as $link) {
            $linksById[$link->getId()] = $link;
        }
		$doc = DOMUtils::parseHTMLFragment($part->getHtml());
		$tags = $doc->getElementsByTagName('a');
		for ($i=$tags->length-1; $i >= 0; $i--) {
			$tag = $tags->item($i);
            $data = Strings::fromJSON($tag->getAttribute('data'));
            if (isset($data->id) && isset($linksById[$data->id])) {
                $link = $linksById[$data->id];
                unset($linksById[$data->id]);
            } else {
                $link = new PartLink();
            }
            $link->setPartId($part->getId());
            $link->setSourceText($tag->textContent);
            if (isset($data->page)) {
                $link->setTargetType('page');
                $link->setTargetValue($data->page);
            }
            else if (isset($data->file)) {
                $link->setTargetType('file');
                $link->setTargetValue($data->file);
            }
            else if (isset($data->url)) {
                $link->setTargetType('url');
                $link->setTargetValue($data->url);
            }
            else if (isset($data->email)) {
                $link->setTargetType('email');
                $link->setTargetValue($data->email);
            }
            $link->save();
            $data->id = $link->getId();
            $tag->setAttribute('data',Strings::toJSON($data));
		}
        $html = DOMUtils::getInnerXML($doc->documentElement);
        $part->setHtml($html);
        
        foreach ($linksById as $id => $link) {
            $link->remove();
        }
        return true;
	}

	function _convert($html) {
		//$html = str_replace(['<br>','&quot;','&nbsp;'], ['<br/>','&#34;','&#160;'], $html);

		$doc = DOMUtils::parseHTMLFragment($html);
		if (!$doc) {
			Log::debug('Unable to parse!');
			return $html;
		}
		$links = $doc->getElementsByTagName('a');
		$linkArray = [];

		for ($i=$links->length-1; $i >= 0; $i--) {
			$linkArray[] = $links->item($i);
		}
		foreach ($linkArray as $link) {
			$link->removeAttribute('href');
			$data = $link->getAttribute('data');
			$obj = Strings::fromJSON($data);

			$replaced = $doc->createElement('link');
			if (isset($obj->page) && !empty($obj->page)) {
                $pageId = intval($obj->page);
                if ($pageId > 0) {
                    $path = PageService::getPath($pageId);
    				$replaced->setAttribute('page',$obj->page);
                    if (!empty($path)) {
        				$replaced->setAttribute('path',$path);
                    }
                }
			}
			if (isset($obj->file) && !empty($obj->file)) {
				$replaced->setAttribute('file',$obj->file);
			}
			if (isset($obj->image) && !empty($obj->image)) {
				$replaced->setAttribute('image',$obj->image);
			}
			if (isset($obj->url) && !empty($obj->url)) {
				$replaced->setAttribute('url',$obj->url);
			}
			if (isset($obj->email) && !empty($obj->email)) {
				$replaced->setAttribute('email',$obj->email);
			}
			$replaced->setAttribute('data',$data);

			for ($j=0; $j < $link->childNodes->length; $j++) {
				$child = $link->childNodes->item($j);
				$link->removeChild($child);
				$replaced->appendChild($child);
			}

			$link->parentNode->replaceChild($replaced,$link);
		}

		$html = DOMUtils::getInnerXML($doc->documentElement);
		return $html;
	}

	function buildSub($part,$context) {
		$html = $part->getHtml();
		$html = $this->_convert($html);
    	//$html = MarkupUtils::htmlToXhtml($html);
		if (DOMUtils::isValidFragment(Strings::toUnicode($html))) {
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