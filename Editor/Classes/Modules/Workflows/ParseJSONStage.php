<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ParseJSONStage extends WorkflowStage {

  function ParseJSONStage(array $options = null) {
  }

  function run($state) {
    if (!$state->getType() == WorkflowState::$STRING) {
      $state->log('Only strings are suppoted as input');
      $state->fail();
      return;
    }
    $obj = Strings::fromJSON($state->getData());
    $state->setObjectData($obj);
  }

  function getDescription() {
    return 'Parses a JSON string into an object';
  }
}
?>