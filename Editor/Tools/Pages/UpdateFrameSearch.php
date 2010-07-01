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
" searchenabled=".sqlBoolean($enabled).
",searchbuttontitle=".sqlText($buttontitle).
",searchpage_id=".$page.
",searchpages=".sqlBoolean($pages).
",searchimages=".sqlBoolean($images).
",searchfiles=".sqlBoolean($files).
",searchnews=".sqlBoolean($news).
",searchpersons=".sqlBoolean($persons).
",searchproducts=".sqlBoolean($products).
" where id=".$id;

Database::update($sql);

redirect('EditFrameSearch.php?id='.$id);
?>