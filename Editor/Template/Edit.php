<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates
 */
require_once '../Include/Private.php';

$id=Request::getId();
if (!($id>0)) {
	$id = InternalSession::getPageId();
}
if (!($id>0)) {
	$id = RenderingService::findPage('home');
}
if (!($id>0)) {
	$id = PageService::getLatestPageId();
}
if ($id>0) {
	InternalSession::setPageId($id);
	$sql = "select `template`.`unique` as template,`design`.`unique` as design from page,template,design where page.template_id = template.id and page.design_id=design.object_id and page.id=".Database::int($id);
	if ($row = Database::selectFirst($sql)) {
		InternalSession::setPageDesign($row['design']);
		if ($ctrl = TemplateService::getController($row['template'])) {
			if ($ctrl->isClientSide()) {
				Response::redirect(ConfigurationService::getBaseUrl().'Editor/Services/Preview/?id='.$id.'&edit=true');
			}
		}
		Response::redirect(ConfigurationService::getBaseUrl().'Editor/Template/'.$row['template'].'/Edit.php?id='.$id);
	}
}
else {
	$gui = '
	<gui xmlns="uri:hui" padding="10">
		<controller source="controller.js"/>
		<box width="400" top="30" variant="rounded">
			<space left="30" right="30" top="10" bottom="10">
			<text align="center">
				<h>{da:Siden kunne ikke findes;en:The could not be found}</h>
				<p>{da:Er du sikker på at der findes nogen sider?;en:Are you sure there are any pages?}</p>
			</text>
			</space>
		</box>
	</gui>
	';
	UI::render($gui);
}
?>