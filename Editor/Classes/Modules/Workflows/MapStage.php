<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Workflows
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class MapStage extends WorkflowStage {

  private $path;
  private $properties = [];

  function MapStage(array $options = []) {
    $this->path = isset($options['path']) ? $options['path'] : null;
  }

  function parse($node) {
    $properties = DOMUtils::getChildElements($node,'property');
    foreach ($properties as $property) {
      $p = [
        'name' => $property->getAttribute('name'),
        'input' => $property->getAttribute('input')
      ];
      $parser = new WorkflowParser();
      $p['workflow'] = $parser->parseNode($property);
      $this->properties[] = $p;
    }
  }

  function run($state) {
    $data = $state->getData();
    $items = [];
    if ($this->path) {
      $p = $this->path;
      if (isset($data->$p)) {
        if (is_array($data->$p)) {
          $items = &$data->$p;
        } else {
          Log::debug('Not an array');
        }
      }
    } else if (is_array($data)) {
      $items = $data;
    }
    for ($i=0; $i < count($items); $i++) {
      $item = $items[$i];
      $this->apply($item);
    }
    $state->setObjectData($data);
  }

  function apply(&$item) {
    foreach ($this->properties as $property) {
      $input = $property['input'];
      $name = $property['name'];
      $flow = $property['workflow'];
      $val = null;
      if (is_array($item) && isset($item[$input])) {
        $val = $item[$input];
      }
      else if (is_object($item) && isset($item->$input)) {
        $val = $item->$input;
      }

      $state = new WorkflowState();
      $state->setStringData($val);

      $result = $flow->run($state);
      if (is_array($item)) {
        $item[$name] = $result;
      } else if (is_object($item)) {
        $item->$name = $result;
      }
    }
  }

  function getDescription() {
    return 'Maps something at path: ' . $this->path . ' to something else';
  }
}
?>