<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

$id = Request::getInt('id',0);
$label = Request::getString('label');

$mode = Request::getString('mode');

$enabled = false;
$default = false;
$hidden = false;
if ($mode=='inactive') {
	// keep defaults
}
elseif ($mode=='possible') {
	$enabled = true;
	$default = false;
	$hidden = false;
}
elseif ($mode=='choosen') {
	$enabled = true;
	$default = true;
	$hidden = false;
}
elseif ($mode=='automatic') {
	$enabled = true;
	$default = true;
	$hidden = true;
}


$sql="update search set".
" pagesenabled=".Database::boolean($enabled).
",pageslabel=".Database::text($label).
",pagesdefault=".Database::boolean($default).
",pageshidden=".Database::boolean($hidden).
" where page_id=".$id;
Database::update($sql);

$sql="update page set changed=now() where id=".$id;
Database::update($sql);


Response::redirect('Pages.php');
?>