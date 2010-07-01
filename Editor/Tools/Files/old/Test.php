<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
$file = fopen("http://www.apple.com/", "r");
if (!$file) {
	echo "Unable to open remote file.";
	exit;
}
while (!feof($file)) {
	$line = fgets($file, 1024);
	if (eregi ("<title>(.*)</title>", $line, $out)) {
		$title = $out[1];
		break;
	}
}
fclose($file);
echo $out[1];
?>