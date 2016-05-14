<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestPhonenumber extends AbstractObjectTest {
    
	function TestPhonenumber() {
		parent::AbstractObjectTest('phonenumber');
	}

	function testProperties() {
		$obj = new Phonenumber();
		$obj->setTitle('My group');
		$obj->setNumber('88997879898');
		$obj->setContext('work');
		$obj->setContainingObjectId(100);
		$obj->save();
		
		$loaded = Phonenumber::load($obj->getId());
		$this->assertEqual($obj->getTitle(),$loaded->getTitle());
		$this->assertEqual($obj->getNumber(),$loaded->getNumber());
		$this->assertEqual($obj->getContext(),$loaded->getContext());
		$this->assertEqual($obj->getContainingObjectId(),$loaded->getContainingObjectId());
		
		$loaded->remove();
	}
}
?>