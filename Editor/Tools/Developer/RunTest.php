<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';

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