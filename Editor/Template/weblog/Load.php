<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';

$weblog = Weblog::load(InternalSession::getPageId());

$values = array(
	'id' => $weblog->getId(),
	'title' => $weblog->getTitle(),
	'blueprint' => $weblog->getPageBlueprintId(),
	'groups' => $weblog->getGroupIds()
);

In2iGui::sendUnicodeObject($values);
?>