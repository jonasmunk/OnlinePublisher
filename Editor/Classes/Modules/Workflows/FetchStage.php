<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
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

  function getDescription() {
    return 'Fetches data from an URL with a max age of ' . $this->maxAge;
  }
}
?>