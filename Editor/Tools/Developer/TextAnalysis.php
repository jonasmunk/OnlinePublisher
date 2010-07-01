<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
$sql = "select `index` from page";

$result = Database::select($sql);
$frequency = array();
while ($row = Database::next($result)) {
	$text = $row['index'];
	$words = preg_split("/[\s,]+/", $text);
	foreach ($words as $word) {
	if (array_key_exists($word,$frequency)) {
		$frequency[$word]++;
	} else {
		$frequency[$word]=1;
	}
	}
}
closeDatabaseresult($result);

echo '<pre>';
asort($frequency);
print_r($frequency);
echo '</pre>';

?>