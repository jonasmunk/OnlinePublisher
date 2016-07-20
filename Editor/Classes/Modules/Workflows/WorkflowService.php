<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class WorkflowService {

  public static function runWorkflow(Workflow $flow) {
    $state = new WorkflowState();
    $parser = new WorkflowParser();
    $flow = $parser->parse($flow->getRecipe());
    if ($flow) {
      $flow->run($state);
    }
    return $state;
  }

  public static function evaluate($obj,$expression) {
    $path = explode('.', $expression);
    foreach ($path as $prop) {
      if (is_array($obj) && isset($obj[$prop])) {
        $obj = $obj[$prop];
      } else if (is_object($obj)) {
        $getter = 'get' . ucfirst($prop);
        if (isset($obj->$prop)) {
          $obj = $obj->$prop;
        }
        else if (method_exists($obj,$getter)) {
          $obj = $obj->$getter();
        } else {
          return null;
        }
      } else {
        return null;
      }
    }
    return $obj;
  }

}
?>