<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestWaterusage extends AbstractObjectTest {
    
	function TestWaterusage() {
		parent::AbstractObjectTest('waterusage');
	}
	
	function testProperties() {
		$obj = new Waterusage();
		$obj->setWatermeterId(20);
		$obj->setValue(123456);
		$obj->setDate(time());
		$obj->save();
		
		$loaded = Waterusage::load($obj->getId());
		$this->assertEqual($obj->getWatermeterId(),$loaded->getWatermeterId());
		$this->assertEqual($obj->getValue(),$loaded->getValue());
		$this->assertEqual($obj->getDate(),$loaded->getDate());
		
		$loaded->remove();
	}

}
?>