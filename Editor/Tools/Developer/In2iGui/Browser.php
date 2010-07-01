<?php
/**
 * @package OnlinePublisher
 * @subpackage Developer
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/In2iGui.php';


$gui='<gui xmlns="uri:In2iGui" path="../../../../">
<split width="300">
<sidebar>
<browser source="Browser.php"/>
</sidebar>
<content>
<browser source="Browser.php"/>
</content>
</split>
</gui>';

In2iGui::render($gui);
?>