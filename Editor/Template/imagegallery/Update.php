<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ImageGallery
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id');
$title = Request::getString('title');
$text = Request::getString('text');
$imagesize = Request::getInt('imagesize');
$showtitle = Request::getCheckbox('showtitle');
$shownote = Request::getCheckbox('shownote');
$rotate = Request::getInt('rotate');

$sql="update imagegallery set title=".Database::text($title).
",`text`=".Database::text($text).
",imagesize=".Database::int($imagesize).
",showtitle=".Database::boolean($showtitle).
",shownote=".Database::boolean($shownote).
",rotate=".Database::int($rotate).
" where page_id=".$id;
Database::update($sql);

PageService::markChanged($id);

Response::redirect('Text.php');
?>