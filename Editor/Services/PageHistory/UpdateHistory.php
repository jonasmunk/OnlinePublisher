<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Config/Setup.php';

require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

$id = requestPostNumber('id');
$message = requestPostText('message');
$sql = "update page_history set message=".sqlText($message)." where id=".$id;
$row = Database::update($sql);

redirect('index.php');
?>