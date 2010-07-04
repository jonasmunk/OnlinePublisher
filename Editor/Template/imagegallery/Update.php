<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ImageGallery
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Page.php';

$id = requestPostNumber('id');
$title = requestPostText('title');
$text = requestPostText('text');
$imagesize = requestPostNumber('imagesize');
$showtitle = requestPostCheckbox('showtitle');
$shownote = requestPostCheckbox('shownote');
$rotate = requestPostNumber('rotate');

$sql="update imagegallery set title=".Database::text($title).
",`text`=".Database::text($text).
",imagesize=".Database::int($imagesize).
",showtitle=".Database::boolean($showtitle).
",shownote=".Database::boolean($shownote).
",rotate=".Database::int($rotate).
" where page_id=".$id;
Database::update($sql);

Page::markChanged($id);

redirect('Text.php');
?>