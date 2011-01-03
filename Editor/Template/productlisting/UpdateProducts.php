<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ProductListing
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

$id = getProductListingId();
$groups = Request::getArray('group');
//print_r($groups);
//exit;

$sql="delete from productlisting_productgroup where page_id=".$id;
Database::delete($sql);

foreach ($groups as $group) {
	$sql="insert into productlisting_productgroup (page_id, productgroup_id)".
	" values (".$id.",".$group.")";
	Database::insert($sql);
}

$sql="update page set changed=now() where id=".$id;
Database::update($sql);


redirect('Products.php');
?>