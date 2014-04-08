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

echo "base : " . $basePath . "\n";

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

	static function schema() {
        global $basePath;
        $schema = SchemaService::getDatabaseSchema();
                
        $schema = var_export(SchemaService::getDatabaseSchema(),true);

        $file = $basePath."Editor/Info/Schema.php";
        
        $data = "<?php
/**
 * @package OnlinePublisher
 * @subpackage Info
 */

if (!isset(\$GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
\$HUMANISE_EDITOR_SCHEMA = " . $schema . "
?>";
        FileSystemService::writeStringToFile($data,$file);
        echo $schema . PHP_EOL;
    }

	static function classes() {
        $success = ClassService::rebuildClasses();
        echo $success ? 'Classes successfully rebuild' : 'ERROR: Classes could not be rebuild';
        echo PHP_EOL;  
    }

	static function check() {
        if (DatabaseUtil::isCorrect()) {
            echo "The database schema is correct" . PHP_EOL;
        } else {
            echo "The database schema is NOT correct" . PHP_EOL;
        }
        echo join(PHP_EOL,DatabaseUtil::buildUpdateSQL()) . PHP_EOL;
    }

	static function full() {
        Commander::classes();
        Commander::hui();
        Commander::style();
    }
}
?>