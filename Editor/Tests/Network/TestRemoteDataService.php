<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Network
 */

class TestRemoteDataService extends UnitTestCase {
    
    function testIt() {
		$data = RemoteDataService::getRemoteData('http://www.apple.com/');
		$this->assertTrue($data!==null);
		$this->assertTrue(0<=$data->getAge(),'Age must be larger or equal to 0, it is: '.$data->getAge());
		$this->assertTrue(file_exists($data->getFile()));
    }
	
	function testHttps() {
		$data = RemoteDataService::getRemoteData('https://github.com/in2isoft/OnlinePublisher/commits/master.atom');
		$this->assertTrue($data!==null);
		$this->assertTrue(file_exists($data->getFile()));
	}
	
	function testNotFound() {
		$data = RemoteDataService::getRemoteData('https://nowhe.re/imnotthere.html');
		$this->assertTrue($data!==null);
		$this->assertFalse($data->isSuccess());
		$this->assertFalse($data->isHasData());
		$this->assertFalse(file_exists($data->getFile()));
	}
	
	function testGithub() {
		$data = RemoteDataService::getRemoteData('https://github.com/in2isoft/OnlinePublisher/commits/master.atom');
		$this->assertTrue($data!==null);
		$this->assertTrue(file_exists($data->getFile()));
	}
}
?>