<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests
 */

require_once('../../Config/Setup.php');
require_once('../Include/Security.php');

require_once('../Include/Functions.php');
require_once('../Libraries/simpletest/unit_tester.php');
require_once('../Libraries/simpletest/reporter.php');

$path = requestGetText('path');

$test = &new GroupTest($path);
$test->addTestFile($path);
$test->run(new HtmlReporter());
?>