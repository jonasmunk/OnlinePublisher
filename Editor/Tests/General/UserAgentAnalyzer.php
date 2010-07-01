<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

require_once('../../Config/Setup.php');
require_once('../Include/Security.php');

require_once('../Libraries/simpletest/unit_tester.php');
require_once('../Libraries/simpletest/reporter.php');

require_once('../Classes/UserAgentAnalyzer.php');

class TestUserAgentAnalyzer extends UnitTestCase {
    
    function testBasic() {
		$analyzer = new UserAgentAnalyzer();
        $this->assertTrue($analyzer->getApplicationVersion()=='');
        $this->assertTrue($analyzer->getApplicationName()=='');
        $this->assertTrue($analyzer->getTechnologyName()=='');
        $this->assertTrue($analyzer->getTechnologyVersion()=='');
        $this->assertNull($analyzer->isRobot());
        $this->assertNull($analyzer->isSearchEngine());
    }

/*
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/125.5 (KHTML, like Gecko) Safari/125.9
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/125.5.5 (KHTML, like Gecko) Safari/125.11
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/412 (KHTML, like Gecko) Safari/412
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/412.6 (KHTML, like Gecko) Safari/412.2
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/412.6.2 (KHTML, like Gecko) Safari/412.2.2
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/412.7 (KHTML, like Gecko) Safari/412.5
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/416.11 (KHTML, like Gecko) Safari/416.12
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/416.12 (KHTML, like Gecko) Safari/416.13
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/417.9 (KHTML, like Gecko) Safari/417.8
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/420+ (KHTML, like Gecko)
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/420+ (KHTML, like Gecko) Safari/412.5
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/420+ (KHTML, like Gecko) Safari/416.12
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/420+ (KHTML, like Gecko) Safari/416.13
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/420+ (KHTML, like Gecko) Safari/417.8
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/125.5.5 (KHTML, like Gecko) Safari/125.12
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/125.5.6 (KHTML, like Gecko) Safari/125.12
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/125.5.7 (KHTML, like Gecko) Safari/125.12
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/312.1 (KHTML, like Gecko) Safari/312
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/312.8 (KHTML, like Gecko) Safari/312.5
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/412 (KHTML, like Gecko) Safari/412
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/412.7 (KHTML, like Gecko) Safari/412.5
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/417.9 (KHTML, like Gecko) Safari/417.8
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Safari/417.8
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US) AppleWebKit/125.4 (KHTML, like Gecko, Safari) OmniWeb/v563.34
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-us) AppleWebKit/312.8 (KHTML, like Gecko) Safari/312.5
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-us) AppleWebKit/420+ (KHTML, like Gecko) Safari/417.8
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US) AppleWebKit/85 (KHTML, like Gecko) OmniWeb/v496
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; fr) AppleWebKit/125.5.6 (KHTML, like Gecko) Safari/125.12

Very rare!
Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/412.7 (KHTML, like Gecko)
*/
    function testAppleWebKit() {
		$analyzer = new UserAgentAnalyzer();
		
		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/412.7 (KHTML, like Gecko)');
        $this->assertTrue($analyzer->getApplicationVersion()=='');
        $this->assertTrue($analyzer->getApplicationName()=='AppleWebKit');
        $this->assertTrue($analyzer->getTechnologyName()=='AppleWebKit');
        $this->assertTrue($analyzer->getTechnologyVersion()=='412.7');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());
		
		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/125.5 (KHTML, like Gecko) Safari/125.9');
        $this->assertTrue($analyzer->getApplicationVersion()=='125.9');
        $this->assertTrue($analyzer->getApplicationName()=='Safari');
        $this->assertTrue($analyzer->getTechnologyName()=='AppleWebKit');
        $this->assertTrue($analyzer->getTechnologyVersion()=='125.5');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/125.5.5 (KHTML, like Gecko) Safari/125.11');
        $this->assertTrue($analyzer->getApplicationVersion()=='125.11');
        $this->assertTrue($analyzer->getApplicationName()=='Safari');
        $this->assertTrue($analyzer->getTechnologyName()=='AppleWebKit');
        $this->assertTrue($analyzer->getTechnologyVersion()=='125.5.5');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/420+ (KHTML, like Gecko) Safari/417.8');
        $this->assertTrue($analyzer->getApplicationVersion()=='417.8');
        $this->assertTrue($analyzer->getApplicationName()=='Safari');
        $this->assertTrue($analyzer->getTechnologyName()=='AppleWebKit');
        $this->assertTrue($analyzer->getTechnologyVersion()=='420+');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Safari/419.3');
        $this->assertTrue($analyzer->getApplicationVersion()=='419.3');
        $this->assertTrue($analyzer->getApplicationName()=='Safari');
        $this->assertTrue($analyzer->getTechnologyName()=='AppleWebKit');
        $this->assertTrue($analyzer->getTechnologyVersion()=='420+');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/417.9 (KHTML, like Gecko) Safari/417.8');
        $this->assertTrue($analyzer->getApplicationVersion()=='417.8');
        $this->assertTrue($analyzer->getApplicationName()=='Safari');
        $this->assertTrue($analyzer->getTechnologyName()=='AppleWebKit');
        $this->assertTrue($analyzer->getTechnologyVersion()=='417.9');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; Intel Mac OS X; da-dk) AppleWebKit/418.9.1 (KHTML, like Gecko) Safari/419.3');
        $this->assertTrue($analyzer->getApplicationVersion()=='419.3');
        $this->assertTrue($analyzer->getApplicationName()=='Safari');
        $this->assertTrue($analyzer->getTechnologyName()=='AppleWebKit');
        $this->assertTrue($analyzer->getTechnologyVersion()=='418.9.1');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		// Other apps than safari

		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US) AppleWebKit/125.4 (KHTML, like Gecko, Safari) OmniWeb/v563.34');
        $this->assertTrue($analyzer->getApplicationVersion()=='563.34');
        $this->assertTrue($analyzer->getApplicationName()=='OmniWeb');
        $this->assertTrue($analyzer->getTechnologyName()=='AppleWebKit');
        $this->assertTrue($analyzer->getTechnologyVersion()=='125.4');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; da-dk) AppleWebKit/418.8 (KHTML, like Gecko) Paparazzi!/0.4.3');
        $this->assertTrue($analyzer->getApplicationVersion()=='0.4.3');
        $this->assertTrue($analyzer->getApplicationName()=='Paparazzi!');
        $this->assertTrue($analyzer->getTechnologyName()=='AppleWebKit');
        $this->assertTrue($analyzer->getTechnologyVersion()=='418.8');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());
		//
    }

