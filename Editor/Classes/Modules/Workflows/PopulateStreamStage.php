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
  private $timeProperty;
  private $identityProperty;
  private $clear;

  function PopulateStreamStage(array $options = []) {
    $this->streamId = isset($options['id']) ? $options['id'] : null;
    $this->itemPath = isset($options['itemPath']) ? $options['itemPath'] : null;
    $this->timeProperty = isset($options['timeProperty']) ? $options['timeProperty'] : null;
    $this->identityProperty = isset($options['identityProperty']) ? $options['identityProperty'] : null;
    $this->clear = isset($options['clear']) ? $options['clear'] == 'true' : false;
  }

  function run($state) {
    $stream = Stream::load($this->streamId);
    if (!$stream) {
      $state->log('Unable load stream: id=' . $this->streamId);
      $state->fail();
      return;
    }
    if ($this->clear) {
      $existing = Query::after('streamitem')
              ->withProperty(Streamitem::$STREAM_ID, $stream->getId())
              ->get();
      foreach ($existing as $item) {
        $item->remove();
      }
    }
    $now = time();
    $obj = $state->getData();
    $items = WorkflowService::evaluate($obj, $this->itemPath);
    foreach ($items as $item) {
      $data = Strings::toJSON($item);
      $identity = WorkflowService::evaluate($item, $this->identityProperty);
      if (!$identity) {
        $identity = $hash;
      }
      $hash = md5($data);
      $streamItem = Query::after('streamitem')
        ->withProperty(Streamitem::$IDENTITY, $identity)
        ->withProperty(Streamitem::$STREAM_ID, $stream->getId())
        ->first();
      if (!$streamItem) {
        $streamItem = new Streamitem();
      }
      $streamItem->setStreamId($stream->getId());
      $streamItem->setData($data);
      $time = intval(WorkflowService::evaluate($item, $this->timeProperty));
      if ($time > 0) {
        $streamItem->setOriginalDate($time);
      }
      $streamItem->setIdentity($identity);
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