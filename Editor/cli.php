<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
require_once 'Include/Public.php';

if (ini_get('display_errors')) {
    ini_set('display_errors', 0);
}
error_reporting(E_ERROR | E_PARSE);

Console::exitIfNotConsole();

$args = Console::getArguments();

if ($args[1]=='test') {
    Commander::test($args);
}
else if ($args[1]=='style') {
    Commander::style();    
}
else if ($args[1]=='hui') {
    Commander::hui();
}
else if ($args[1]=='classpath') {
    Commander::classpath();
}
else if ($args[1]=='full') {
    Commander::classpath();
    Commander::hui();
    Commander::style();
} else {
    echo "Tell me what to do: \nstyle \nhui \nclasspath \nfull";
    echo "\n: ";
    $handle = fopen ("php://stdin","r");
    $line = fgets($handle);
    echo "Your choice: $line\n";
}


class Commander {
	
	static function test($args) {
        if (!Database::testConnection()) {
            echo "No database - no testing!\n";
            exit;
        }

        if ($args[2]) {
            TestService::runTest($args[2],new ConsoleReporter());
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
}
?>