/*
Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)
Mozilla/4.0 (compatible; MSIE 5.01; Windows 98; SYMPA)
Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)
Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0; DigExt)
Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0; DigExt; NOOS)
Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0; MathPlayer 2.0; SV1; .NET CLR 1.1.4322)
Mozilla/4.0 (compatible; MSIE 5.0; Windows 98; DigExt; Hotbar 4.3.1.0)
Mozilla/4.0 (compatible; MSIE 5.5; Windows 98; FREETELECOM)
Mozilla/4.0 (compatible; MSIE 5.5; Windows 98; KITV4 Wanadoo)
Mozilla/4.0 (compatible; MSIE 5.5; Windows 98; KITV5 Wanadoo; Wanadoo 6.0; (R1 1.3))
Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; IE55SP2-2002.06.03-NC; IE55SP2-2002.06.03)
Mozilla/4.0 (compatible; MSIE 6.0; Windows 98)
Mozilla/4.0 (compatible; MSIE 6.0; Windows 98; .NET CLR 1.0.3705)
Mozilla/4.0 (compatible; MSIE 6.0; Windows 98; Q312461; FREE)
Mozilla/4.0 (compatible; MSIE 6.0; Windows 98; Win 9x 4.90; KITV4.6 Wanadoo; .NET CLR 1.1.4322)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.0.3705)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Avant Browser [avantbrowser.com]; .NET CLR 1.0.3705)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Avant Browser [avantbrowser.com]; .NET CLR 1.1.4322)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; DigExt; Hotbar 4.2.8.0; i-Nav 3.0.1.0F)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; DigExt; KITV5 Wanadoo)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Hotbar 4.2.13.0; MSN 6.1; MSNbMSFT; MSNmfr-ca; MSNc00; v5m)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; MathPlayer 2.0; SV1; .NET CLR 1.1.4322)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727; .NET CLR 1.1.4322)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Wanadoo 5.5; Q312461)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322; InfoPath.1)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; FunWebProducts; SIMBAR Enabled; SIMBAR=0; SIMBAR={3D7C862C-1B8B-4e9b-BE7E-490350C8A7AF}; .NET CLR 1.1.4322)

Mozilla/4.0 (compatible; MSIE 6.0; Windows XP)
	
Mozilla/4.0 (compatible; MSIE 5.0b1; Mac_PowerPC)
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; acc=kimochiz; acc=none; (none); (); .NET CLR 1.1.4322)
Mozilla/2.0 (compatible; MSIE 3.0B; Windows NT)
*/
	function testInternetExplorer() {
		$analyzer = new UserAgentAnalyzer();
		
		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)');
        $this->assertTrue($analyzer->getApplicationVersion()=='5.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='5.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 5.01; Windows 98; SYMPA)');
        $this->assertTrue($analyzer->getApplicationVersion()=='5.01');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='5.01');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0; MathPlayer 2.0; SV1; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='5.01');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='5.01');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; IE55SP2-2002.06.03-NC; IE55SP2-2002.06.03)');
        $this->assertTrue($analyzer->getApplicationVersion()=='5.5');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='5.5');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; IE55SP2-2002.06.03-NC; IE55SP2-2002.06.03)');
        $this->assertTrue($analyzer->getApplicationVersion()=='5.5');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='5.5');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Hotbar 4.2.13.0; MSN 6.1; MSNbMSFT; MSNmfr-ca; MSNc00; v5m)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322; InfoPath.1)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 5.0b1; Mac_PowerPC)');
        $this->assertTrue($analyzer->getApplicationVersion()=='5.0b1');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='5.0b1');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; acc=kimochiz; acc=none; (none); (); .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows XP)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/2.0 (compatible; MSIE 3.0B; Windows NT)');
        $this->assertTrue($analyzer->getApplicationVersion()=='3.0B');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='3.0B');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; FunWebProducts; SIMBAR Enabled; SIMBAR=0; SIMBAR={3D7C862C-1B8B-4e9b-BE7E-490350C8A7AF}; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; EmbeddedWB 14,52 from: http://www.bsalsa.com/ EmbeddedWB 14,52; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='7.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='7.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; EmbeddedWB 14,52 from: http://www.bsalsa.com/ EmbeddedWB 14,52; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; SIMBAR Enabled; SIMBAR={89B84771-DEAF-4b89-AB55-86C6DBE4E38D}; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; ESB{A79DE66A-1A52-43FD-8E3E-A87A14867BBF}; SV1; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; SIMBAR={BC764ED8-0CCA-483a-9546-E275EA8B348A}; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; Sgrunt|V109|155|S-728657281|dial; snprtz|S26140800000000|2600#Service Pack 2#2#5#1)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; FunWebProducts; SIMBAR Enabled; SIMBAR=0; SIMBAR={3D7C862C-1B8B-4e9b-BE7E-490350C8A7AF}; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; SIMBAR Enabled; SIMBAR={5851FEF0-8C5A-4796-9A88-9CE73BD176C1}; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; {CCB25D1F-EFD8-6D88-7FAB-EAC458474602})');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; %username%; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; ESB{E1B0C493-3C63-4320-BBC0-8EB2881B67BF}; SV1; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; SIMBAR Enabled; SIMBAR={1401E86A-705D-4e24-BBD7-452FE6C53250})');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; SIMBAR Enabled; SIMBAR={7807FBC3-78FA-452e-9831-D95471D64800}; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; SIMBAR Enabled; SIMBAR=0; SIMBAR={E1A9EF6D-AEBC-4141-834F-D081FD070336}; i-NavFourF; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='6.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; SIMBAR Enabled; SIMBAR={AAF80D7F-0746-439a-A61F-94E2400D3F68}; .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='7.0');
        $this->assertTrue($analyzer->getApplicationName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='7.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());
//

	}

