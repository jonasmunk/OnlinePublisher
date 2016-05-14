<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Html
 */
require_once '../../../Include/Private.php';

$id = Request::getId();
$title = Request::getString('title');
$html = Request::getString('html');

$valid = DOMUtils::isValidFragment($html);

$sql="update html set".
" html=".Database::text($html).
",title=".Database::text($title).
",valid=".Database::boolean($valid).
" where page_id=".$id;
Database::update($sql);

PageService::markChanged($id);

Response::sendObject(array('valid'=>$valid));
?>