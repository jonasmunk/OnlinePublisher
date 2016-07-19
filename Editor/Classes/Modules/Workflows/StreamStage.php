<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class StreamStage extends WorkflowStage {

  private $id = 0;

  function StreamStage(array $options = []) {
    if (isset($options['id'])) {
      $this->id = max(0,intval($options['id']));
    }
  }

  function run($state) {
    $items = Query::after('streamitem')->
      withProperty(Streamitem::$STREAM_ID,$this->id)->
      orderBy('originalDate')->descending()->get();
    $out = [];
    foreach ($items as $item) {
      $out[] = Strings::fromJSON($item->getData());
    }
    $state->setObjectData($out);
  }

  function getDescription() {
    return 'Loads items from a stream';
  }
}
?>