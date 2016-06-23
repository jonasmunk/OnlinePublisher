<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class PopulateStreamStage extends WorkflowStage {

  private $streamId;
  private $itemPath;

  function PopulateStreamStage(array $options = []) {
    $this->streamId = isset($options['id']) ? $options['id'] : null;
    $this->itemPath = isset($options['itemPath']) ? $options['itemPath'] : null;
  }

  function run($state) {
    $stream = Stream::load($this->streamId);
    if (!$stream) {
      $state->log('Unable load stream: id=' . $this->streamId);
      $state->fail();
      return;
    }
    $obj = $state->getData();
    $items = $obj->getItems(); // TODO
    foreach ($items as $item) {
      $streamItem = new Streamitem();
      $streamItem->setStreamId($stream->getId());
      $streamItem->setData(Strings::toJSON($item));
      $streamItem->setOriginalDate($item->getPubDate());
      $streamItem->save();
    }
    $state->setObjectData($stream);
  }
}
?>