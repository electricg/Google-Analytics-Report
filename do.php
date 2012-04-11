<?php
require 'config.php';
require 'gapi.class.php';
require 'import_data.php';
require 'query_data.php';
require 'excel.php';



// Import statistics
if ($_POST['import']) {
	$func = $_POST['import'];
	$year = (int) $_POST['year'];
	$month = (int) $_POST['month'];
	switch ($func) {
		case "access_time":
			echo import_access_time($year, $month);
			break;
		
		case "browser_os":
			echo import_browser_os($year, $month);
			break;
		
		case "countries":
			echo import_countries($year, $month);
			break;
		
		case "help_page":
			echo import_help_page($year, $month);
			break;
		
		case "screen_res":
			echo import_screen_res($year, $month);
			break;
	}
}



// Create reports
if ($_POST['create']) {
	$report = $_POST['report'];
	$year = (int) $_POST['year'];
	$month = (int) $_POST['month'];
	
	switch($report) {
		
		case 'browser':
			$arr = query_browser($year, $month);
			$what = 'browser';
			$col_header = array('Browser', 'Version', 'Visitors', '%');
			$col_format = array('text', 'text', 'int1000', 'float');
			$col_total_visitor = 3;
			create_report($year, $month, $what, $col_header, $col_format, $arr['result_report_query'], $arr['total_visitor'], $col_total_visitor);
			break;
		
		
		case 'os':
			$arr = query_os($year, $month);
			$what = 'os';
			$col_header = array('OS', 'OS Version', 'Visitors', '%');
			$col_format = array('text', 'text', 'int1000', 'float');
			$col_total_visitor = 3;
			create_report($year, $month, $what, $col_header, $col_format, $arr['result_report_query'], $arr['total_visitor'], $col_total_visitor);
			break;
		
		
		case 'access_time':
			$arr = query_access_time($year, $month);
			$what = 'access time';
			$col_header = array('Day of Week', 'Hour', 'Average Visitors', 'Average Visits', 'Average Time');
			$col_format = array('int', 'int', 'int1000', 'int1000', 'time');
			$col_total_visitor = 0;
			create_report($year, $month, $what, $col_header, $col_format, $arr['result_report_query'], $arr['total_visitor'], $col_total_visitor);
			break;
		
		
		case 'countries':
			$arr = query_countries($year, $month);
			$what = 'country';
			$col_header = array('Country', 'Visitors', '%');
			$col_format = array('text', 'int1000', 'float');
			$col_total_visitor = 2;
			create_report($year, $month, $what, $col_header, $col_format, $arr['result_report_query'], $arr['total_visitor'], $col_total_visitor);
			break;
		
		
		case 'help_page':
			$arr = query_help_page($year, $month);
			$what = 'help page';
			$col_header = array('Page', 'Visitors', 'Page Views', 'Average Time On Page');
			$col_format = array('text', 'int1000', 'int1000', 'time');
			$col_total_visitor = 2;
			create_report($year, $month, $what, $col_header, $col_format, $arr['result_report_query'], $arr['total_visitor'], $col_total_visitor);
			break;
		
		
		case 'screen_res':
			$arr = query_screen_res($year, $month);
			$what = 'screen res';
			$col_header = array('Width', 'Height', 'Visitors', '%');
			$col_format = array('int', 'int', 'int1000', 'float');
			$col_total_visitor = 3;
			create_report($year, $month, $what, $col_header, $col_format, $arr['result_report_query'], $arr['total_visitor'], $col_total_visitor);
			break;
	
	}
}
?>
