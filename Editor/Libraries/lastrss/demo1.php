<?php
require_once "lastRSS.php"; 
// Create lastRSS object 
$rss = new lastRSS;
// Set cache dir and cache time limit (1200 seconds) 
// (don't forget to chmod cahce dir to 777 to allow writing) 
$rss->cache_dir = '';
$rss->cache_time = 0;
$rss->cp = 'US-ASCII';
$rss->date_format = 'm.d.y';

// Try to load and parse RSS file of Slashdot.org 
$rssurl = 'http://www.freshfolder.com/rss.php';

if ($rs = $rss->get($rssurl)) { 
	echo '<pre>'; 
	print_r($rs); 
	echo '</pre>'; 
	} 
else { 
	echo "Error: It's not possible to get $rssurl..."; 
}
?>