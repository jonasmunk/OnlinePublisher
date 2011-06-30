<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['testphrase'] = array();
class Testphrase extends Object {

	function Testphrase() {
		parent::Object('testphrase');
	}
	
	function load($id) {
		return Object::get($id,'testphrase');
	}
		
	/////////////////////////// GUI /////////////////////////
	
	function getIn2iGuiIcon() {
	    return 'monochrome/globe';
	}
}
?>