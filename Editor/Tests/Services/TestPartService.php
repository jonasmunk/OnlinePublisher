<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestPartService extends UnitTestCase {
    
    function testLinks() {
		$part = new PosterPart();
		$part->save();
		
		$link = new PartLink();
		$link->setPartId($part->getId());
		$link->setSourceType('A');
		$link->setTargetType('B');
		$link->setTargetValue('C');
		$link->save();
		
		
		
		$this->assertTrue($link->getId()>0);
		$this->assertEqual($link->getSourceType());
		
		PartService::removeLinks($part->getId());
		$part->remove();
    }
}
?>