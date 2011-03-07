<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Formats/CSVWriter.php';

$view = Request::getBoolean('view');

if ($view) {
	header('Content-Type: text/plain');
} else {
	header('Content-Type: application/csv');
	Response::contentDisposition('waterusage-'.DateUtils::formatCSV(time()).'.csv');
}

$sql = "SELECT UNIX_TIMESTAMP(object.updated) as updated,UNIX_TIMESTAMP(waterusage.date) AS `date`,waterusage.number,CONVERT(waterusage.number,UNSIGNED) AS numberformatted,waterusage.year,waterusage.value FROM object,waterusage WHERE object.id=waterusage.object_id ORDER BY year,numberformatted";

$writer = new CSVWriter();

$writer->string('Number')->string('Year')->string('Date')->string('Updated')->string('Value');
$result = Database::select($sql);
while ($row = Database::next($result)) {	
	$writer->newLine()->string($row['number'])->string($row['year'])->date($row['date'])->date($row['updated'])->text($row['value']);
}
Database::close($result);
?>