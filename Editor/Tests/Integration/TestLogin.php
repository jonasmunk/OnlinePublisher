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
    }
}
?>