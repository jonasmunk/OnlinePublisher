<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../../Config/Setup.php';
require_once '../../../../Include/Security.php';
require_once '../../../../Classes/Request.php';
require_once '../../../../Classes/In2iGui.php';
require_once '../../../../Classes/Page.php';

$id = Request::getInt('id');

//$page = Page::load($id);
//$page->toUnicode();

$arr = array(
	'changed'=>Page::isChanged($id)
);

In2iGui::sendObject($arr);
?>