<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Integration
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestLogin extends UnitTestCase {
    
    function testNotAllowed() {
        global $baseUrl, $basePath;
        $url = ConfigurationService::getCompleteBaseUrl().'Editor/Services/Core/Authentication.php';
        $response = HttpClient::send(new WebRequest($url));
        $this->assertEqual(200,$response->getStatusCode());
        $obj = Strings::fromJSON($response->getBody());
        $this->assertFalse($obj->success);
        $this->assertNotNull($response->getHeader('Set-Cookie'));

        $url = ConfigurationService::getCompleteBaseUrl().'Editor/index.php';
        $request = new WebRequest($url);
        
        $response = HttpClient::send($request);
        $this->assertNotEqual(200,$response->getStatusCode());        
    }
    
    function testSuccess() {
        global $baseUrl, $basePath;
        $username = Strings::generate(30);
        $password = Strings::generate(30);
        $user = new User();
        $user->setTitle('Test user');
        $user->setUsername($username);
        $user->setPassword(AuthenticationService::encryptPassword($password));
        $user->setInternal(true);
        $user->setSecure(true);
        $user->save();
        
        $url = ConfigurationService::getCompleteBaseUrl().'Editor/Services/Core/Authentication.php';
        $request = new WebRequest($url);
        $request->addParameter('username',$username);
        $request->addParameter('password',$password);
        
        $response = HttpClient::send($request);
        
        $this->assertEqual(200,$response->getStatusCode());
        $obj = Strings::fromJSON($response->getBody());
        $this->assertTrue($obj->success);
        
        $this->assertNotNull($response->getHeader('Set-Cookie'));
        $cookie = $response->getHeader('Set-Cookie');
        $cookie = substr($cookie,0,strpos($cookie,';'));

        $url = ConfigurationService::getCompleteBaseUrl().'Editor/index.php';
        $request = new WebRequest($url);
        $request->addHeader('Cookie',$cookie);
        
        $response = HttpClient::send($request);
        $this->assertEqual(200,$response->getStatusCode());

        $user->remove();
    }

}
?>