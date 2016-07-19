<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class WorkflowService {

  public static function runWorkflow(Workflow $flow) {
    $state = new WorkflowState();
    $parser = new WorkflowParser();
    $flow = $parser->parse($flow->getRecipe());
    if ($flow) {
      $flow->run($state);
    }
    return $state;
  }
}
?>