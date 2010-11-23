<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

class TestRemoteDataService extends UnitTestCase {
    
    function testIt() {
		$data = RemoteDataService::getRemoteData('http://www.apple.com/');
		$this->assertTrue($data!==null);
		$this->assertTrue(0<=$data->getAge(),'Age must be larger or equal to 0, it is: '.$data->getAge());
		$this->assertTrue(file_exists($data->getFile()));
    }
}
?>