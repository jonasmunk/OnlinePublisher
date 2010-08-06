<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

class TestJsonService extends UnitTestCase {
    
    function testDecode() {
		$json = '{"a":"b"}';
		$obj = JsonService::decode($json);
        $this->assertEqual("b",$obj->a);

		$json = "{\n\t\"name\" : \"the name\"\n}";
		$obj = JsonService::decode($json);
        $this->assertEqual("the name",$obj->name);
    }
}
?>