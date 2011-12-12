<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Templates
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestAuthenticationTemplate extends UnitTestCase {

	function testIt() {
		$this->assertNull(AuthenticationTemplate::load(0));
	}
}