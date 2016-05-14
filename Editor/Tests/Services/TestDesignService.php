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
    
    function testGettingDesigns() {
		$designs = DesignService::getAvailableDesigns();
		$this->assertTrue(count($designs) > 0);
		$this->assertTrue(is_array($designs));
        $this->assertTrue(array_key_exists('basic',$designs), "The basic design is not present");
    }

	function testValidateDesigns() {
		$designs = DesignService::getAvailableDesigns();
		foreach ($designs as $design => $info) {
			$valid = DesignService::validate($design);
			$this->assertTrue($valid,"The design $design is not valid");
		}
	}

    function testParameters() {
		$custom = new Design();
		$custom->setUnique('custom');
		$custom->save();
		
		$parameters = DesignService::loadParameters($custom->getId());
		
		Log::debug($parameters);

		$this->assertTrue(count($parameters)>0);
		
		DesignService::saveParameters($custom->getId(),array('title'=>'Ullamcorper Magna Parturient'));
		
		$parameters = DesignService::loadParameters($custom->getId());
		$this->assertEqual($parameters[0]['value'],'Ullamcorper Magna Parturient');

		$custom->remove();
    }
}
?>