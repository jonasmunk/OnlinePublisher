<?
sleep(rand(0,6));
error_log('Number of files: '.count($_FILES));
error_log(json_encode($_FILES));

echo 'SUCCESS';
?>