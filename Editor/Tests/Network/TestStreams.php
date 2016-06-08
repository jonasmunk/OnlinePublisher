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
    $stream = new Stream();
    $stream->setTitle('Test stream');
    $stream->save();
    $params = [
      'url' => 'http://janemunk.tumblr.com/rss',
      'streamId' => $stream->getId()
    ];
    $xml = '
      <workflow>
        <input>
          <text key="url"/>
          <text key="streamId"/>
        </input>
        <stages>
          <data type="url" data="{url}"/>
          <fetch maxAge="60"/>
          <parseFeed/>
          <populateStream id="{streamId}"/>
        </stages>
      </workflow>
    ';
    $parser = new WorkflowParser();
    $workflow = $parser->parse($xml,$params);
    $this->assertNotNull($workflow);
    $workflow->run();
    $stream->remove();
  }

}














?>