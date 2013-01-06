<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Network
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestOnlineObjectsService extends UnitTestCase {
    
    function testAnalyseText() {
		$text = "Et mitokondrie er inden for cellebiologi betegnelsen for et organel, som findes i de fleste eukaryote celler.";
		$response = OnlineObjectsService::analyseText($text);
		$this->assertNotNull($response);

		$uniqueWords = $response->uniqueWords;
		
		$this->assertTrue(in_array('inden',$uniqueWords));
		$this->assertTrue(in_array('eukaryote',$uniqueWords));
	}
}