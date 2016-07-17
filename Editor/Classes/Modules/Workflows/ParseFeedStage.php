<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
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

  function getDescription() {
    return 'Parses data as an RSS/Atom feed into an object';
  }
}
?>