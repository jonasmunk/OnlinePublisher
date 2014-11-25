<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class PartContext {
	
	//var $displayLinks;
	//var $buildLinks;
	var $template;
	var $design;
	var $buildDecorator;
	var $indexDecorator;
	var $synchronize;
	var $language;
	
	function PartContext() {
		$this->displayLinks = array();
		//$this->buildLinks = array();
		$this->template = 'document';
		$this->design = 'in2isoft';
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
		$this->indexDecorator->setEmailTags('','');
		$this->indexDecorator->setHttpTags('','');
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
	
	function setLanguage($language) {
	    $this->language = $language;
	}
	
	function getLanguage() {
	    return $this->language;
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
			$atts.=' url="'.Strings::escapeXML($value).'"';
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
		if (Strings::isNotBlank($title)) {
			$atts.=' title="'.Strings::escapeXML($title).'"';
		}
		if ($linkId>0) {
			$atts.=' id="'.$linkId.'"';
		}
		if ($partId>0) {
			$atts.=' part-id="'.$partId.'"';
		}
		if ($partId!=null) {
			//Log::debug('Build link added: '.$partId);
		}
		$this->buildDecorator->addReplacement($text,'<link'.$atts.'>','</link>',$partId);
	}
}
?>