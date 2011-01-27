<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

// THE ENCODING OF THIS FILE SHOULD BE UTF-8

class TestValidateUtils extends UnitTestCase {
	
	function testEmail() {
		$this->assertTrue(ValidateUtils::validateEmail('jbm@domain.dk'));
		$this->assertTrue(ValidateUtils::validateEmail('my.name@domain.dk'));
		$this->assertTrue(ValidateUtils::validateEmail('my.name@d.b.s.dk'));
		
		$this->assertFalse(ValidateUtils::validateEmail('jbm@domain.d'));
		$this->assertFalse(ValidateUtils::validateEmail('jbm@@domain.d'));
		$this->assertFalse(ValidateUtils::validateEmail(''));
		$this->assertFalse(ValidateUtils::validateEmail('jbm'));
		$this->assertFalse(ValidateUtils::validateEmail('@'));
		$this->assertFalse(ValidateUtils::validateEmail('@atira.dk'));
	}
}