<?
// Pull in the NuSOAP code
require_once '../../Editor/Include/Public.php';
require_once('../../Editor/Libraries/nusoap/nusoap.php');
require_once('../../Editor/Classes/Core/SystemInfo.php');
// Create the server instance
$server = new soap_server;
// Register the method to expose
// Note: with NuSOAP 0.6.3, only method name is used w/o WSDL
$server->register(
    'hello',                             // method name
    array('name' => 'xsd:string'),       // input parameters
    array('return' => 'xsd:string'),     // output parameters
    'uri:helloworld',                    // namespace
    'uri:helloworld/hello',              // SOAPAction
    'rpc',                               // style
    'encoded'                            // use
);
$server->register(
    'getSystemVersion',                             // method name
    array(),       // input parameters
    array('version' => 'xsd:string'),     // output parameters
    'http://uri.in2isoft.com/onlinepublisher/services/',                        // namespace
    'http://uri.in2isoft.com/onlinepublisher/services/getSystemVersion',      // SOAPAction
    'rpc',                               // style
    'encoded'                            // use
);
// Define the method as a PHP function
function hello($name) {
    return new soapval('return', 'xsd:string', 'Hello, ' . $name);
}


function getSystemVersion($name) {
    return new soapval('version', 'xsd:string', SystemInfo::getDate());
}

// Use the request to (try to) invoke the service
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>