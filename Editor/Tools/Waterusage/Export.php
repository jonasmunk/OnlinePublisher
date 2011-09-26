<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Formats/CSVWriter.php';

$view = Request::getBoolean('view');

if ($view) {
	header('Content-Type: text/plain');
} else {
	header('Content-Type: application/csv');
	Response::contentDisposition('waterusage-'.DateUtils::formatCSV(time()).'.csv');
}

$sql = "SELECT UNIX_TIMESTAMP(object.updated) as updated,UNIX_TIMESTAMP(waterusage.date) AS `date`,watermeter.number,CONVERT(watermeter.number,UNSIGNED) AS numberformatted,waterusage.value FROM object,waterusage,watermeter WHERE object.id=waterusage.object_id and waterusage.watermeter_id=watermeter.object_id ORDER BY numberformatted,date";

$writer = new CSVWriter();

$writer->string('Number')->string('Date')->string('Value')->string('Updated');
$result = Database::select($sql);
while ($row = Database::next($result)) {	
	$writer->newLine()->string($row['number'])->date($row['date'])->string($row['value'])->date($row['updated']);
}
Database::close($result);
?>