<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Formats
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestHtmlTableParser extends UnitTestCase {
	
	function testSimple() {
		$html = "<html>
			<table>
				<tr><td>hephey</td></tr>
				<tr>
					<td>abc</td>
					<td>123</td>
					<td><strong>Hello</strong></td>
				</tr>
			</table>
			</html>
		";
		
		
		$parser = new HtmlTableParser();
		$parsed = $parser->parse($html);
		
		$this->assertEqual(count($parsed),1,'There should be exactly one table');
		$this->assertEqual($parsed[0][0][0],'hephey');
		$this->assertEqual($parsed[0][1][0],'abc');
		$this->assertEqual($parsed[0][1][1],'123');
		$this->assertEqual($parsed[0][1][2],'Hello');		
	}
	
	function testParseFile() {
		global $basePath;
		$path = $basePath.'Editor/Tests/Resources/Kampprogram_haandbold.xls';
		$this->assertTrue(file_exists($path),'File does not exist: '.$path);
		$html = file_get_contents($path);
		$this->assertTrue(Strings::isNotBlank($html));

		$parser = new HtmlTableParser();
		$parsed = $parser->parse($html);
		
		$this->assertEqual(count($parsed),1);
		$table = $parsed[0];
		$this->assertEqual(count($table),91);
		
		$first = $table[0];
		$this->assertEqual($first[0],'Kampnr.');
		$this->assertEqual($first[1],'Runde');
		
		$second = $table[1];
		$this->assertEqual($second[0],'256835');
		$this->assertEqual($second[1],'2');
		$this->assertEqual($second[2],Strings::fromUnicode('Søn'));

	}

	function testParseUsingHeader() {
		global $basePath;
		$path = $basePath.'Editor/Tests/Resources/Kampprogram_haandbold.xls';
		$html = file_get_contents($path);

		$parser = new HtmlTableParser();
		$parsed = $parser->parseUsingHeader($html);
		
		$table = $parsed[0];
		$this->assertEqual(count($table),90);
		$firstRow = $table[0];
		$this->assertEqual($firstRow['Kampnr.'],'256835');
		$this->assertEqual($firstRow['Hjemmehold'],Strings::fromUnicode('Dybvad Håndbold Flauenskjold IF'));

		$secondRow = $table[1];
		$this->assertEqual($secondRow['Udehold'],Strings::fromUnicode('Bindslev/Tversted IF'));
	}
}