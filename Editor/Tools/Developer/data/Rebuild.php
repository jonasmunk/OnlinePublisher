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

$text = "<?php\n\$classes = ".$text."\n?>";

FileSystemService::writeStringToFile($text,$basePath.'Editor/Info/Classpaths.php');
?>