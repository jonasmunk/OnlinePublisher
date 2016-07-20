<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestWorkflowService extends UnitTestCase {

    function testEvaluate() {
      $data = ['first' => ['second' => ['third' => 'value']]];

      $this->assertEqual('value',WorkflowService::evaluate($data,'first.second.third'));

      $data = new stdClass();
      $data->first = ['second' => ['third' => 'value']];
      $this->assertEqual('value',WorkflowService::evaluate($data,'first.second.third'));
      $this->assertNull(WorkflowService::evaluate($data,'nowhere'));
    }
}
?>