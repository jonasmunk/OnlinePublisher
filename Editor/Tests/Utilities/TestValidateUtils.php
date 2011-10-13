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

	function testHref() {
		$this->assertTrue(ValidateUtils::validateHref('http://www.humanise.dk/'));
		$this->assertTrue(ValidateUtils::validateHref('https://www.humanise.dk/'));
		$this->assertTrue(ValidateUtils::validateHref('http://www.humanise.dk'));
		$this->assertTrue(ValidateUtils::validateHref('http://itunes.apple.com/us/podcast/nsbrief/id399822861'));

		$this->assertTrue(ValidateUtils::validateHref('#hey'));
		$this->assertTrue(ValidateUtils::validateHref('#'));
	}
	
	function testUrl() {
		$this->assertTrue(ValidateUtils::validateUrl('http://www.humanise.dk/'));
		$this->assertTrue(ValidateUtils::validateUrl('https://www.humanise.dk/'));
		$this->assertTrue(ValidateUtils::validateUrl('http://www.humanise.dk'));
		$this->assertTrue(ValidateUtils::validateUrl('http://itunes.apple.com/us/podcast/nsbrief/id399822861'));
		$this->assertTrue(ValidateUtils::validateUrl('http://code.google.com/p/onlineobjects/source/browse/#svn%2Ftrunk%2Fsrc%2Fweb%2Fhui'));
		
			$this->assertTrue(ValidateUtils::validateUrl('http://www.google.com/search?client=safari&rls=en&q=iphone+developer+podcast&ie=UTF-8&oe=UTF-8#sclient=psy-ab&hl=en&client=safari&rls=en&source=hp&q=humanize&pbx=1&oq=humanize&aq=f&aqi=g4&aql=&gs_sm=e&gs_upl=12171745l12173115l0l12173609l8l7l0l0l0l0l180l932l1.6l7l0&bav=on.2,or.r_gc.r_pw.r_cp.,cf.osb&fp=de4ce0a5d4a08049&biw=1481&bih=738'));
	}
	
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