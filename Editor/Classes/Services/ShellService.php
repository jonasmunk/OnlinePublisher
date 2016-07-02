<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ShellService {

	static function execute($cmd) {
    $path = ConfigurationService::getShellPath();
    if (!empty($path)) {
      $cmd = "export PATH=\"" . $path . "\"; " . $cmd;
    }
    return shell_exec($cmd);
	}
}