<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates
 */
require_once '../../Config/Setup.php';
require_once '../Include/Security.php';
require_once '../Classes/Core/Response.php';
require_once '../Classes/Core/Request.php';
require_once '../Classes/Core/InternalSession.php';
require_once '../Classes/Services/RenderingService.php';

$id=Request::getId();
if (!($id>0)) {
	$id = InternalSession::getPageId();
}
if (!($id>0)) {
	$id = RenderingService::findPage('home');
}
if ($id>0) {
	InternalSession::setPageId($id);
	$sql = "select `template`.`unique` as template,`design`.`unique` as design from page,template,design where page.template_id = template.id and page.design_id=design.object_id and page.id=".$id;
	if ($row = Database::selectFirst($sql)) {
		InternalSession::setPageDesign($row['design']);
		if ($ctrl = TemplateService::getController($row['template'])) {
			if ($ctrl->isClientSide()) {
				Response::redirect($baseUrl.'Editor/Services/Preview/?id='.$id.'&edit=true');
			}
		}
		Response::redirect($baseUrl.'Editor/Template/'.$row['template'].'/Edit.php?id='.$id);
	}
}
else {
	echo "no page";
}
?>