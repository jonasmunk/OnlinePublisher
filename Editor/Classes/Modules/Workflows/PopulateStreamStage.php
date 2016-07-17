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
    $now = time();
    $obj = $state->getData();
    $items = $obj->getItems(); // TODO
    foreach ($items as $item) {
      $data = Strings::toJSON($item);
      $hash = md5($data);
      $existing = Query::after('streamitem')
        ->withProperty(Streamitem::$HASH,$hash)
          ->withProperty(Streamitem::$STREAM_ID,$stream->getId())->first();
      if ($existing) {
        Log::debug('Skipping stream item: ' . $hash);
        continue;
      }
      $streamItem = new Streamitem();
      $streamItem->setStreamId($stream->getId());
      $streamItem->setData($data);
      $streamItem->setOriginalDate($item->getPubDate());
      $streamItem->setRetrievalDate($now);
      $streamItem->setHash($hash);
      $streamItem->save();
      $streamItem->publish();
    }
    $state->setObjectData($stream);
  }

  function getDescription() {
    return 'Loops through an object using the path "' . $this->itemPath . '" and populates the stream with the ID "' . $this->streamId . '"';
  }
}
?>