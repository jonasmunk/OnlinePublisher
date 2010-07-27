<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/TextDecorator.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PartContext {
	
	var $displayLinks;
	var $buildLinks;
	var $template;
	var $design;
	var $displayDecorator;
	var $buildDecorator;
	
	function PartContext() {
		$this->displayLinks = array();
		$this->buildLinks = array();
		$this->template = 'document';
		$this->design = 'in2it';
		$this->displayDecorator = new TextDecorator();
		$this->displayDecorator->setEmailTags('<a href="#" class="common"><span>','</span></a>');
		$this->displayDecorator->setHttpTags('<a href="{subject}" class="common" target="_blank"><span>','</span></a>');
		$this->displayDecorator->addTag('s','strong');
		$this->displayDecorator->addTag('e','em');
		$this->displayDecorator->addTag('slet','del');
		$this->buildDecorator = new TextDecorator();
		$this->buildDecorator->setEmailTags('<link email="{subject}">','</link>');
		$this->buildDecorator->setHttpTags('<link url="{subject}">','</link>');
		$this->buildDecorator->addTag('s','strong');
		$this->buildDecorator->addTag('e','em');
		$this->buildDecorator->addTag('slet','del');
	}
	
	function setTemplate($template) {
	    $this->template = $template;
	}
	
	function getTemplate() {
	    return $this->template;
	}
	
	function setDesign($design) {
	    $this->design = $design;
	}
	
	function getDesign() {
	    return $this->design;
	}

	function decorateForDisplay($text) {
		return $this->displayDecorator->decorate($text);
	}

	function decorateForBuild($text) {
		return $this->buildDecorator->decorate($text);
	}

	/////////////////////////////// Links ///////////////////////////
	
	function addDisplayLink($text,$href,$target,$class,$title) {
		$this->displayLinks[] = array('text' => $text, 'href' => $href , 'target' => $target, 'class' => $class, 'title' => $title);
		$this->displayDecorator->addReplacement($text,'<a href="'.$href.'" target="'.$target.'" class="'.$class.'" title="'.$title.'"><span>','</span></a>');
	}
	
	function addBuildLink($text,$type,$id,$value,$target,$title,$path) {
		$this->buildLinks[] = array('text' => $text, 'type' => $type, 'id' => $id, 'value' => $value , 'target' => $target, 'title' => $title);
		$atts='';
		if ($type=='url') {
			$atts.=' url="'.StringUtils::escapeNumericXML($value).'"';
		}
		else if ($type=='page') {
			$atts.=' page="'.$id.'"';
		}
		else if ($type=='email') {
			$atts.=' email="'.$value.'"';
		}
		else if ($type=='file') {
			$atts.=' file="'.$id.'"';
		}
		if ($target!='') {
			$atts.=' target="'.$target.'"';
		}
		if ($path!='') {
			$atts.=' path="'.$path.'"';
		}
		$this->buildDecorator->addReplacement($text,'<link'.$atts.'>','</link>');
	}
}
?>