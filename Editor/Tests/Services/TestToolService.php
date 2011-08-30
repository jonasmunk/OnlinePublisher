<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

class TestToolService extends UnitTestCase {
    
    function testIt() {
		$tools = ToolService::getAvailable();
		$this->assertTrue(count($tools)>0);
		$this->assertTrue(is_array($tools));
        $this->assertTrue(in_array('System',$tools),"The system tool is not present");
    }

	function testValid() {
		$tools = ToolService::getAvailable();
		foreach ($tools as $key) {
			$info = ToolService::getInfo($key);
			$this->assertTrue($info!=null,"The tool $key has no info");
		}
	}
}
?>