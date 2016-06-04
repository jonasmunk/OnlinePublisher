<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Network
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestStreams extends UnitTestCase {

  function testIt() {
    $flow = new WorkflowDescription();
    $flow->add(new DataStage([
      'type' => WorkflowState::$URL,
      'data' => 'http://daringfireball.net/feeds/main']
    ));
    $flow->add(new FetchStage(['maxAge'=>60]));
    $flow->add(new ParseFeedStage());
    $result = $flow->run();
    $this->assertNotNull($result);
    Log::debug($result->getTitle());
	}

  function testWorkflowIntoStream() {
    $stream = new Stream();
    $stream->setTitle('Test stream');
    $stream->save();

    $flow = new WorkflowDescription();
    $flow->add(new DataStage([
      'type' => WorkflowState::$URL,
      'data' => 'http://janemunk.tumblr.com/rss']
    ));
    $flow->add(new FetchStage(['maxAge'=>60]));
    $flow->add(new ParseFeedStage());
    $flow->add(new PopulateStreamStage(['id'=>$stream->getId(),'itemPath'=>'items']));
    $result = $flow->run();
    $this->assertNotNull($result);
    Log::debug($result->getTitle());

    $stream->remove();
	}

  function testParseWorkflow() {
    $xml = '
      <workflow>
        <input>
        </input>
        <stages>
          <data><string>{url}</string></data>
          <fetch maxAge="60"/>
          <parseFeed/>
          <populateStream id="{streamId}"/>
        </stages>
      </workflow>
    ';
  }

}

class WorkflowDescription {
  private $stages = [];

  public function add(WorkflowStage $stage) {
    $this->stages[] = $stage;
  }

  public function run() {
    $state = new WorkflowState();
    $state->setStringData('http://daringfireball.net/feeds/main');
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

class WorkflowState {
  static $STRING = 'string';
  static $FILE = 'file';
  static $OBJECT = 'object';
  static $URL = 'url';

  var $status = 'undefined';
  var $type;
  var $data;

  function clean() {
    $this->status = 'undefined';
  }

  public function log($value='') {
    Log::debug($value);
  }

  public function setStringData($data) {
    $this->setData($data,WorkflowState::$STRING);
  }

  public function setFileData($data) {
    $this->setData($data,WorkflowState::$FILE);
  }

  public function setObjectData($data) {
    $this->setData($data,WorkflowState::$OBJECT);
  }

  public function setData($data, $type) {
    $this->type = $type;
    $this->data = $data;
  }

  public function getData() {
    return $this->data;
  }

  function fail() {
    $this->status = 'failed';
  }

  function isSuccess() {
    return $this->status != 'failed';
  }
}

abstract class WorkflowStage {
  abstract function run($state);
}

class FetchStage extends WorkflowStage {

  private $maxAge = 0;

  function FetchStage(array $options = []) {
    if (isset($options['maxAge'])) {
      $this->maxAge = max(0,intval($options['maxAge']));
    }
  }

  function run($state) {
    $data = $state->getData();
    $state->log('Fetching from ' . $data);
    $remoteData = RemoteDataService::getRemoteData($data, $this->maxAge);
    if ($remoteData->isHasData()) {
      $state->setFileData($remoteData->getFile());
    } else {
      $state->log('No data from ' . $data);
      $state->fail();
    }
  }
}

class DataStage extends WorkflowStage {

  private $type;
  private $data;

  function DataStage(array $options) {
    $this->type = $options['type'];
    $this->data = $options['data'];
  }

  function run($state) {
    $state->setData($this->data,$this->type);
  }
}

class ParseFeedStage extends WorkflowStage {

  function ParseFeedStage(array $options = null) {
  }

  function run($state) {
    $parser = new FeedParser();
    $file = $state->getData();
    $state->log('Parsing ' . $file);
    $feed = $parser->parseFile($file);
    if ($feed) {
      $state->setObjectData($feed);
    } else {
      $state->log('Unable to parse ' . $file);
      $state->fail();
    }
  }
}

class PopulateStreamStage extends WorkflowStage {

  private $streamId;
  private $itemPath;

  function PopulateStreamStage(array $options) {
    $this->streamId = $options['id'];
    $this->itemPath = $options['itemPath'];
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