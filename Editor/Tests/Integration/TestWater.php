<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Integration
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestWater extends UnitTestCase {
    
    function testIt() {
		$meter = new Watermeter();
		$meter->setNumber('test'.time());
		$meter->save();
		
		$usage = new Waterusage();
		$usage->setWatermeterId($meter->getId());
		$usage->setDate(time());
		$usage->save();

		$usage2 = new Waterusage();
		$usage2->setWatermeterId($meter->getId());
		$usage2->setDate(DateUtils::addYears(time(),-1));
		$usage2->save();
		
		$summary = WaterusageService::getSummary($meter->getNumber());
		$this->assertEqual($summary->getNumber(),$meter->getNumber());
		
		$usage->remove();
		$usage2->remove();
		$meter->remove();
    }
}
?>