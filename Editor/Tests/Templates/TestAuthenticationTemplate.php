<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

class TestAuthenticationTemplate extends UnitTestCase {

	function testIt() {
		$this->assertNull(AuthenticationTemplate::load(0));
	}
}