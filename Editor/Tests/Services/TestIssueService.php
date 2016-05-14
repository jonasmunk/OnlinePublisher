<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestIssueService extends UnitTestCase {
    
    function testTranslateKind() {
		$this->assertEqual(IssueService::translateKind('error','da'),"Fejl");
		$this->assertEqual(IssueService::translateKind('error','en'),"Error");

		$this->assertEqual(IssueService::translateKind('hoppaloopa','en'),"hoppaloopa");
    }
}
?>