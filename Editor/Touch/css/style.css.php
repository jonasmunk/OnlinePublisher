@import url('base.css');
@import url('login.css');

<?php
function baseit($path) {
	$handle = fopen($path, "r");
	$binary = fread($handle, filesize($path));
	fclose($handle);
	return 'data:image/jpg;base64,'.base64_encode($binary);
}
?>

