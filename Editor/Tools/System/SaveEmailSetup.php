<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/EmailUtil.php';

$data = Request::getObject('data');

EmailUtil::setServer($data->server);
EmailUtil::setPort($data->port);
EmailUtil::setUsername($data->username);
EmailUtil::setPassword($data->password);
EmailUtil::setStandardEmail($data->standardEmail);
EmailUtil::setStandardName($data->standardName);
?>