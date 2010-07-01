<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/Part.php');

Part::$schema['text'] = array(
	'fields' => array(
		'text'   => array('type'=>'text')
	)
);
class Text extends Part
{
	var $text;
	
	function Text() {
		parent::Part('text');
	}
	
	function load($id) {
		return Part::load('text',$id);
	}
	
	function setText($text) {
	    $this->text = $text;
	}

	function getText() {
	    return $this->text;
	}
	
	function toUnicode() {
		$this->text = mb_convert_encoding($this->text, "UTF-8","ISO-8859-1");
	}
}
?>