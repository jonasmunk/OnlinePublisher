<?php
 
  // session_start for caching
  session_start();
  
  require 'analytics.class.php';
  
  try {
      
      // construct the class
      $oAnalytics = new analytics('[username]', '[password]');
      
      // set it up to use caching
      $oAnalytics->useCache();
      
      $oAnalytics->setProfileByName('[Google analytics accountname]');
      // or $oAnalytics->setProfileById('ga:123456');
      
      // set the date range
      $oAnalytics->setMonth(date('n'), date('Y'));
      // or $oAnalytics->setDateRange('YYYY-MM-DD', 'YYYY-MM-DD');
      
      echo '<pre>';
      // print out visitors for given period
      print_r($oAnalytics->getVisitors());
      
      // print out pageviews for given period
      print_r($oAnalytics->getPageviews());
      
      // use dimensions and metrics for output
      // see: http://code.google.com/intl/nl/apis/analytics/docs/gdata/gdataReferenceDimensionsMetrics.html
      print_r($oAnalytics->getData(array(   'dimensions' => 'ga:keyword',
                                            'metrics'    => 'ga:visits',
                                            'sort'       => 'ga:keyword')));
      
  } catch (Exception $e) { 
      echo 'Caught exception: ' . $e->getMessage(); 
  }
?>