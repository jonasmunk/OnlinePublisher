<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestAddress extends AbstractObjectTest {
    
	function TestAddress() {
		parent::AbstractObjectTest('address');
	}
	
	function testToString() {
		$address = new Address();
		$address->setStreet('H/f Syndbyvester 49');
		$address->setZipcode(2300);
		$address->setCity('Copenhagen S');
		$this->assertEqual($address->toString(),'H/f Syndbyvester 49, 2300, Copenhagen S');
	}

	function testToString2() {
		$address = new Address();
		$address->setStreet('H/f Syndbyvester 49');
		$address->setCity('Copenhagen S');
		$this->assertEqual($address->toString(),'H/f Syndbyvester 49, Copenhagen S');
	}

	function testToString3() {
		$address = new Address();
		$address->setStreet('H/f Syndbyvester 49');
		$this->assertEqual($address->toString(),'H/f Syndbyvester 49');
	}
}
?>