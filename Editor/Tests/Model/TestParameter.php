<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestParameter extends UnitTestCase {

	function testProperties() {
		$parameter = new Parameter();
		$parameter->setName('My parameter');
		$parameter->save();
		$this->assertNotNull($parameter->getId());
				
		$loaded = Parameter::load($parameter->getId());
		$this->assertNotNull($loaded);
		$this->assertEqual($loaded->getId(),$parameter->getId());
		$this->assertEqual($loaded->getName(),'My parameter');

		$parameter->setName('My new parameter name');
		$parameter->save();

		$loaded = Parameter::load($parameter->getId());
		$this->assertNotNull($loaded);
		$this->assertEqual($loaded->getId(),$parameter->getId());
		$this->assertEqual($loaded->getName(),'My new parameter name');
		
		$loaded->remove();
		$this->assertNull(Parameter::load($parameter->getId()));
	}
}
?>