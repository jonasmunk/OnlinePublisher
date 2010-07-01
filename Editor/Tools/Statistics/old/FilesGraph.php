<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';
require_once 'Functions.php';
require_once "../../Libraries/charts/charts.php";


$limit = 30;
$labels = array("");
$hits = array("Hits");
$sessions = array("sessions");

$sql=buildFilesSql();
$result = Database::select($sql);	
while($row = Database::next($result)) {
	if ($limit>0) {
		$labels[] = encodeXML($row['title']);
		$hits[] = $row['hits'];
		$sessions[] = $row['sessions'];
	}
	$limit--;
}
Database::free($result);



$chart[ 'axis_category' ] = array ( 'size'=>9, 'color'=>"000000", 'alpha'=>40, 'font'=>"arial", 'bold'=>true, 'skip'=>0 ,'orientation'=>"diagonal_down" ); 
$chart[ 'axis_ticks' ] = array ( 'value_ticks'=>true, 'category_ticks'=>true, 'major_thickness'=>2, 'minor_thickness'=>1, 'minor_count'=>1, 'major_color'=>"aaaaaa", 'minor_color'=>"aaaaaa" ,'position'=>"outside" );
$chart[ 'axis_value' ] = array (  'min'=>0, 'font'=>"arial", 'bold'=>true, 'size'=>10, 'color'=>"aaaaaa", 'alpha'=>100, 'steps'=>6, 'prefix'=>"", 'suffix'=>"", 'decimals'=>0, 'separator'=>"", 'show_min'=>true );

$chart[ 'chart_border' ] = array ( 'color'=>"999999", 'top_thickness'=>1, 'bottom_thickness'=>1, 'left_thickness'=>1, 'right_thickness'=>1 );
$chart[ 'chart_grid_h' ] = array ( 'alpha'=>5, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );
$chart[ 'chart_grid_v' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );
$chart[ 'chart_pref' ] = array ( 'line_thickness'=>4, 'point_shape'=>"none", 'fill_shape'=>false );
$chart[ 'chart_rect' ] = array ( 'x'=>30, 'y'=>5, 'width'=>545, 'height'=>300, 'positive_color'=>"eeeeee", 'positive_alpha'=>50, 'negative_color'=>"ff0000",  'negative_alpha'=>100 );
$chart[ 'chart_type' ] = "Column";
$chart[ 'chart_value' ] = array ( 'prefix'=>"", 'suffix'=>"", 'decimals'=>0, 'separator'=>"", 'position'=>"cursor", 'hide_zero'=>true, 'as_percentage'=>false, 'font'=>"arial", 'bold'=>true, 'size'=>12, 'color'=>"000000", 'alpha'=>75 );
$chart[ 'chart_data' ] = array (
	$labels,$hits,$sessions
	);
$chart[ 'legend_rect' ] = array ( 'x'=>-100, 'y'=>-100, 'width'=>10, 'height'=>10, 'margin'=>10 ); 

$chart[ 'series_color' ] = array ( "3366ff", "66ff99", "ff0000" ); 

SendChartData ( $chart );
?>