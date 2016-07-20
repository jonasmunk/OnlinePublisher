<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ReadFileStage extends WorkflowStage {

  function ReadFileStage(array $options = null) {
  }

  function run($state) {
    if (!$state->getType() == WorkflowState::$FILE) {
      $state->log('Only files are suppoted as input');
      $state->fail();
      return;
    }
    $str = file_get_contents($state->getData());
    $state->setStringData($str);
  }

  function getDescription() {
    return 'Reads a file into a string';
  }
}
?>