/*
Mozilla/4.0 (compatible; MSIE 5.5; AOL 7.0; Windows 98; Win 9x 4.90; (R1 1.3))
Mozilla/4.0 (compatible; MSIE 5.5; AOL 7.0; Windows 98; Win 9x 4.90; KITV4.6 Wanadoo)
Mozilla/4.0 (compatible; MSIE 6.0; AOL 8.0; Windows NT 5.1; FIPID-{0KUZjWJqnnqw{05je1XAf1sUU356964111; (R1 1.3); .NET CLR 1.1.4322)
*/
	function testAOL() {
		$analyzer = new UserAgentAnalyzer();

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; AOL 8.0; Windows NT 5.1; FIPID-{0KUZjWJqnnqw{05je1XAf1sUU356964111; (R1 1.3); .NET CLR 1.1.4322)');
        $this->assertTrue($analyzer->getApplicationVersion()=='8.0');
        $this->assertTrue($analyzer->getApplicationName()=='AOL');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='6.0');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 5.5; AOL 7.0; Windows 98; Win 9x 4.90; KITV4.6 Wanadoo)');
        $this->assertTrue($analyzer->getApplicationVersion()=='7.0');
        $this->assertTrue($analyzer->getApplicationName()=='AOL');
        $this->assertTrue($analyzer->getTechnologyName()=='InternetExplorer');
        $this->assertTrue($analyzer->getTechnologyVersion()=='5.5');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());
	}
