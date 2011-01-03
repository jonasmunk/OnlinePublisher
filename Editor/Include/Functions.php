<?php
/**
 * General purpose functions that are likely to be used alot
 *
 * @package OnlinePublisher
 * @subpackage Include
 */
require_once $basePath.'Editor/Classes/Database.php';

/**
 * Redirects to an URL and exits, should be used instead of setting headers directly
 * @param string $url The url to be redirected to
 */
function redirect($url) {
	session_write_close();
	header('Location: '.$url);
	exit;
}
?>