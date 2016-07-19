<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class WorkflowParser {

  public function parse($xml,$parameters = []) {
    foreach ($parameters as $key => $value) {
      $xml = str_replace('{' . $key . '}', Strings::escapeXML($value), $xml);
    }
    $dom = DOMUtils::parse($xml);
    if (!$dom) {
      return null;
    }
    $stages = DOMUtils::getFirstChildElement($dom->documentElement,'stages');
    if (!$stages) {
      return null;
    }
    return $this->parseNode($stages, $parameters);
  }

  public function parseNode($stages,$parameters = []) {
    $flow = new WorkflowDescription();
    $children = DOMUtils::getChildElements($stages);
    foreach ($children as $child) {
      $options = [];
      $stageClass = ucfirst($child->nodeName) . 'Stage';
      if (!class_exists($stageClass)) {
        Log::debug('Stage not found: ' . $stageClass);
        return null;
      }
      if ($child->hasAttributes()) {
        foreach ($child->attributes as $attr) {
          $options[$attr->nodeName] = $attr->nodeValue;
        }
      }
      $stage = new $stageClass($options);
      if (method_exists($stage,'parse')) {
        $stage->parse($child);
      }
      $flow->add($stage);
    }
    return $flow;
  }
}
?>