/*
Mozilla/4.0 (compatible; MSIE 6.0; Mac_PowerPC Mac OS X; da) Opera 8.5
Mozilla/4.0 (compatible; MSIE 6.0; Mac_PowerPC Mac OS X; da) Opera 8.51
Mozilla/4.0 (compatible; MSIE 5.0; Windows NT 4.0) Opera 6.04  [fr]
Opera/7.11 (Windows 98; U)  [fr]
Opera/8.02 (Windows NT 5.1; U; en)
*/
	function testOpera() {
		$analyzer = new UserAgentAnalyzer();

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 5.0; Windows NT 5.0) Opera 6.04  [fr]');
        $this->assertEqual($analyzer->getApplicationVersion(),'6.04');
        $this->assertEqual($analyzer->getApplicationName(),'Opera');
        $this->assertEqual($analyzer->getTechnologyName(),'Opera');
        $this->assertEqual($analyzer->getTechnologyVersion(),'6.04');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Mac_PowerPC Mac OS X; da) Opera 8.51');
        $this->assertEqual($analyzer->getApplicationVersion(),'8.51');
        $this->assertEqual($analyzer->getApplicationName(),'Opera');
        $this->assertEqual($analyzer->getTechnologyName(),'Opera');
        $this->assertEqual($analyzer->getTechnologyVersion(),'8.51');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Opera/7.11 (Windows 98; U)  [fr]');
        $this->assertEqual($analyzer->getApplicationVersion(),'7.11');
        $this->assertEqual($analyzer->getApplicationName(),'Opera');
        $this->assertEqual($analyzer->getTechnologyName(),'Opera');
        $this->assertEqual($analyzer->getTechnologyVersion(),'7.11');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Opera/8.02 (Windows NT 5.1; U; en)');
        $this->assertEqual($analyzer->getApplicationVersion(),'8.02');
        $this->assertEqual($analyzer->getApplicationName(),'Opera');
        $this->assertEqual($analyzer->getTechnologyName(),'Opera');
        $this->assertEqual($analyzer->getTechnologyVersion(),'8.02');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());
	}
	
/*
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; da-DK; rv:1.7.8) Gecko/20050511 Firefox/1.0.4
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; da; rv:1.8) Gecko/20051025 Firefox/1.5
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; da; rv:1.8) Gecko/20051107 Firefox/1.5
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; da; rv:1.8) Gecko/20051111 Firefox/1.5
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; da; rv:1.8.0.1) Gecko/20060111 Firefox/1.5.0.1
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.5) Gecko/20031026 Firebird/0.7
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.7.10) Gecko/20050716 Firefox/1.0.6
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.7.5) Gecko/20041103 Firefox/1.0RC2
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.7.5) Gecko/20041110 Firefox/1.0 (PowerBook)
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.7.6) Gecko/20050223 Firefox/1.0.1
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.7.6) Gecko/20050225 Firefox/1.0.1
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.7.6) Gecko/20050317 Firefox/1.0.2
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.7.7) Gecko/20050414 Firefox/1.0.3
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8) Gecko/20051107 Firefox/1.5
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8) Gecko/20051111 Firefox/1.5
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8) Gecko/20051129 Camino/1.0b1+
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8) Gecko/20051211 Camino/1.0b1+
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8) Gecko/20051229 Camino/1.0b2
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8b4) Gecko/20050908 Firefox/1.4
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8b4) Gecko/20050914 Camino/1.0a1
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8b5) Gecko/20051006 Firefox/1.4.1
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.9a1) Gecko/20051219 Firefox/1.6a1
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.9a1) Gecko/20051226 Firefox/1.6a1
Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.9a1) Gecko/20060107 Camino/1.0+

Mozilla/5.0 (Windows; U; Windows NT 5.0; da-DK; rv:1.7.8) Gecko/20050511 Firefox/1.0.4
Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.9a1) Gecko/20060107 SeaMonkey/1.5a
Mozilla/5.0 (Windows; U; Windows NT 5.1; da-DK; rv:1.7.10) Gecko/20050717 Firefox/1.0.6
Mozilla/5.0 (Windows; U; Windows NT 5.1; da-DK; rv:1.7.5) Gecko/20041118 Firefox/1.0
Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.10) Gecko/20050716 Firefox/1.0.6
Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0
Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041217 MultiZilla/1.6.4.0b
Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.6) Gecko/20050225 Firefox/1.0.1
Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.6) Gecko/20050317 Firefox/1.0.2
Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.8) Gecko/20050511 Firefox/1.0.4
Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8) Gecko/20051025 Firefox/1.5
Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8) Gecko/20051111 Firefox/1.5
Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.1) Gecko/20060111 Firefox/1.5.0.1
Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8b2) Gecko/20050328 Firefox/1.0+
Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8b4) Gecko/20050908 Firefox/1.4
Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.0.1) Gecko/20060111 Firefox/1.5.0.1

Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.12) Gecko/20051010 Firefox/1.0.4 (Ubuntu package 1.0.7)

Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8b) Gecko/20050217
*/
	function testGecko() {
		$analyzer = new UserAgentAnalyzer();

		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; da-DK; rv:1.7.8) Gecko/20050511 Firefox/1.0.4');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.0.4');
        $this->assertEqual($analyzer->getApplicationName(),'Firefox');
        $this->assertEqual($analyzer->getTechnologyName(),'Gecko');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.7.8');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.5) Gecko/20031026 Firebird/0.7');
        $this->assertEqual($analyzer->getApplicationVersion(),'0.7');
        $this->assertEqual($analyzer->getApplicationName(),'Firebird');
        $this->assertEqual($analyzer->getTechnologyName(),'Gecko');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.5');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.7.5) Gecko/20041103 Firefox/1.0RC2');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.0RC2');
        $this->assertEqual($analyzer->getApplicationName(),'Firefox');
        $this->assertEqual($analyzer->getTechnologyName(),'Gecko');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.7.5');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.7.5) Gecko/20041110 Firefox/1.0 (PowerBook)');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.0');
        $this->assertEqual($analyzer->getApplicationName(),'Firefox');
        $this->assertEqual($analyzer->getTechnologyName(),'Gecko');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.7.5');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8) Gecko/20051129 Camino/1.0b1+');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.0b1+');
        $this->assertEqual($analyzer->getApplicationName(),'Camino');
        $this->assertEqual($analyzer->getTechnologyName(),'Gecko');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.8');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.9a1) Gecko/20060107 SeaMonkey/1.5a');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.5a');
        $this->assertEqual($analyzer->getApplicationName(),'SeaMonkey');
        $this->assertEqual($analyzer->getTechnologyName(),'Gecko');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.9a1');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041217 MultiZilla/1.6.4.0b');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.6.4.0b');
        $this->assertEqual($analyzer->getApplicationName(),'MultiZilla');
        $this->assertEqual($analyzer->getTechnologyName(),'Gecko');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.7.5');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8b2) Gecko/20050328 Firefox/1.0+');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.0+');
        $this->assertEqual($analyzer->getApplicationName(),'Firefox');
        $this->assertEqual($analyzer->getTechnologyName(),'Gecko');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.8b2');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.12) Gecko/20051010 Firefox/1.0.4 (Ubuntu package 1.0.7)');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.0.4');
        $this->assertEqual($analyzer->getApplicationName(),'Firefox');
        $this->assertEqual($analyzer->getTechnologyName(),'Gecko');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.7.12');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8b) Gecko/20050217');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.8b');
        $this->assertEqual($analyzer->getApplicationName(),'Mozilla');
        $this->assertEqual($analyzer->getTechnologyName(),'Gecko');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.8b');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());
	}
