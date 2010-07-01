<?php
/**
 * @package OnlinePublisher
 * @subpackage Developer
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/In2iGui.php';


$gui='
<gui xmlns="uri:In2iGui" path="../../../../">
<dock source="Layout.php">

</dock>
</gui>
';

In2iGui::render($gui);
?>