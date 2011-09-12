<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/TextDecorator.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PartContext {
	
	//var $displayLinks;
	//var $buildLinks;
	var $template;
	var $design;
	var $buildDecorator;
	var $indexDecorator;
	var $synchronize;
	
	function PartContext() {
		$this->displayLinks = array();
		//$this->buildLinks = array();
		$this->template = 'document';
		$this->design = 'in2it';
		$this->buildDecorator = new TextDecorator();
		$this->buildDecorator->setEmailTags('<link email="{subject}">','</link>');
		$this->buildDecorator->setHttpTags('<link url="{subject}">','</link>');
		$this->buildDecorator->addTag('s','strong');
		$this->buildDecorator->addTag('e','em');
		$this->buildDecorator->addTag('slet','del');
		$this->indexDecorator = new TextDecorator();
		$this->indexDecorator->addTag('s',null);
		$this->indexDecorator->addTag('e',null);
		$this->indexDecorator->addTag('slet',null);
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
	
	function setSynchronize($synchronize) {
	    $this->synchronize = $synchronize;
	}

	function getSynchronize() {
	    return $this->synchronize;
	}

	function decorateForBuild($text,$partId=null) {
		return $this->buildDecorator->decorate($text,$partId);
	}

	function decorateForIndex($text) {
		return $this->indexDecorator->decorate($text);
	}

	/////////////////////////////// Links ///////////////////////////
	
	function addBuildLink($text,$type,$id,$value,$target,$title,$path,$linkId=0,$partId=null) {
		//$this->buildLinks[] = array('text' => $text, 'type' => $type, 'id' => $id, 'value' => $value , 'target' => $target, 'title' => $title, 'linkId' => $linkId, 'partId' => $partId);
		$atts='';
		if ($type=='url') {
			$atts.=' url="'.StringUtils::escapeXML($value).'"';
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
		if (StringUtils::isNotBlank($title)) {
			$atts.=' title="'.StringUtils::escapeXML($title).'"';
		}
		if ($linkId>0) {
			$atts.=' id="'.$linkId.'"';
		}
		if ($partId>0) {
			$atts.=' part-id="'.$partId.'"';
		}
		if ($partId!=null) {
			Log::debug('Build link added: '.$partId);
		}
		$this->buildDecorator->addReplacement($text,'<link'.$atts.'>','</link>',$partId);
	}
}
?>