/*
Googlebot/2.1 (+http://www.google.com/bot.html)
Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)
*/
	function testGoogle() {
		$analyzer = new UserAgentAnalyzer();

		$analyzer->setUserAgent('Googlebot/2.1 (+http://www.google.com/bot.html)');
        $this->assertEqual($analyzer->getApplicationVersion(),'2.1');
        $this->assertEqual($analyzer->getApplicationName(),'Googlebot');
        $this->assertEqual($analyzer->getTechnologyName(),'Googlebot');
        $this->assertEqual($analyzer->getTechnologyVersion(),'2.1');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
        $this->assertEqual($analyzer->getApplicationVersion(),'2.1');
        $this->assertEqual($analyzer->getApplicationName(),'Googlebot');
        $this->assertEqual($analyzer->getTechnologyName(),'Googlebot');
        $this->assertEqual($analyzer->getTechnologyVersion(),'2.1');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());
	}
/*
msnbot/1.0 (+http://search.msn.com/msnbot.htm)
*/
	function testMsnbot() {
		$analyzer = new UserAgentAnalyzer();

		$analyzer->setUserAgent('msnbot/1.0 (+http://search.msn.com/msnbot.htm)');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.0');
        $this->assertEqual($analyzer->getApplicationName(),'msnbot');
        $this->assertEqual($analyzer->getTechnologyName(),'msnbot');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.0');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());
	}
/*
Mozilla/5.0 (compatible; Konqueror/3.1)
Mozilla/5.0 (compatible; Konqueror/3.4) KHTML/3.4.3 (like Gecko) (Kubuntu package 4:3.4.3-0ubuntu1)
*/
	function testKonqueror() {
		$analyzer = new UserAgentAnalyzer();

		$analyzer->setUserAgent('Mozilla/5.0 (compatible; Konqueror/3.1)');
        $this->assertEqual($analyzer->getApplicationVersion(),'3.1');
        $this->assertEqual($analyzer->getApplicationName(),'Konqueror');
        $this->assertEqual($analyzer->getTechnologyName(),'KHTML');
        $this->assertEqual($analyzer->getTechnologyVersion(),'3.1');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (compatible; Konqueror/3.4) KHTML/3.4.3 (like Gecko) (Kubuntu package 4:3.4.3-0ubuntu1)');
        $this->assertEqual($analyzer->getApplicationVersion(),'3.4');
        $this->assertEqual($analyzer->getApplicationName(),'Konqueror');
        $this->assertEqual($analyzer->getTechnologyName(),'KHTML');
        $this->assertEqual($analyzer->getTechnologyVersion(),'3.4.3');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());
	}
/*
MJ12bot/v1.0.4 (http://majestic12.co.uk/bot.php?+)
MJ12bot/v1.0.7 (http://majestic12.co.uk/bot.php?+)
*/
	function testMJ12bot() {
		$analyzer = new UserAgentAnalyzer();

		$analyzer->setUserAgent('MJ12bot/v1.0.4 (http://majestic12.co.uk/bot.php?+)');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.0.4');
        $this->assertEqual($analyzer->getApplicationName(),'MJ12bot');
        $this->assertEqual($analyzer->getTechnologyName(),'MJ12bot');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.0.4');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());
	}
//Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)
	function testYahoo() {
		$analyzer = new UserAgentAnalyzer();

		$analyzer->setUserAgent('Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)');
        $this->assertEqual($analyzer->getApplicationVersion(),'');
        $this->assertEqual($analyzer->getApplicationName(),'Yahoo! Slurp');
        $this->assertEqual($analyzer->getTechnologyName(),'Yahoo! Slurp');
        $this->assertEqual($analyzer->getTechnologyVersion(),'');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());
	}
	
/*
Mozilla/4.8 [en] (Windows NT 5.0; U)
Mozilla/4.5 [en] (Win98; I)
Mozilla/4.51 [fr] (Win95; I)
*/
	function testNetscape() {
		$analyzer = new UserAgentAnalyzer();
		
		$analyzer->setUserAgent('Mozilla/4.8 [en] (Windows NT 5.0; U)');
        $this->assertEqual($analyzer->getApplicationVersion(),'4.8');
        $this->assertEqual($analyzer->getApplicationName(),'Netscape');
        $this->assertEqual($analyzer->getTechnologyName(),'Netscape');
        $this->assertEqual($analyzer->getTechnologyVersion(),'4.8');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());
		
		$analyzer->setUserAgent('Mozilla/4.51 [fr] (Win95; I)');
        $this->assertEqual($analyzer->getApplicationVersion(),'4.51');
        $this->assertEqual($analyzer->getApplicationName(),'Netscape');
        $this->assertEqual($analyzer->getTechnologyName(),'Netscape');
        $this->assertEqual($analyzer->getTechnologyVersion(),'4.51');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

	}

	function testOthers() {
		$analyzer = new UserAgentAnalyzer();
		
		$analyzer->setUserAgent('W3C_Validator/1.432.2.10');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.432.2.10');
        $this->assertEqual($analyzer->getApplicationName(),'W3C Validator');
        $this->assertEqual($analyzer->getTechnologyName(),'W3C Validator');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.432.2.10');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Jigsaw/2.2.3 W3C_CSS_Validator_JFouffa/2.0');
        $this->assertEqual($analyzer->getApplicationVersion(),'2.0');
        $this->assertEqual($analyzer->getApplicationName(),'W3C CSS Validator');
        $this->assertEqual($analyzer->getTechnologyName(),'W3C CSS Validator');
        $this->assertEqual($analyzer->getTechnologyVersion(),'2.0');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 compatible ZyBorg/1.0 Dead Link Checker (wn.dlc@looksmart.net; http://www.WISEnutbot.com)');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.0');
        $this->assertEqual($analyzer->getApplicationName(),'ZyBorg');
        $this->assertEqual($analyzer->getTechnologyName(),'ZyBorg');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.0');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 compatible ZyBorg/1.0 (wn-14.zyborg@looksmart.net; http://www.WISEnutbot.com)');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.0');
        $this->assertEqual($analyzer->getApplicationName(),'ZyBorg');
        $this->assertEqual($analyzer->getTechnologyName(),'ZyBorg');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.0');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('scrutor.dk abuse@scrutor.dk');
        $this->assertEqual($analyzer->getApplicationVersion(),'');
        $this->assertEqual($analyzer->getApplicationName(),'Scrutor Crawler');
        $this->assertEqual($analyzer->getTechnologyName(),'Scrutor Crawler');
        $this->assertEqual($analyzer->getTechnologyVersion(),'');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('KompassBot/0.8 (Kompass WebSearch Robot; http://websearch.kompass.com/bot/; nutch-agent@lucene.apache.org)');
        $this->assertEqual($analyzer->getApplicationVersion(),'0.8');
        $this->assertEqual($analyzer->getApplicationName(),'KompassBot');
        $this->assertEqual($analyzer->getTechnologyName(),'KompassBot');
        $this->assertEqual($analyzer->getTechnologyVersion(),'0.8');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (compatible; iCab 3.0.2; Macintosh; U; PPC Mac OS X)');
        $this->assertEqual($analyzer->getApplicationVersion(),'3.0.2');
        $this->assertEqual($analyzer->getApplicationName(),'iCab');
        $this->assertEqual($analyzer->getTechnologyName(),'iCab');
        $this->assertEqual($analyzer->getTechnologyVersion(),'3.0.2');
        $this->assertTrue(!$analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Gigabot/2.0/gigablast.com/spider.html');
        $this->assertEqual($analyzer->getApplicationVersion(),'2.0');
        $this->assertEqual($analyzer->getApplicationName(),'Gigabot');
        $this->assertEqual($analyzer->getTechnologyName(),'Gigabot');
        $this->assertEqual($analyzer->getTechnologyVersion(),'2.0');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());

		$analyzer->setUserAgent('SurveyBot/2.3 (Whois Source)');
        $this->assertEqual($analyzer->getApplicationVersion(),'2.3');
        $this->assertEqual($analyzer->getApplicationName(),'SurveyBot');
        $this->assertEqual($analyzer->getTechnologyName(),'SurveyBot');
        $this->assertEqual($analyzer->getTechnologyVersion(),'2.3');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());

		$analyzer->setUserAgent('NutchCVS/0.8-dev (Nutch; http://lucene.apache.org/nutch/bot.html; nutch-agent@lucene.apache.org)');
        $this->assertEqual($analyzer->getApplicationVersion(),'0.8');
        $this->assertEqual($analyzer->getApplicationName(),'NutchCVS');
        $this->assertEqual($analyzer->getTechnologyName(),'NutchCVS');
        $this->assertEqual($analyzer->getTechnologyVersion(),'0.8');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/5.0 (compatible; heritrix/1.7.1-200601241521 +http://netarkivet.dk/website/info.html)');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.7.1');
        $this->assertEqual($analyzer->getApplicationName(),'Heritrix');
        $this->assertEqual($analyzer->getTechnologyName(),'Heritrix');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.7.1');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());

		$analyzer->setUserAgent('NetResearchServer/4.0(loopimprovements.com/robot.html)');
        $this->assertEqual($analyzer->getApplicationVersion(),'4.0');
        $this->assertEqual($analyzer->getApplicationName(),'NetResearchServer');
        $this->assertEqual($analyzer->getTechnologyName(),'NetResearchServer');
        $this->assertEqual($analyzer->getTechnologyVersion(),'4.0');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; Cerberian Drtrs Version-3.2-Build-0)');
        $this->assertEqual($analyzer->getApplicationVersion(),'3.2');
        $this->assertEqual($analyzer->getApplicationName(),'Cerberian Drtrs');
        $this->assertEqual($analyzer->getTechnologyName(),'Cerberian Drtrs');
        $this->assertEqual($analyzer->getTechnologyVersion(),'3.2');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)');
        $this->assertEqual($analyzer->getApplicationVersion(),'5');
        $this->assertEqual($analyzer->getApplicationName(),'WinHttp');
        $this->assertEqual($analyzer->getTechnologyName(),'WinHttp');
        $this->assertEqual($analyzer->getTechnologyVersion(),'5');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('Microsoft-WebDAV-MiniRedir/5.1.2600');
        $this->assertEqual($analyzer->getApplicationVersion(),'5.1.2600');
        $this->assertEqual($analyzer->getApplicationName(),'Microsoft-WebDAV-MiniRedir');
        $this->assertEqual($analyzer->getTechnologyName(),'Microsoft-WebDAV-MiniRedir');
        $this->assertEqual($analyzer->getTechnologyVersion(),'5.1.2600');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('findlinks/1.1.1-a5 (+http://wortschatz.uni-leipzig.de/findlinks/)');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.1.1-a5');
        $this->assertEqual($analyzer->getApplicationName(),'findlinks');
        $this->assertEqual($analyzer->getTechnologyName(),'findlinks');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.1.1-a5');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());

		$analyzer->setUserAgent('findlinks/1.1.1-a1 (+http://wortschatz.uni-leipzig.de/findlinks/)');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.1.1-a1');
        $this->assertEqual($analyzer->getApplicationName(),'findlinks');
        $this->assertEqual($analyzer->getTechnologyName(),'findlinks');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.1.1-a1');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());

		$analyzer->setUserAgent('sproose/0.1 (sproose bot; http://www.sproose.com/bot.html; crawler@sproose.com)');
        $this->assertEqual($analyzer->getApplicationVersion(),'0.1');
        $this->assertEqual($analyzer->getApplicationName(),'sproose');
        $this->assertEqual($analyzer->getTechnologyName(),'sproose');
        $this->assertEqual($analyzer->getTechnologyVersion(),'0.1');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());

		$analyzer->setUserAgent('sproose/0.1-alpha (sproose crawler; http://www.sproose.com/bot.html; crawler@sproose.com)');
        $this->assertEqual($analyzer->getApplicationVersion(),'0.1-alpha');
        $this->assertEqual($analyzer->getApplicationName(),'sproose');
        $this->assertEqual($analyzer->getTechnologyName(),'sproose');
        $this->assertEqual($analyzer->getTechnologyVersion(),'0.1-alpha');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());

		$analyzer->setUserAgent('voyager/1.0');
        $this->assertEqual($analyzer->getApplicationVersion(),'1.0');
        $this->assertEqual($analyzer->getApplicationName(),'voyager');
        $this->assertEqual($analyzer->getTechnologyName(),'voyager');
        $this->assertEqual($analyzer->getTechnologyVersion(),'1.0');
        $this->assertNull($analyzer->isRobot());
        $this->assertNull($analyzer->isSearchEngine());

		$analyzer->setUserAgent('Mozilla/4.0 (compatible; MSIE 5.0; Windows NT; Girafabot; girafabot at girafa dot com; http://www.girafa.com)');
        $this->assertEqual($analyzer->getApplicationVersion(),'');
        $this->assertEqual($analyzer->getApplicationName(),'Girafabot');
        $this->assertEqual($analyzer->getTechnologyName(),'Girafabot');
        $this->assertEqual($analyzer->getTechnologyVersion(),'');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue($analyzer->isSearchEngine());

		$analyzer->setUserAgent('Jakarta Commons-HttpClient/3.0-rc4');
        $this->assertEqual($analyzer->getApplicationVersion(),'3.0-rc4');
        $this->assertEqual($analyzer->getApplicationName(),'Jakarta Commons-HttpClient');
        $this->assertEqual($analyzer->getTechnologyName(),'Jakarta Commons-HttpClient');
        $this->assertEqual($analyzer->getTechnologyVersion(),'3.0-rc4');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		$analyzer->setUserAgent('khttp');
        $this->assertNull($analyzer->getApplicationVersion());
        $this->assertEqual($analyzer->getApplicationName(),'khttp');
        $this->assertEqual($analyzer->getTechnologyName(),'khttp');
        $this->assertNull($analyzer->getTechnologyVersion());
        $this->assertTrue($analyzer->isRobot());
        $this->assertNull($analyzer->isSearchEngine());

		$analyzer->setUserAgent('dandirbot');
        $this->assertNull($analyzer->getApplicationVersion());
        $this->assertEqual($analyzer->getApplicationName(),'dandirbot');
        $this->assertEqual($analyzer->getTechnologyName(),'dandirbot');
        $this->assertNull($analyzer->getTechnologyVersion());
        $this->assertTrue($analyzer->isRobot());
        $this->assertNull($analyzer->isSearchEngine());

		$analyzer->setUserAgent('Pingdom GIGRIB (http://www.pingdom.com)');
        $this->assertNull($analyzer->getApplicationVersion());
        $this->assertEqual($analyzer->getApplicationName(),'Pingdom GIGRIB (http://www.pingdom.com)');
        $this->assertEqual($analyzer->getTechnologyName(),'Pingdom GIGRIB (http://www.pingdom.com)');
        $this->assertNull($analyzer->getTechnologyVersion());
        $this->assertTrue($analyzer->isRobot());
        $this->assertNull($analyzer->isSearchEngine());

		$analyzer->setUserAgent('EmeraldShield.com Web Spider (http://www.emeraldshield.com/webbot.aspx)');
        $this->assertNull($analyzer->getApplicationVersion());
        $this->assertEqual($analyzer->getApplicationName(),'EmeraldShield.com Web Spider (http://www.emeraldshield.com/webbot.aspx)');
        $this->assertEqual($analyzer->getTechnologyName(),'EmeraldShield.com Web Spider (http://www.emeraldshield.com/webbot.aspx)');
        $this->assertNull($analyzer->getTechnologyVersion());
        $this->assertTrue($analyzer->isRobot());
        $this->assertNull($analyzer->isSearchEngine());

		$analyzer->setUserAgent('HenryTheMiragoRobot (http://www.miragorobot.com/scripts/dkinfo.asp)');
        $this->assertNull($analyzer->getApplicationVersion());
        $this->assertEqual($analyzer->getApplicationName(),'HenryTheMiragoRobot (http://www.miragorobot.com/scripts/dkinfo.asp)');
        $this->assertEqual($analyzer->getTechnologyName(),'HenryTheMiragoRobot (http://www.miragorobot.com/scripts/dkinfo.asp)');
        $this->assertNull($analyzer->getTechnologyVersion());
        $this->assertTrue($analyzer->isRobot());
        $this->assertNull($analyzer->isSearchEngine());

		$analyzer->setUserAgent('NPBot/3 (NPBot; http://www.nameprotect.com; npbot@nameprotect.com)');
        $this->assertEqual($analyzer->getApplicationVersion(),'3');
        $this->assertEqual($analyzer->getApplicationName(),'NPBot');
        $this->assertEqual($analyzer->getTechnologyName(),'NPBot');
        $this->assertEqual($analyzer->getTechnologyVersion(),'3');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());

		//curl/7.13.1 (powerpc-apple-darwin8.0) libcurl/7.13.1 OpenSSL/0.9.7i zlib/1.2.3

		$analyzer->setUserAgent('curl/7.13.1 (powerpc-apple-darwin8.0) libcurl/7.13.1 OpenSSL/0.9.7i zlib/1.2.3');
        $this->assertEqual($analyzer->getApplicationVersion(),'7.13.1');
        $this->assertEqual($analyzer->getApplicationName(),'curl');
        $this->assertEqual($analyzer->getTechnologyName(),'curl');
        $this->assertEqual($analyzer->getTechnologyVersion(),'7.13.1');
        $this->assertTrue($analyzer->isRobot());
        $this->assertTrue(!$analyzer->isSearchEngine());
	}
}
?>