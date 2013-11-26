<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestMarkupUtils extends UnitTestCase {
	
	function testFindScriptSegments() {
		$html = '<html><head><script type="text/javascript"></script></head><body><h1>Test</h1><script>if (true) {alert(0)}</script></body></html>';
		$result = MarkupUtils::findScriptSegments($html);
		//Log::debug(Strings::toJSON($result));
		$this->assertEqual(2,count($result));

		$first = substr($html,$result[0]['from'],$result[0]['to']-$result[0]['from']);
		$this->assertEqual('<script type="text/javascript"></script>',$first);

		$second = substr($html,$result[1]['from'],$result[1]['to']-$result[1]['from']);
		$this->assertEqual('<script>if (true) {alert(0)}</script>',$second);
	}
  
	function testMoveScriptsToBottom() {
		$html = '<html><head><script type="text/javascript"></script></head><body><h1>Test</h1><script>if (true) {alert(0)}</script></body></html>';
        $result = MarkupUtils::moveScriptsToBottom($html);
        $expected = '<html><head><!-- moved script --></head><body><h1>Test</h1><!-- moved script --><script type="text/javascript"></script><script>if (true) {alert(0)}</script></body></html>';
		print_r(Strings::escapeXML($result));
        $this->assertEqual($expected,$result);
    }
  
    function testTest() {
        $html = '<html>
            <head>
                <script type="text/javascript" src="dfhsfgfgdsgfhsj"/>
            </head>
            <body>
                <h1>Test</h1>
                <script>if (true) {alert(0)}</script>
            </body>
        </html>';
		$html = '<html><head><script type="text/javascript"></script></head><body><h1>Test</h1><script>if (true) {alert(0)}</script></body></html>';
        preg_match_all("/<script[^>]+\\/>|<script[^>]*>[\s\S]*<\\/script>/uU", $html, $matches);
        print_r(Strings::escapeXML($html));
        $html = str_replace($matches[0],'<!-- moved script -->',$html);
        print_r(Strings::escapeXML($html));
        
    }
}