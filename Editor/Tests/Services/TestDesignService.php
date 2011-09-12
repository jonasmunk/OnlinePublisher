<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestDesignService extends UnitTestCase {
    
    function testIt() {
		$designs = DesignService::getAvailableDesigns();
		$this->assertTrue(count($designs)>0);
		$this->assertTrue(is_array($designs));
        $this->assertTrue(array_key_exists('basic',$designs),"The basic design is not present");
    }

	function testValid() {
		$designs = DesignService::getAvailableDesigns();
		foreach ($designs as $design => $info) {
			$valid = DesignService::validate($design);
			$this->assertTrue($valid,"The design $design is not valid");
		}
	}
}
?>