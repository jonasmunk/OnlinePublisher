<?php
require_once('../../Editor/Include/Public.php');

$number = Request::getString('number');

$sql = "select DATE_FORMAT(waterusage.date, '%d-%m-%Y') as `date`,`value`,UNIX_TIMESTAMP(waterusage.date) as time".
	" from waterusage,watermeter where waterusage.`watermeter_id`=watermeter.`object_id` and number = ".Database::text($number)." order by waterusage.`date`";
$rows = Database::selectAll($sql);

Response::sendObject($rows);
?>