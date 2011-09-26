<?php
/**
 * @package OnlinePublisher
 * @subpackage Public.Services.Status
 */

require_once '../../Editor/Include/Public.php';
require_once '../../Editor/Classes/Core/SystemInfo.php';
require_once '../../Editor/Libraries/xmlrpc/xmlrpc.php';

$request = XMLRPC_parse($HTTP_RAW_POST_DATA);
$methodName = XMLRPC_getMethodName($request);
$params = XMLRPC_getParams($request);
$response = SystemInfo::getDate();
XMLRPC_response(XMLRPC_prepare($response), "OnlinePublisher");
?>