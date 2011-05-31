<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/In2iGui.php';

$move = Request::getInt('move',0);
$targetItem = Request::getInt('targetItem',0);
$targetHierarchy = Request::getInt('targetHierarchy',0);

$response = Hierarchy::relocateItem($move,$targetItem,$targetHierarchy);

In2iGui::sendObject($response);
?>