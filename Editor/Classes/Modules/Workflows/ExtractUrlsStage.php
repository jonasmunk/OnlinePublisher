<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ExtractUrlsStage extends WorkflowStage {

  private $extensions;

  function ExtractUrlsStage(array $options = []) {
    $this->extensions = isset($options['extensions']) ? split(',', $options['extensions']) : null;
  }

  function run($state) {
    $text = $state->getData();
    $found = [];
		$pattern = "/http[s]?:\/\/[a-z0-9\-\.]+[a-z0-9.\?&\/\#=_\-\%,~\+)\(;]*/umi";
		preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
		foreach ($matches as $match) {
      $url = trim($match[0]);
      if ($this->extensions) {
        $ext = FileSystemService::getFileExtension($url);
        Log::debug($ext);
        Log::debug($this->extensions);
        if (!in_array($ext,$this->extensions)) {
          continue;
        }
      }
			$found[] = $url;
		}
    $state->setObjectData($found);
  }

  function getDescription() {
    return 'Finds URLs';
  }
}
?>