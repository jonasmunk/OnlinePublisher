<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Include/Functions.php';

$id = Request::getInt('id',0);
$enabled = Request::getCheckbox('enabled');
$buttontitle = Request::getString('buttontitle');
$page = Request::getInt('page',0);
$pages = Request::getCheckbox('pages');
$images = Request::getCheckbox('images');
$files = Request::getCheckbox('files');
$news = Request::getCheckbox('news');
$persons = Request::getCheckbox('persons');
$products = Request::getCheckbox('products');

$sql="update frame set".
" searchenabled=".Database::boolean($enabled).
",searchbuttontitle=".Database::text($buttontitle).
",searchpage_id=".$page.
",searchpages=".Database::boolean($pages).
",searchimages=".Database::boolean($images).
",searchfiles=".Database::boolean($files).
",searchnews=".Database::boolean($news).
",searchpersons=".Database::boolean($persons).
",searchproducts=".Database::boolean($products).
" where id=".$id;

Database::update($sql);

redirect('EditFrameSearch.php?id='.$id);
?>