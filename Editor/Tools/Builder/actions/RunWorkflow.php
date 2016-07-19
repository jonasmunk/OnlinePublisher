<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Builder
 */
require_once '../../../Include/Private.php';

$recipe = Request::getString('recipe');

$parser = new WorkflowParser();
$flow = $parser->parse($recipe);

$result = $flow->run();

Log::debug($result);

Response::sendObject([
  'description' => $flow->getDescription(),
  'result' => $result
]);
?>