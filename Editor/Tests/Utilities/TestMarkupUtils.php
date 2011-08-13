<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Utilities
 */

class TestMarkupUtils extends UnitTestCase {
	
	function testFindScriptSegments() {
		$html = '<html><head><script type="text/javascript"></script></head><body><h1>Test</h1><script>if (true) {alert(0)}</script></body></html>';
		$result = MarkupUtils::findScriptSegments($html);
		//Log::debug(StringUtils::toJSON($result));
		$this->assertEqual(2,count($result));

		$first = substr($html,$result[0]['from'],$result[0]['to']-$result[0]['from']);
		$this->assertEqual('<script type="text/javascript"></script>',$first);

		$second = substr($html,$result[1]['from'],$result[1]['to']-$result[1]['from']);
		$this->assertEqual('<script>if (true) {alert(0)}</script>',$second);
	}
}