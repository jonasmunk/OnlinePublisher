<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';


$list = array();

$sql = "select id,title from page order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$list[] = array(
		'id' => intval($row['id']),
		'title' => $row['title']
	);
}
Database::free($result);

Response::sendUnicodeObject($list);
?>