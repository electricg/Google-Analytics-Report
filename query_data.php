<?php
// library for exporting data from DB


/**
 * Export browser statistics
 * @param int $year year of the required data
 * @param int $month month of the required data
 * @return array('result_report_query','total_visitor')
 */
function query_browser($year, $month) {

	$browser_query = array(
		array(
			"id" => "Chrome",
			"query" => array(
				"select" => "CAST(SUBSTRING(browserVersion, 1, LOCATE('.', browserVersion) - 1) AS SIGNED)",
				"where"  => "browser = 'Chrome'"
			)
		),
		array(
			"id" => "Firefox",
			"query" => array(
				"select" => "CAST(SUBSTRING_INDEX(browserVersion, '.', 2) AS DECIMAL(3,1))",
				"where"  => "browser = 'Firefox'"
			)
		),
		array(
			"id" => "Safari mobile",
			"query" => array(
				"select" => "operatingSystem",
				"where"  => "browser = 'Safari' AND CAST(SUBSTRING(browserVersion, 1, LOCATE('.', browserVersion) - 1) AS SIGNED) > 6000"
			)
		),
		array(
			"id" => "Safari 3",
			"query" => array(
				"select" => "3",
				"where"  => "browser = 'Safari' AND CAST(SUBSTRING(browserVersion, 1, LOCATE('.', browserVersion) - 1) AS DECIMAL(6,2)) < 526"
			)
		),
		array(
			"id" => "Safari 4",
			"query" => array(
				"select" => "4",
				"where"  => "browser = 'Safari' AND CAST(SUBSTRING(browserVersion, 1, LOCATE('.', browserVersion) - 1) AS DECIMAL(6,2)) BETWEEN 526 AND 532"
			)
		),
		array(
			"id" => "Safari 5",
			"query" => array(
				"select" => "5",
				"where"  => "browser = 'Safari' AND CAST(SUBSTRING(browserVersion, 1, LOCATE('.', browserVersion) - 1) AS DECIMAL(6,2))  BETWEEN 533 AND 6000"
			)
		),
		array(
			"id" => "Everything else",
			"query" => array(
				"select" => "browserVersion",
				"where"  => "browser != 'Firefox' AND browser != 'Chrome' AND browser != 'Safari'"
			)
		)
	);
	
	
	$report_query = "";
	
	for ($i = 0; $i < count($browser_query); $i++) {
		if ($i != 0) {
			$report_query .= " UNION ";
		}
		$report_query .= "(SELECT browser, ";
		$report_query .= $browser_query[$i]['query']['select'];
		$report_query .= " AS ver, SUM(visitors) AS v FROM browsers WHERE ";
		$report_query .= $browser_query[$i]['query']['where'];
		$report_query .= " AND year = $year AND month = $month GROUP BY browser, ver)";
	}
	
	$report_total_query = "SELECT SUM(visitors) AS v FROM browsers WHERE year = $year AND month = $month";
	$total_visitor = mysql_fetch_array(mysql_query($report_total_query));
	$total_visitor = $total_visitor['v'];
	
	$result_report_query = mysql_query($report_query);
	
	$arr = array();
	$c = 0;
	while ($row = mysql_fetch_row($result_report_query)) {
		foreach ($row as $k => $v) {
			$arr[$c][$k] = $v;
		}
		$arr[$c][$k + 1] = number_format(($v * 100 / $total_visitor), 2);
		$c++;
	}
	
	return array(
		'result_report_query' => $arr,
		'total_visitor' => $total_visitor
	);
}


/**
 * Export os statistics
 * @param int $year year of the required data
 * @param int $month month of the required data
 * @return array('result_report_query','total_visitor')
 */
function query_os($year, $month) {
	
	$report_query = "SELECT operatingSystem, operatingSystemVersion, SUM(visitors) AS v
		FROM browsers
		WHERE year = $year AND month = $month
		GROUP BY year, month, operatingSystem, operatingSystemVersion
		ORDER BY v DESC";

	
	$report_total_query = "SELECT SUM(visitors) AS v FROM browsers WHERE year = $year AND month = $month";
	$total_visitor = mysql_fetch_array(mysql_query($report_total_query));
	$total_visitor = $total_visitor['v'];
	
	$result_report_query = mysql_query($report_query);
	
	$arr = array();
	$c = 0;
	while ($row = mysql_fetch_row($result_report_query)) {
		foreach ($row as $k => $v) {
			$arr[$c][$k] = $v;
		}
		$arr[$c][$k + 1] = number_format(($v * 100 / $total_visitor), 2);
		$c++;
	}
	
	return array(
		'result_report_query' => $arr,
		'total_visitor' => $total_visitor
	);
}


