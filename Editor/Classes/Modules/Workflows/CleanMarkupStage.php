<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class CleanMarkupStage extends WorkflowStage {

  function CleanMarkupStage(array $options = []) {
  }

  function run($state) {
    $data = $state->getData();
    if (is_string($data)) {
      $data = MarkupUtils::htmlToXhtml($data);
      $data = trim($data);
    } else {
      $data = '';
      Log::debug('The data was not a string');
    }
    $state->setStringData($data);
  }

  function getDescription() {
    return 'Cleans markup';
  }
}
?>