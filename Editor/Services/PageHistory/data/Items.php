<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Include/Private.php';

$pageId = Request::getInt('pageId');

$writer = new ItemsWriter();



$sql="select page_history.id,UNIX_TIMESTAMP(page_history.time) as time,page_history.message,object.title".
" from page_history left join object on object.id=page_history.user_id where page_id=".Database::int($pageId)." order by page_history.time desc";

$writer->startItems();

$result = Database::select($sql);
while ($row = Database::next($result)) {
	$writer->item(array(
		'icon' => 'common/time',
		'value' => $row['id'],
		'title' => Dates::formatLongDateTime($row['time'])
	));
}
Database::free($result);

$writer->endItems();
?>