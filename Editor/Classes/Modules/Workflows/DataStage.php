<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class DataStage extends WorkflowStage {

  private $type;
  private $data;

  function DataStage(array $options = []) {
    $this->type = isset($options['type']) ? $options['type'] : null;
    $this->data = isset($options['data']) ? $options['data'] : null;
  }

  function run($state) {
    $state->setData($this->data,$this->type);
  }

  function getDescription() {
    return 'Returns the value "' . $this->data . '" of type "' . $this->type . '"';
  }
}
?>