<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
require_once 'Include/Public.php';

error_reporting(E_ALL);
ini_set("log_errors" , "1");
ini_set("error_log" , $basePath."local/logs/test.log");
ini_set("display_errors" , "0");

Console::exitIfNotConsole();

$args = Console::getArguments();

if (method_exists('Commander',$args[1])) {
    Commander::$args[1]($args);
} else {
    $methods = get_class_methods('Commander');
    echo "Tell me what to do: ".join(', ',$methods);
    echo "\n: ";
    $handle = fopen ("php://stdin","r");
    $cmd = trim(fgets($handle));
    if (method_exists('Commander',$cmd)) {
        Commander::$cmd();
    } else {
        echo "No action: ".$cmd;
    }
}


class Commander {
	
	static function test($args) {
        if (!Database::testConnection()) {
            echo "No database - no testing!\n";
            exit;
        }

        if (isset($args[2])) {
            if (strpos($args[2],'/')!==false) {
                TestService::runTest($args[2],new ConsoleReporter());
            } else {
                TestService::runTestsInGroup($args[2],new ConsoleReporter());                
            }
        } else {
            TestService::runAllTests(new ConsoleReporter());
        }
        
    }

	static function hui() {
        echo In2iGui::compile();        
    }

	static function style() {
        DesignService::rebuild();  
    }

	static function classpath() {
        $success = ClassService::rebuildClassPaths();
        echo $success ? 'Classpath successfully rebuild' : 'ERROR: Classpath could not be rebuild';
        echo PHP_EOL;  
    }

	static function full() {
        Commander::classpath();
        Commander::hui();
        Commander::style();
    }
}
?>