/**
 * Export screen resolutions statistics
 * @param int $year year of the required data
 * @param int $month month of the required data
 * @return array('result_report_query','total_visitor')
 */
function query_screen_res($year, $month) {
	
	$report_query = "SELECT width, height, SUM(visitors) AS v
		FROM screens
		WHERE year = $year AND month = $month
		GROUP BY year, month, width, height
		ORDER BY v DESC";
	
	$report_total_query = "SELECT SUM(visitors) AS v FROM screens WHERE year = $year AND month = $month";
	$total_visitor = mysql_fetch_array(mysql_query($report_total_query));
	$total_visitor = $total_visitor['v'];
	
	$result_report_query = mysql_query($report_query);
	
	$arr = array();
	$c = 0;
	while ($row = mysql_fetch_row($result_report_query)) {
		foreach ($row as $k => $v) {
			$arr[$c][$k] = $v;
		}
		$arr[$c][$k + 1] = number_format(($v * 100 / $total_visitor), 2);
		$c++;
	}
	
	return array(
		'result_report_query' => $arr,
		'total_visitor' => $total_visitor
	);
}


/**
 * Export help pages statistics
 * @param int $year year of the required data
 * @param int $month month of the required data
 * @return array('result_report_query','total_visitor')
 */
function query_help_page($year, $month) {
	
	$report_query = "SELECT REPLACE(pagePath, '/_act/get_page.php?pgName=/help/', ''), visitors AS v, pageviews, CONCAT('=', avgTimeOnPage, '/(60*60*24)')
		FROM helppages
		WHERE year = $year AND month = $month
		GROUP BY year, month, pagePath
		ORDER BY v DESC";
	
	$report_total_query = "SELECT SUM(visitors) AS v FROM helppages WHERE year = $year AND month = $month";
	$total_visitor = mysql_fetch_array(mysql_query($report_total_query));
	$total_visitor = $total_visitor['v'];
	
	$result_report_query = mysql_query($report_query);
	
	$arr = array();
	$c = 0;
	while ($row = mysql_fetch_row($result_report_query)) {
		foreach ($row as $k => $v) {
			$arr[$c][$k] = $v;
		}
		$c++;
	}
	
	return array(
		'result_report_query' => $arr,
		'total_visitor' => $total_visitor
	);
}


/**
 * Export countries statistics
 * @param int $year year of the required data
 * @param int $month month of the required data
 * @return array('result_report_query','total_visitor')
 */
function query_countries($year, $month) {
	
	$report_query = "SELECT country, visitors AS v
		FROM countries
		WHERE year = $year AND month = $month
		GROUP BY year, month, country
		ORDER BY v DESC";

	
	$report_total_query = "SELECT SUM(visitors) AS v FROM countries WHERE year = $year AND month = $month";
	$total_visitor = mysql_fetch_array(mysql_query($report_total_query));
	$total_visitor = $total_visitor['v'];
	
	$result_report_query = mysql_query($report_query);
	
	$arr = array();
	$c = 0;
	while ($row = mysql_fetch_row($result_report_query)) {
		foreach ($row as $k => $v) {
			$arr[$c][$k] = $v;
		}
		$arr[$c][$k + 1] = number_format(($v * 100 / $total_visitor), 2);
		$c++;
	}
	
	return array(
		'result_report_query' => $arr,
		'total_visitor' => $total_visitor
	);
}


/**
 * Export access times statistics
 * @param int $year year of the required data
 * @param int $month month of the required data
 * @return array('result_report_query','total_visitor')
 */
function query_access_time($year, $month) {
	
	$report_query = "SELECT CONCAT('=CHOOSE(',dayOfWeek + 1, ', \"Sunday\", \"Monday\", \"Tuesday\", \"Wednesday\", \"Thursday\", \"Friday\", \"Saturday\")'), hour, AVG(noext_visitors) AS v, AVG(noext_visits) AS w, CONCAT('=', AVG(noext_avgTimeOnSite), '/(60*60*24)') AS t
		FROM accesstimes
		WHERE year(date) = $year AND month(date) = $month
		GROUP BY hour, dayOfWeek
		ORDER BY dayOfWeek, hour";
	
	$result_report_query = mysql_query($report_query);
	
	$arr = array();
	$c = 0;
	while ($row = mysql_fetch_row($result_report_query)) {
		foreach ($row as $k => $v) {
			$arr[$c][$k] = $v;
		}
		$c++;
	}
	
	return array(
		'result_report_query' => $arr,
		'total_visitor' => $total_visitor
	);
}
?>