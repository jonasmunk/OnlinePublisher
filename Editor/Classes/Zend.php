<?
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . $basePath.'Editor/Libraries/');
?>