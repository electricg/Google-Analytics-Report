<?php
require_once 'phpexcel-1.7.6/Classes/PHPExcel.php';


/**
 * @param int $year year of the required data
 * @param int $month month of the required data
 * @param string $what name of the statistic, used for titles
 * @param array $col_header titles of the columns
 * @param array $report multi dimensional array containing data
 * @param int $total_visitor number of total visitors
 * @param array $col_format settings for the format of the excel columns
 * @param int $col_total_visitor excel column in which write total visitors number
 * @return null send the file to be saved
 */
function create_report($year, $month, $what, $col_header, $col_format, $report, $total_visitor, $col_total_visitor=0) {
	
	// Preparations
	$_ALPHA = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$str_stat = 'statistics';
	$str_tot = 'total';
	$first_line = 3;
	$format = array(
		'text' => PHPExcel_Style_NumberFormat::FORMAT_TEXT,
		'float' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
		'int1000' => '#,##0',
		'int' => '###0',
		'time' => 'hh:mm:ss'
	);
	
	$date_name = date('F Y', mktime(0, 0, 0, $month, 1, $year));
	$title = ucwords($what.' '.$str_stat);
	$col_len = count($col_header) - 1;
	
	// Thin black border
	$styleThinBlackBorderAll = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => 'FF000000'),
			),
		),
	);
	// Thick black border
	$styleThickBlackBorderOutline = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
				'color' => array('argb' => 'FF000000'),
			),
		),
	);
	
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	
	// Set properties
	$objPHPExcel->getProperties()
		->setCreator('Giulia Alfonsi - Pure360')
		->setLastModifiedBy('Giulia Alfonsi - Pure360')
		->setTitle($title.' - '.$date_name)
		->setKeywords($title.' '.$date_name);
	
	// Add some data
	$objPHPExcel->setActiveSheetIndex(0);
	
	$sh1 = $objPHPExcel->getActiveSheet();
	
	$sh1->setCellValue('A1', $title.' - '.$date_name);
	
	foreach ($col_header as $k => $h) {
		$sh1->setCellValue($_ALPHA[$k].$first_line, $h);
	}
	
	$i = $first_line + 1;
	foreach ($report as $row) {
		foreach ($row as $k => $v) {
			$sh1->setCellValue($_ALPHA[$k].$i, $v);
		}
		$i++;
	}
	
	// Total visitors
	if ($col_total_visitor > 0) {
		$sh1->setCellValue($_ALPHA[$col_total_visitor - 2] . $i, ucfirst($str_tot))
			->setCellValue($_ALPHA[$col_total_visitor - 1] . $i, $total_visitor);
		// style bold
		$sh1->getStyle('A'.$i.':'.$_ALPHA[$col_len].$i)->getFont()->setBold(true);
		// style borders
		$sh1->getStyle('A'.$i.':'.$_ALPHA[$col_len].$i)->applyFromArray($styleThinBlackBorderAll);
		$sh1->getStyle('A'.$i.':'.$_ALPHA[$col_len].$i)->applyFromArray($styleThickBlackBorderOutline);
	}
	
	// Set autofilter
	$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$first_line.':'.$_ALPHA[$col_len].$i);
	// Set repeated row
	$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(3, 3);
	
	// Style
	// align
	$sh1->mergeCells('A1:'.$_ALPHA[$col_len].'1')->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sh1->getStyle('A'.$first_line.':'.$_ALPHA[$col_len].$first_line)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	// bold
	$sh1->getStyle('A1')->getFont()->setBold(true);
	$sh1->getStyle('A'.$first_line.':'.$_ALPHA[$col_len].$first_line)->getFont()->setBold(true);
	// borders
	$sh1->getStyle('A'.$first_line.':'.$_ALPHA[$col_len].($i - 1))->applyFromArray($styleThinBlackBorderAll);
	
	for ($c = 0; $c <= $col_len; $c++) {
		// column widths
		$sh1->getColumnDimension($_ALPHA[$c])->setAutoSize(true);
		// cell number formats
		$sh1->getStyle($_ALPHA[$c].($first_line + 1).':'.$_ALPHA[$c].$i)->getNumberFormat()->setFormatCode($format[$col_format[$c]]);
	}
	
	
	// Rename sheet
	$sh1->setTitle(ucfirst($what).' '.$date_name);
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$what.'_'.$year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	
	exit;
} // end create_report
?>