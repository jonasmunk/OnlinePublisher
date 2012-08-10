<?php
/**
 * @package OnlinePublisher
 * @subpackage Public.Services.Statistics
 */

require_once '../../../Editor/Include/Public.php';

header('Content-type: text/xml');
echo '<?xml version="1.0"?>'.
'<statistics>';

$sql="select count(id) as num,DATE_FORMAT(time,'%d') as day,DATE_FORMAT(time,'%m') as month,DATE_FORMAT(time,'%Y') as year from statistics group by DATE_FORMAT(time,'%Y%m%d') order by time desc";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	echo '<result hits="'.$row['num'].'" day="'.$row['day'].'" month="'.$row['month'].'" year="'.$row['year'].'"/>';
}
Database::free($result);

echo "</statistics>";
?>