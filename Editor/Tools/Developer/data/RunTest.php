<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

$all = Request::getBoolean('all');
$test = Request::getString('test');
$group = Request::getString('group');

if ($all) {
	TestService::runAllTests();
} else if ($test) {
	TestService::runTest($test);
} else if ($group) {
	TestService::runTestsInGroup($group);
}
?>