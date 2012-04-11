<?php
// library for importing data from Google Analytics into DB


/**
 * Import browser and os statistics
 * @param int $year year of the required data
 * @param int $month month of the required data
 * @return 1 if successful or the mysql error
 */
function import_browser_os($year, $month) {

	$ga = new gapi(ga_email,ga_password);
	
	$dimensions = array('year', 'month', 'browser', 'browserVersion', 'operatingSystem', 'operatingSystemVersion', 'isMobile');
	$metrics = array('visitors');
	$sort_metric = null;
	$filter = null;
	$start_date = date('Y-m-d', mktime(0, 0, 0, $month,   1, $year));
	$end_date =   date('Y-m-d', mktime(0, 0, 0, $month+1, 0, $year));
	$start_index = 1;
	$max_results = 10000;
	$segment = ga_no_external;
	
	$ga->requestReportData(ga_profile_id, $dimensions, $metrics, $sort_metric, $filter, $start_date, $end_date, $start_index, $max_results, $segment);
	
	foreach($ga->getResults() as $result):
		$query  = "INSERT INTO browsers SET ";
		$query .= " year = ".$result->getYear();
		$query .= ",month = ".$result->getMonth();
		$query .= ",browser = '".$result->getBrowser()."'";
		$query .= ",browserVersion = '".$result->getBrowserVersion()."'";
		$query .= ",operatingSystem = '".$result->getOperatingSystem()."'";
		$query .= ",operatingSystemVersion = '".$result->getOperatingSystemVersion()."'";
		$query .= ",isMobile = ".($result->getIsMobile() == 'Yes' ? 1 : 0);
		$query .= ",visitors = ".$result->getVisitors();
		$query .= " ON DUPLICATE KEY UPDATE";
		$query .= " visitors = ".$result->getVisitors();
		$query .= ";";
		
		$result_query = mysql_query($query);
		if (!$result_query) {
			die('Invalid query: ' . mysql_error());
		}
	endforeach;
	return 1;
}


/**
 * Import screen resolution statistics
 * @param int $year year of the required data
 * @param int $month month of the required data
 * @return 1 if successful or the mysql error
 */
function import_screen_res($year, $month) {
	
	$ga = new gapi(ga_email,ga_password);
	
	$dimensions = array('year', 'month', 'screenResolution');
	$metrics = array('visitors');
	$sort_metric = null;
	$filter = null;
	$start_date = date('Y-m-d', mktime(0, 0, 0, $month,   1, $year));
	$end_date =   date('Y-m-d', mktime(0, 0, 0, $month+1, 0, $year));
	$start_index = 1;
	$max_results = 10000;
	$segment = ga_no_external;
	
	$ga->requestReportData(ga_profile_id, $dimensions, $metrics, $sort_metric, $filter, $start_date, $end_date, $start_index, $max_results, $segment);
	
	foreach($ga->getResults() as $result):
		$arr = array();
		$arr = explode('x', $result->getScreenResolution());
		$query  = "INSERT INTO screens SET ";
		$query .= " year = ".$result->getYear();
		$query .= ",month = ".$result->getMonth();
		$query .= ",width = ".$arr[0];
		$query .= ",height = ".$arr[1];
		$query .= ",visitors = ".$result->getVisitors();
		$query .= " ON DUPLICATE KEY UPDATE";
		$query .= " visitors = ".$result->getVisitors();
		$query .= ";";
		
		$result_query = mysql_query($query);
		if (!$result_query) {
			die('Invalid query: ' . mysql_error());
		}
	endforeach;
	return 1;
}


/**
 * Import countries statistics
 * @param int $year year of the required data
 * @param int $month month of the required data
 * @return 1 if successful or the mysql error
 */
function import_countries($year, $month) {
	
	$ga = new gapi(ga_email,ga_password);
	
	$dimensions = array('year', 'month', 'country');
	$metrics = array('visitors');
	$sort_metric = null;
	$filter = null;
	$start_date = date('Y-m-d', mktime(0, 0, 0, $month,   1, $year));
	$end_date =   date('Y-m-d', mktime(0, 0, 0, $month+1, 0, $year));
	$start_index = 1;
	$max_results = 10000;
	$segment = ga_no_external;
	
	$ga->requestReportData(ga_profile_id, $dimensions, $metrics, $sort_metric, $filter, $start_date, $end_date, $start_index, $max_results, $segment);
	
	foreach($ga->getResults() as $result):
		$query  = "INSERT INTO countries SET ";
		$query .= " year = ".$result->getYear();
		$query .= ",month = ".$result->getMonth();
		$query .= ",country = '".$result->getCountry()."'";
		$query .= ",visitors = ".$result->getVisitors();
		$query .= " ON DUPLICATE KEY UPDATE";
		$query .= " visitors = ".$result->getVisitors();
		$query .= ";";
		
		$result_query = mysql_query($query);
		if (!$result_query) {
			die('Invalid query: ' . mysql_error());
		}
	endforeach;
	return 1;
}


/**
 * Import access time statistics
 * @param int $year year of the required data
 * @param int $month month of the required data
 * @return 1 if successful or the mysql error
 */
