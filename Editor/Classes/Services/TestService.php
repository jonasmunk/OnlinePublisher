<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
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

}