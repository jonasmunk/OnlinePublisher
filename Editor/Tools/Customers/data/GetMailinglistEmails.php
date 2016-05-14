<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');
$list = Mailinglist::load($data->id);
$mails = $list->getEmails();
Response::sendObject($mails);
?>