<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

// THE ENCODING OF THIS FILE SHOULD BE UTF-8

class TestValidateUtils extends UnitTestCase {
	
	function testEmail() {
		$this->assertTrue(ValidateUtil::validateEmail('jbm@domain.dk'));
		$this->assertTrue(ValidateUtil::validateEmail('my.name@domain.dk'));
		$this->assertTrue(ValidateUtil::validateEmail('my.name@d.b.s.dk'));
		
		$this->assertFalse(ValidateUtil::validateEmail('jbm@domain.d'));
		$this->assertFalse(ValidateUtil::validateEmail('jbm@@domain.d'));
		$this->assertFalse(ValidateUtil::validateEmail(''));
		$this->assertFalse(ValidateUtil::validateEmail('jbm'));
		$this->assertFalse(ValidateUtil::validateEmail('@'));
		$this->assertFalse(ValidateUtil::validateEmail('@atira.dk'));
	}
}