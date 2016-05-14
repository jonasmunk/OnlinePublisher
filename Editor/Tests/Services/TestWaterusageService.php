<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestWaterusageService extends UnitTestCase {
    
    function testParseAddress() {
		$parsed = WaterusageService::parseAddress('Bisnapkrat   3, Bisnap, 9370 Hals');
		$this->assertEqual('Bisnapkrat   3',$parsed['street']);
		$this->assertEqual('9370',$parsed['zipcode']);
		$this->assertEqual('Hals',$parsed['city']);
		
		$parsed = WaterusageService::parseAddress('Bisnapkrat 3,Bisnap,9370 Hals');
		$this->assertEqual('Bisnapkrat 3',$parsed['street']);
		$this->assertEqual('9370',$parsed['zipcode']);
		$this->assertEqual('Hals',$parsed['city']);
		
		$parsed = WaterusageService::parseAddress('Rylegyde   1, 01 TH, 9370 Hals');
		$this->assertEqual('Rylegyde   1',$parsed['street']);
		$this->assertEqual('9370',$parsed['zipcode']);
		$this->assertEqual('Hals',$parsed['city']);
		
		
		
		$parsed = WaterusageService::parseAddress('Bisnapkrat   3, 9370 Hals');
		$this->assertEqual('Bisnapkrat   3',$parsed['street']);
		$this->assertEqual('9370',$parsed['zipcode']);
		$this->assertEqual('Hals',$parsed['city']);
		
		$parsed = WaterusageService::parseAddress('Søhesten  67 -  77, 9370 Hals');
		$this->assertEqual('Søhesten  67 -  77',$parsed['street']);
		$this->assertEqual('9370',$parsed['zipcode']);
		$this->assertEqual('Hals',$parsed['city']);
		
		$parsed = WaterusageService::parseAddress("\xd8sterled  52, 9370 Hals");
		$this->assertEqual("\xd8sterled  52",$parsed['street']);
		$this->assertEqual('9370',$parsed['zipcode']);
		$this->assertEqual('Hals',$parsed['city']);
		
		$parsed = WaterusageService::parseAddress('');
		$this->assertNull($parsed);

		$parsed = WaterusageService::parseAddress('gfdkllgjdksfljl');
		$this->assertNull($parsed);

		$parsed = WaterusageService::parseAddress('Rylegyde   1 01 TH 9370 Hals');
		$this->assertNull($parsed);
    }

	function testParsingAll() {
		global $basePath;
		$path = $basePath.'Editor/Tests/Resources/watermeters.csv';
		$handle = @fopen($path, "r");
		$this->assertTrue($handle,'Could not open: '.$path);
		if ($handle) {
		    while (!feof($handle)) {
		        $line = fgets($handle, 4096);
				
				$words = preg_split('/;/',$line);
				
				$address = @$words[1];
				if (Strings::startsWith($address,'Adresse') || Strings::isBlank($address)) {
					continue;
				}
				$parsed = WaterusageService::parseAddress($address);
				$this->assertNotNull($parsed,'Unable to parse: "'.$address.'"');
				
		    }
			fclose($handle);
		} else {
		}
	}
}
?>