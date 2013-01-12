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
		if ($response!=null) {
			$uniqueWords = $response->uniqueWords;
			
			$this->assertEqual('da',$response->language);
			$this->assertTrue(in_array('inden',$uniqueWords));
			$this->assertTrue(in_array('eukaryote',$uniqueWords));
			Log::debug($response);
		}
	}

    function testAnalyseEnglish() {
		$text = "Jones, sitting on the set of 'Piers Morgan Tonight' on Monday, nodded in agreement, as Morgan framed the gun advocate's desire to have the Englishman thrown out of the United States.";
		$response = OnlineObjectsService::analyseText($text);
		if ($response!=null) {
			$this->assertEqual('en',$response->language);
		}
	}
}