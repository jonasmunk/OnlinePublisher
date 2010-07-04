<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';

$id = requestPostNumber('id',0);
$enabled = requestPostCheckbox('enabled');
$buttontitle = requestPostText('buttontitle');
$page = requestPostNumber('page',0);
$pages = requestPostCheckbox('pages');
$images = requestPostCheckbox('images');
$files = requestPostCheckbox('files');
$news = requestPostCheckbox('news');
$persons = requestPostCheckbox('persons');
$products = requestPostCheckbox('products');

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