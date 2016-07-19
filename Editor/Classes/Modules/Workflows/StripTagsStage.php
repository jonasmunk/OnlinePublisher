<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class StripTagsStage extends WorkflowStage {

  function StripTagsStage(array $options = []) {
  }

  function run($state) {
    $data = $state->getData();
    if (is_string($data)) {
      $data = Strings::convertMarkupToText($data);
      $data = trim($data);
    } else {
      Log::debug('The data was not a string');
    }
    $state->setStringData($data);
  }

  function getDescription() {
    return 'Converts a string with markup to text';
  }
}
?>