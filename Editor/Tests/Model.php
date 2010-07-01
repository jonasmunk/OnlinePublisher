<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests
 */

require_once('../../Config/Setup.php');
require_once '../Include/Security.php';

if (! defined('SIMPLE_TEST')) {
    define('SIMPLE_TEST', $basePath.'Editor/Libraries/simpletest/');
}
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');

require_once($basePath.'Editor/Tests/Model/TestNews.php');

$test = new GroupTest('Model tests');
$test->addTestCase(new TestNews());
$test->run(new HtmlReporter());
?>