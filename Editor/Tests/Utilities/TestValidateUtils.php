<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

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
	
	function testPositiveInt() {
		$this->assertTrue(ValidateUtils::validateDigits('1'));
		$this->assertTrue(ValidateUtils::validateDigits(1));
		$this->assertTrue(ValidateUtils::validateDigits('71823471894719'));
		$this->assertTrue(ValidateUtils::validateDigits('0'));
		$this->assertTrue(ValidateUtils::validateDigits(0));

		$this->assertFalse(ValidateUtils::validateDigits(' 1'));
		$this->assertFalse(ValidateUtils::validateDigits(''));
		$this->assertFalse(ValidateUtils::validateDigits(' '));
		$this->assertFalse(ValidateUtils::validateDigits(null));
		$this->assertFalse(ValidateUtils::validateDigits());
		$this->assertFalse(ValidateUtils::validateDigits('-1'));
		$this->assertFalse(ValidateUtils::validateDigits('a'));
		$this->assertFalse(ValidateUtils::validateDigits('1.2495'));
	}
}