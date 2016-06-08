<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class WorkflowDescription {
  private $stages = [];

  public function add(WorkflowStage $stage) {
    $this->stages[] = $stage;
  }

  public function getStages() {
    return $this->stages;
  }

  public function run() {
    $state = new WorkflowState();
    $state->setStringData('http://daringfireball.net/feeds/main');
    for ($i=0; $i < count($this->stages); $i++) {
      $stage = $this->stages[$i];
      $state->log('Running: ' . get_class($stage));
      $state->clean();
      $stage->run($state);
      if (!$state->isSuccess()) {
        $state->log('Stage failed: ' . get_class($stage));
        break;
      }
    }
    return $state->getData();
  }
}
?>