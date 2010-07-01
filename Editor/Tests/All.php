<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests
 */

require_once('../../Config/Setup.php');
require_once '../Include/Security.php';

require_once('../Libraries/simpletest/unit_tester.php');
require_once('../Libraries/simpletest/reporter.php');

$test = &new GroupTest('All tests');
$test->addTestFile('General/Files.php');
$test->addTestFile('General/UserAgentAnalyzer.php');
$test->addTestFile('General/HtmlDocument.php');
$test->addTestFile('General/TextDecoration.php');
$test->run(new HtmlReporter());
?>