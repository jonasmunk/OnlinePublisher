<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
include "../../Libraries/charts/charts.php";

$chart[ 'axis_category' ] = array ( 'size'=>14, 'color'=>"000000", 'alpha'=>40, 'font'=>"arial", 'bold'=>true, 'skip'=>0 ,'orientation'=>"horizontal" ); 
$chart[ 'axis_ticks' ] = array ( 'value_ticks'=>true, 'category_ticks'=>true, 'major_thickness'=>2, 'minor_thickness'=>1, 'minor_count'=>1, 'major_color'=>"aaaaaa", 'minor_color'=>"aaaaaa" ,'position'=>"outside" );
$chart[ 'axis_value' ] = array (  'min'=>0, 'max'=>80, 'font'=>"arial", 'bold'=>true, 'size'=>10, 'color'=>"aaaaaa", 'alpha'=>100, 'steps'=>6, 'prefix'=>"", 'suffix'=>"", 'decimals'=>0, 'separator'=>"", 'show_min'=>true );

$chart[ 'chart_border' ] = array ( 'color'=>"999999", 'top_thickness'=>1, 'bottom_thickness'=>1, 'left_thickness'=>1, 'right_thickness'=>1 );
$chart[ 'chart_grid_h' ] = array ( 'alpha'=>5, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );
$chart[ 'chart_grid_v' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );
$chart[ 'chart_pref' ] = array ( 'line_thickness'=>4, 'point_shape'=>"none", 'fill_shape'=>false );
$chart[ 'chart_rect' ] = array ( 'x'=>30, 'y'=>15, 'width'=>545, 'height'=>350, 'positive_color'=>"eeeeee", 'positive_alpha'=>50, 'negative_color'=>"ff0000",  'negative_alpha'=>100 );
$chart[ 'chart_type' ] = "Line";
$chart[ 'chart_value' ] = array ( 'prefix'=>"", 'suffix'=>"", 'decimals'=>0, 'separator'=>"", 'position'=>"cursor", 'hide_zero'=>true, 'as_percentage'=>false, 'font'=>"arial", 'bold'=>true, 'size'=>12, 'color'=>"000000", 'alpha'=>75 );
$chart[ 'chart_data' ] = array (
	array ( "","jan","feb","mar","apr","maj","jun","jul","aug","sep","okt","nov","dec" ),
	array ( "Region A",0,12,11,15,20,22,21,25,31,32,28,29 ),
	array ( "Region B",30,32,35,40,42,35,36,31,35,36,40,42 ),
	array ( "Region B",10,50,40,0,33,34,30,35,35,40,42,35)
	);
/*
$chart[ 'draw' ] = array ( array ( 'type'=>"text", 'color'=>"ffffff", 'alpha'=>15, 'font'=>"arial", 'rotation'=>-90, 'bold'=>true, 'size'=>30, 'x'=>5, 'y'=>348, 'width'=>300, 'height'=>150, 'text'=>"HITS", 'h_align'=>"center", 'v_align'=>"top" ),
                           array ( 'type'=>"text", 'color'=>"000000", 'alpha'=>15, 'font'=>"arial", 'rotation'=>0, 'bold'=>true, 'size'=>60, 'x'=>0, 'y'=>0, 'width'=>320, 'height'=>300, 'text'=>"output", 'h_align'=>"left", 'v_align'=>"bottom" ) );
*/
$chart[ 'legend_rect' ] = array ( 'x'=>-100, 'y'=>-100, 'width'=>10, 'height'=>10, 'margin'=>10 ); 

$chart[ 'series_color' ] = array ( "0000ff", "00ff00", "ff0000" ); 
$chart [ 'chart_transition' ] = array ( 'type'      =>  "slide_right",
                                        'delay'     =>  0, 
                                        'duration'  =>  1, 
                                        'order'     =>  "series"                                 
                                      ); 
SendChartData ( $chart );
?>