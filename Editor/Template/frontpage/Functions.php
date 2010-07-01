<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
function getColumnCount() {
	$columnCount = 0;
	$sql = "select sum(`columns`) as cols from frontpage_cell where page_id=".getPageId()." group by row_id";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		if ($row['cols']>$columnCount) $columnCount=$row['cols'];
	}
	Database::free($result);
	return $columnCount;
}
?>