<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['pageblueprint'] = array(
	'designId' => array('type' => 'int','column' => 'design_id'),
	'frameId' => array('type' => 'int','column' => 'frame_id'),
	'templateId' => array('type' => 'int','column' => 'template_id'),
);
class PageBlueprint extends Object {
	var $designId;
	var $frameId;
	var $templateId;

	function PageBlueprint() {
		parent::Object('pageblueprint');
	}
	
	function load($id) {
		return Object::get($id,'pageblueprint');
	}
	
	function getIcon() {
		return "Element/Template";
	}
	
	function setDesignId($designId) {
	    $this->designId = $designId;
	}

	function getDesignId() {
	    return $this->designId;
	}
	
	function setFrameId($frameId) {
	    $this->frameId = $frameId;
	}

	function getFrameId() {
	    return $this->frameId;
	}
	
	function setTemplateId($templateId) {
	    $this->templateId = $templateId;
	}

	function getTemplateId() {
	    return $this->templateId;
	}
}
?>