function import_access_time($year, $month) {

	$ga = new gapi(ga_email,ga_password);
	
	$dimensions = array('date','dayOfWeek','hour');
	$metrics = array('visitors','visits','avgTimeOnSite');
	$sort_metric = array('date', 'hour');
	$filter = null;
	$start_date = date('Y-m-d', mktime(0, 0, 0, $month,   1, $year));
	$end_date =   date('Y-m-d', mktime(0, 0, 0, $month+1, 0, $year));
	$start_index = 1;
	$max_results = 10000;
	$segment = null;
	
	$ga->requestReportData(ga_profile_id, $dimensions, $metrics, $sort_metric, $filter, $start_date, $end_date, $start_index, $max_results, $segment);
	
	foreach($ga->getResults() as $result):
		$date = $result->getDate();
		$new_date = substr($date, 0, 4)."-".substr($date, 4, 2)."-".substr($date, 6, 2);
		$query  = "INSERT INTO accesstimes SET ";
		$query .= " date = '".$new_date."'";
		$query .= ",dayOfWeek = ".$result->getDayOfWeek();
		$query .= ",hour = ".$result->getHour();
		$query .= ",visitors = ".$result->getVisitors();
		$query .= ",visits = ".$result->getVisits();
		$query .= ",avgTimeOnSite = ".$result->getAvgTimeOnSite();
		$query .= " ON DUPLICATE KEY UPDATE";
		$query .= " visitors = ".$result->getVisitors();
		$query .= ",visits = ".$result->getVisits();
		$query .= ",avgTimeOnSite = ".$result->getAvgTimeOnSite();
		$query .= ";";
		
		$result_query = mysql_query($query);
		if (!$result_query) {
			die('Invalid query: ' . mysql_error());
		}
	endforeach;
	
	
	// no external visits
	$segment = ga_no_external;
	
	$ga->requestReportData(ga_profile_id, $dimensions, $metrics, $sort_metric, $filter, $start_date, $end_date, $start_index, $max_results, $segment);
	
	foreach($ga->getResults() as $result):
		$date = $result->getDate();
		$new_date = substr($date, 0, 4)."-".substr($date, 4, 2)."-".substr($date, 6, 2);
		$query  = "INSERT INTO accesstimes SET ";
		$query .= " date = '".$new_date."'";
		$query .= ",dayOfWeek = ".$result->getDayOfWeek();
		$query .= ",hour = ".$result->getHour();
		$query .= ",noext_visitors = ".$result->getVisitors();
		$query .= ",noext_visits = ".$result->getVisits();
		$query .= ",noext_avgTimeOnSite = ".$result->getAvgTimeOnSite();
		$query .= " ON DUPLICATE KEY UPDATE";
		$query .= " noext_visitors = ".$result->getVisitors();
		$query .= ",noext_visits = ".$result->getVisits();
		$query .= ",noext_avgTimeOnSite = ".$result->getAvgTimeOnSite();
		$query .= ";";
		
		$result_query = mysql_query($query);
		if (!$result_query) {
			die('Invalid query: ' . mysql_error());
		}
	endforeach;
	return 1;
}


/**
 * Import help pages statistics
 * @param int $year year of the required data
 * @param int $month month of the required data
 * @return 1 if successful or the mysql error
 */
function import_help_page($year, $month) {

	$ga = new gapi(ga_email,ga_password);
	
	$dimensions = array('year', 'month', 'pagePath');
	$metrics = array('visitors', 'pageviews', 'uniquePageviews', 'avgTimeOnPage');
	$sort_metric = null;
	$filter = 'ga:pagePath=@/_act/get_page.php?pgName=/help/';
	$start_date = date('Y-m-d', mktime(0, 0, 0, $month,   1, $year));
	$end_date =   date('Y-m-d', mktime(0, 0, 0, $month+1, 0, $year));
	$start_index = 1;
	$max_results = 10000;
	$segment = ga_no_external;
	
	$ga->requestReportData(ga_profile_id, $dimensions, $metrics, $sort_metric, $filter, $start_date, $end_date, $start_index, $max_results, $segment);
	
	foreach($ga->getResults() as $result):
		$query  = "INSERT INTO helppages SET ";
		$query .= " year = ".$result->getYear();
		$query .= ",month = ".$result->getMonth();
		$query .= ",pagePath = '".$result->getPagePath()."'";
		$query .= ",visitors = ".$result->getVisitors();
		$query .= ",pageviews = ".$result->getPageviews();
		$query .= ",uniquePageviews = ".$result->getUniquePageviews();
		$query .= ",avgTimeOnPage = ".$result->getAvgTimeOnPage();
		$query .= " ON DUPLICATE KEY UPDATE";
		$query .= " visitors = ".$result->getVisitors();
		$query .= ",pageviews = ".$result->getPageviews();
		$query .= ",uniquePageviews = ".$result->getUniquePageviews();
		$query .= ",avgTimeOnPage = ".$result->getAvgTimeOnPage();
		$query .= ";";
		
		$result_query = mysql_query($query);
		if (!$result_query) {
			die('Invalid query: ' . mysql_error());
		}
	endforeach;
	return 1;
}
?>