<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Config/Setup.php';

require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

$id = requestGetNumber('id');
$sql = "delete from page_history where id=".$id;
$row = Database::delete($sql);

redirect('index.php');
?>