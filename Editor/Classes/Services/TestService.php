<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Libraries/simpletest/unit_tester.php');
require_once($basePath.'Editor/Libraries/simpletest/reporter.php');
require_once($basePath.'Editor/Classes/Services/FileSystemService.php');
require_once($basePath.'Editor/Classes/Tests/AbstractObjectTest.php');

class TestService {
	
	function getGroups() {
		global $basePath;
		$out = array();
		$groups = FileSystemService::listDirs($basePath.'Editor/Tests/');
		for ($i=0; $i < count($groups); $i++) { 
			if ($groups[$i]!='Resources') {
				$out[] = $groups[$i];
			}
		}
		return $out;//$groups;
	}

	function getTestsInGroup($group) {
		global $basePath;
		return FileSystemService::listFiles($basePath.'Editor/Tests/'.$group.'/');
	}
	
	function runTest($test) {
		global $basePath;
		$path = $basePath.'Editor/Tests/'.$test.'.php';
		$test = new GroupTest($test);
		$test->addTestFile($path);
		$test->run(new HtmlReporter());
	}
	
	function runTestsInGroup($group) {
		global $basePath;
		$paths = array();
		
		$tests = TestService::getTestsInGroup($group);
		foreach ($tests as $test) {
			$paths[] = $basePath.'Editor/Tests/'.$group.'/'.$test;
		}
		
		$test = new GroupTest($group);
		foreach ($paths as $path) {
			$test->addTestFile($path);
		}
		$test->run(new HtmlReporter());
	}
	
	function runAllTests() {
		global $basePath;
		$paths = array();
		$groups = TestService::getGroups();
		
		foreach ($groups as $group) {
			$tests = TestService::getTestsInGroup($group);
			foreach ($tests as $test) {
				$paths[] = $basePath.'Editor/Tests/'.$group.'/'.$test;
			}
		}
		require_once($basePath.'Editor/Libraries/simpletest/unit_tester.php');
		require_once($basePath.'Editor/Libraries/simpletest/reporter.php');
		$test = new GroupTest('All tests');
		foreach ($paths as $path) {
			$test->addTestFile($path);
		}
		$test->run(new HtmlReporter());
	}
	
	function createTestPage() {
		$template = TemplateService::getTemplateByUnique('document');
		if (!$template) {
			TemplateService::install('document');
			$template = TemplateService::getTemplateByUnique('document');
		}
		
		$hierarchy = new Hierarchy();
		$hierarchy->save();
		
		$frame = new Frame();
		$frame->setHierarchyId($hierarchy->getId());
		$frame->save();

		$design = new Design();
		$design->setUnique('custom');
		$design->save();

		$page = new Page();
		
		$page->setTemplateId($template->getId());
		$page->setDesignId($design->getId());
		$page->setFrameId($frame->getId());
		
		$page->setTitle('Test page');
		$page->create();
		
		return $page;
	}
	
	function removeTestPage($page) {
		$frame = Frame::load($page->getFrameId());
		$hierarchy = Hierarchy::load($frame->getHierarchyId());
		$design = Design::load($page->getDesignId());
		
		$hierarchy->remove();
		$page->remove();
		$frame->remove();
		$design->remove();
	}

}