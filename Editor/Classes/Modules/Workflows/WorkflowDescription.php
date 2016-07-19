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

  public function getDescription() {
    $description = [];
    foreach ($this->stages as $stage) {
      $description[] = $stage->getDescription();
    }
    return $description;
  }

  public function run(WorkflowState $state = null) {
    if (!$state) {
      $state = new WorkflowState();
    }
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