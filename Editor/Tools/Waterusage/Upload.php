<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterssage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/FileUpload.php';
require_once '../../Classes/Objects/Waterusage.php';
require_once '../../Classes/Database.php';

$upload = new FileUpload();
$upload->process('file');
$path = $upload->getFilePath();
$contents = file_get_contents($upload->getFilePath());

$handle = @fopen($path, "r");
if ($handle) {
    while (!feof($handle)) {
        $line = fgets($handle, 4096);
		handleLine($line);
    }
	fclose($handle);
}

echo 'SUCCESS';

function handleLine($line) {
	$words = preg_split('/;/',$line);
	$year = $words[0];
	$number = $words[1];
	$value = $words[2];
	if (!$number) {
		return;
	}
	$sql = "select object_id as id from waterusage where year = ".Database::int($year)." and number = ".Database::text($number);
	$row = Database::selectFirst($sql);
	if ($row) {
		$usage = Waterusage::load($row['id']);
		if ($usage) {
			$usage->setValue($value);
			$usage->save();
			$usage->publish();
		}
	} else {
		$usage = new Waterusage();
		$usage->setNumber($number);
		$usage->setValue($value);
		$usage->setYear($year);
		$usage->save();
		$usage->publish();
	}
}
?>