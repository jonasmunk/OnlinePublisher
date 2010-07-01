<?php
/**
 * @package OnlinePublisher
 * @subpackage Public.Services.Images
 */

require_once '../../../Config/Setup.php';
require_once '../../../Editor/Include/Functions.php';

header('Content-type: text/xml');
echo '<?xml version="1.0"?>'.
'<images>';

$sql="select * from image order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	echo '<image title="'.$row['title'].'" filename="'.$row['filename'].'" width="'.$row['width'].'" height="'.$row['height'].'"/>';
}
Database::free($result);

echo "</images>";
?>