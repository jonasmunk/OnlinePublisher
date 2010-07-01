<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../../Config/Setup.php';
require_once '../../../../Include/Security.php';
require_once '../../../../Classes/Request.php';
require_once '../../../../Classes/Page.php';
require_once '../../../../Classes/Log.php';

$pageId = Request::getInt('pageId');
$type = Request::getString('type');
$controllerClass = ucfirst($type).'Controller';
require_once '../../../../Classes/Parts/'.$controllerClass.'.php';

$ctrl = new $controllerClass();

$part = $ctrl->getFromRequest();
$part->save();

Page::markChanged($pageId);

header("Content-Type: text/html; charset=UTF-8");
echo $ctrl->render($part,$pageId);
?>