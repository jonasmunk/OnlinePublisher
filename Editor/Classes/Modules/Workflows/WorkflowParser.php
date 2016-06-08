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
    $flow = new WorkflowDescription();
    $children = DOMUtils::getChildElements($stages);
    foreach ($children as $child) {
      $options = [];
      if ($child->hasAttributes()) {
        foreach ($child->attributes as $attr) {
          $options[$attr->nodeName] = $attr->nodeValue;
        }
      }
      $stageClass = ucfirst($child->nodeName) . 'Stage';
      if (!class_exists($stageClass)) {
        return null;
      }
      $stage = new $stageClass($options);
      $flow->add($stage);
    }
    return $flow;
  }

}
?>