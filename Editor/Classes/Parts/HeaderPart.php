<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/Part.php');

Part::$schema['header'] = array(
	'fields' => array(
		'text'   => array('type'=>'text'),
		'level'   => array('type'=>'int')
	)
);
class HeaderPart extends Part
{
	var $text;
	var $level;
	
	function HeaderPart() {
		parent::Part('header');
	}
	
	function load($id) {
		return Part::load('header',$id);
	}
	
	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
	}
	
	function setLevel($level) {
	    $this->level = $level;
	}

	function getLevel() {
	    return $this->level;
	}
	
	function toUnicode() {
		$this->text = mb_convert_encoding($this->text, "UTF-8","ISO-8859-1");
	}
}
?>