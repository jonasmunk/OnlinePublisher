<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Image.php';

$id = requestGetNumber('id',0);

$image = Image::load($id);

?>
<html>
<body>
<img src="../../../images/<?=$image->getFilename()?>" width="<?=$image->getWidth()?>" height="<?=$image->getHeight()?>"/>
</body>
</html>