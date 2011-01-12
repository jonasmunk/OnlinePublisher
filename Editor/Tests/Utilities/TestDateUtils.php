<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Utilities
 */

class TestDateUtils extends UnitTestCase {

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