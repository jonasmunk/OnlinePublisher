<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Network
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestPartLink extends UnitTestCase {
	
	function testLinks() {
        $link = new PartLink();
        $link->save();
        
        $this->assertTrue($link->getId() > 0);
        
        $loaded = PartLink::load($link->getId());
        $this->assertTrue($loaded->getId() > 0);        
	}
	
}
?>