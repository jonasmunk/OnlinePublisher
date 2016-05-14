<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

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