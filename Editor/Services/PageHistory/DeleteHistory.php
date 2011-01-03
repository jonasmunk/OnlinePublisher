<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Config/Setup.php';

require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id');
$sql = "delete from page_history where id=".$id;
$row = Database::delete($sql);

Response::redirect('index.php');
?>