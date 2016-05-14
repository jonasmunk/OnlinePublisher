<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestAuthenticationService extends UnitTestCase {
    
    function testIt() {
		$this->assertTrue(AuthenticationService::isValidUsername('jonasmunk'));
		$this->assertTrue(AuthenticationService::isValidUsername('Jonas_munk0'));
		$this->assertTrue(AuthenticationService::isValidUsername('ab'));

		$this->assertFalse(AuthenticationService::isValidUsername(' '));
		$this->assertFalse(AuthenticationService::isValidUsername('a'));
    }
}
?>