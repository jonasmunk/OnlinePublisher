<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates
 */
require_once '../../Config/Setup.php';
require_once '../Include/Security.php';
require_once '../Include/Functions.php';
require_once '../Include/Publishing.php';

$id=requestGetNumber('id',-1);
if (!($id>0)) {
	$id = getPageId();
}
if (!($id>0)) {
	$id=findPage('home');
}
if ($id>0) {
	setPageId($id);
	$sql = "select `template`.`unique` as template,`design`.`unique` as design from page,template,design where page.template_id = template.id and page.design_id=design.object_id and page.id=".$id;
	if ($row = Database::selectFirst($sql)) {
		setPageDesign($row['design']);
		redirect($baseUrl.'Editor/Template/'.$row['template'].'/Edit.php?id='.$id);
	}
}
else {
	echo "no page";
}
?>