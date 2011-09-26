<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Model/Object.php');

Object::$schema['path'] = array(
	'path'   => array('type'=>'text'),
	'pageId'  => array('type'=>'int','column'=>'page_id')
);
class Path extends Object {
	var $path;
	var $pageId=0;

	function Path() {
		parent::Object('path');
	}
	
	function load($id) {
		return Object::get($id,'path');
	}

	function setPath($path) {
		$this->path = $path;
		$this->_updateTitle();
	}

	function getPath() {
		return $this->path;
	}

	function setPageId($id) {
		$this->pageId = $id;
		$this->_updateTitle();
	}

	function getPageId() {
		return $this->pageId;
	}

	function _updateTitle() {
		$this->setTitle($this->path.' -> '.$this->pageId);
	}
		
	/////////////////////////// GUI /////////////////////////
	
	function getIn2iGuiIcon() {
	    return 'monochrome/globe';
	}
}
?>