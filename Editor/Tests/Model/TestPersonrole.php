<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestPersonrole extends AbstractObjectTest {
    
	function TestPersonrole() {
		parent::AbstractObjectTest('personrole');
	}

	function testProperties() {
		$obj = new Personrole();
		$obj->setPersonId(10);
		$obj->save();
		
		$loaded = Personrole::load($obj->getId());
		$this->assertEqual($loaded->getPersonId(),10);
		
		$loaded->remove();
	}
}
?>