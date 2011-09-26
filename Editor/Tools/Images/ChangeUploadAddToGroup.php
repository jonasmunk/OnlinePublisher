<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Core/InternalSession.php';

InternalSession::setToolSessionVar('images','uploadAddToGroup',Request::getBoolean('uploadAddToGroup'));
?>