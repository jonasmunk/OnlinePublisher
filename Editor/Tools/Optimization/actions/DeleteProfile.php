<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$settings = OptimizationService::getSettings();

$url = Request::getString('url');

$newProfiles = array();
if (is_array($settings->profiles)) {
	foreach ($settings->profiles as $profile) {
		if ($url!=$profile->url) {
			$newProfiles[] = $profile;
		}
	}
}
$settings->profiles = $newProfiles;

OptimizationService::setSettings($settings);
?>