<?php
/**
 * @package OnlinePublisher
 * @subpackage Public.Services.Images
 */

require_once '../../../Editor/Include/Public.php';

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