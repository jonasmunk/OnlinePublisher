<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestDateUtils extends UnitTestCase {

	function testParse() {
		$date = '15-04-1980';
		$stamp = DateUtils::parse($date);
		$this->assertEqual(DateUtils::formatLongDate($stamp,'en_US'),'15. Apr 1980');

		$date = '15/04-1980';
		$stamp = DateUtils::parse($date);
		$this->assertEqual(DateUtils::formatLongDate($stamp,'en_US'),'15. Apr 1980');

		$date = '15.04/1980';
		$stamp = DateUtils::parse($date);
		$this->assertEqual(DateUtils::formatLongDate($stamp,'en_US'),'15. Apr 1980');

		$date = '1980-04-15';
		$stamp = DateUtils::parse($date);
		$this->assertEqual(DateUtils::formatLongDate($stamp,'en_US'),'15. Apr 1980');

		$date = '1980-4-15';
		$stamp = DateUtils::parse($date);
		$this->assertEqual(DateUtils::formatLongDate($stamp,'en_US'),'15. Apr 1980');

		$date = '15.04/10';
		$stamp = DateUtils::parse($date);
		$this->assertEqual(DateUtils::formatLongDate($stamp,'en_US'),'15. Apr 2010');

		$date = '150410';
		$stamp = DateUtils::parse($date);
		$this->assertEqual(DateUtils::formatLongDate($stamp,'en_US'),'15. Apr 2010');
	}

	function testParseRFC3339() {
		$date = '2010-12-06T08:12:42-08:00';
		$stamp = DateUtils::parseRFC3339($date);
		$this->assertEqual(DateUtils::formatLongDateTimeGM($stamp,'en_US'),' 6. Dec 2010 kl. 16:12');
	}
	
	function testParseRFC3339_greenwich() {
		$date = '2010-12-06T08:12:42Z';
		$stamp = DateUtils::parseRFC3339($date);
		$this->assertEqual(DateUtils::formatLongDateTimeGM($stamp,'en_US'),' 6. Dec 2010 kl. 08:12');
	}
}