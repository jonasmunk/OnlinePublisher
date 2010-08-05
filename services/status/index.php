<?php
/**
 * @package OnlinePublisher
 * @subpackage Public.Services.Status
 */

require_once '../../Editor/Include/Public.php';
require_once '../../Editor/Classes/SystemInfo.php';
require_once '../../Editor/Libraries/xmlrpc/xmlrpc.php';¨

$xmlrpc_request = XMLRPC_parse($HTTP_RAW_POST_DATA);
$methodName = XMLRPC_getMethodName($xmlrpc_request);
$params = XMLRPC_getParams($xmlrpc_request);
$response = SystemInfo::getDate();
XMLRPC_response(XMLRPC_prepare($response), "OnlinePublisher");
?>