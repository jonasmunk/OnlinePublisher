<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

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