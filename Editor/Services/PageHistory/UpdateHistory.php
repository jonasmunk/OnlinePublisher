<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Config/Setup.php';

require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id');
$message = Request::getString('message');
$sql = "update page_history set message=".Database::text($message)." where id=".$id;
$row = Database::update($sql);

redirect('index.php');
?>