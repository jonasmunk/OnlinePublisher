<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Integration
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestLogin extends WebTestCase {
    
    function testLogin() {
        global $baseUrl, $basePath;
        //$this->get(ConfigurationService::getCompleteBaseUrl().'Editor/Services/Core/Authentication.php');
        //$this->assertResponse(200);
    }
}
?>