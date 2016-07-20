<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Builder
 */
require_once '../../../Include/Private.php';

$recipe = Request::getString('recipe');

$parser = new WorkflowParser();
$flow = $parser->parse($recipe);
if (!$flow) {
  Response::sendObject([
    'error' => 'Unable to parse workflow'
  ]);
}
else {
  $state = new WorkflowState();
  $result = $flow->run($state);

  Response::sendObject([
    'description' => $flow->getDescription(),
    'result' => $state->getData()
  ]);
}
?>