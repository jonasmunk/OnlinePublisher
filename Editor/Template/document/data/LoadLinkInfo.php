<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

if ($info = LinkService::getLinkInfo($id)) {
	Response::sendObject(array(
		'id' => $info->getId(),
		'text' => $info->getSourceText(),
		'type' => $info->getTargetType(),
		'targetId' => $info->getTargetId(),
		'targetValue' => $info->getTargetValue(),
		'scope' => $info->getPartId()>0 ? 'part' : 'page',
		'rendering' => 
			'<p><strong>'.$info->getTargetTitle().'</strong></p>
			<p class="hui_rendering_dimmed">'.LinkService::translateLinkType($info->getTargetType()).'</p>'.
			($info->getPartId()>0 ? '<p style="margin-top: 5px;">Kun dette afsnit</p>' : '')
	));
}
?>