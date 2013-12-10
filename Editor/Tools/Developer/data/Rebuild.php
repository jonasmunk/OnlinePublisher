<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$cache = array();

$list = ClassService::getClasses();
foreach ($list as $item) {
	$cache[$item['name']] = $item['relativePath'];
}

$text = var_export($cache,true);

$text = "<?php
if (!isset(\$GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
\n\$HUI_EDITOR_CLASSES = ".$text."\n?>";

$success = FileSystemService::writeStringToFile($text,$basePath.'Editor/Info/Classpaths.php');
if (!$success) {
	Response::internalServerError();
}
?>