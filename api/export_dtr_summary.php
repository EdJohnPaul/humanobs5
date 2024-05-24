<?php
    header("Access-Control-Allow-Origin: *");
    
	require_once "functions.loader.php";
	date_default_timezone_set("Asia/Manila");
	
	$var = $_GET["var"];
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];

    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
    $objPHPExcel->getActiveSheet()->getProtection()->setPassword("686");
    $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
    $objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
    $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
    $objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);

    $headerStyleArray = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
            ),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
	
    /* Night Differential */
	$codeND = "ND";
	
	$rateND = "0.10";
    
	/* Overtime */
	$codeOT = "RegOT";
	$codeRDOT = "RDOT";
	$codeSHOT = "SHOT";
	$codeSHRDOT = "SHRDOT";
	$codeRHOT = "RHOT";	
	$codeRHRDOT = "RHROT";
	
	$rateOT = "1.25";
	$rateRDOT = "1.69";
	$rateSHOT = "1.69";
	$rateSHRDOT = "1.95";
	$rateRHOT = "2.60";
	$rateRHRDOT = "3.38";
	
	/* Holiday */
	$codeRD = "RD";
	$codeSH = "SH";
	$codeSHRD = "SHRD";
	$codeRH = "RH";
	$codeRHRD = "RHRD";
	
	/* Daily Rate */
	$rateRD = "0.30";
	$rateSH = "0.30";
	$rateSHRD = "0.50";
	$rateRH = "1.0";
	$rateRHRD = "1.6";
	
	/* Monthly Rate */
	$rateRDp = "1.30";
	$rateSHp = "1.30";
	$rateSHRDp = "1.50";
	$rateRHp = "2.0";
	$rateRHRDp = "2.6";

    $period = read_timekeeping_log_file_by_uid($uid);	
	$title = "Timekeeping Record as of " . str_replace("From", "", $period->period);
	
    $objPHPExcel->getActiveSheet()->mergeCells("A1:I1");
    $objPHPExcel->getActiveSheet()->SetCellValue("A1", $title);
    $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true)));
    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setName("Verdana");
    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(8);
	
	$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setVisible(FALSE);

	$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('L1', $codeND)
				->setCellValue('M1', $codeOT)
				->setCellValue('N1', $codeRDOT)
                ->setCellValue('O1', $codeSHOT)
                ->setCellValue('P1', $codeSHRDOT)
                ->setCellValue('Q1', $codeRHOT)
                ->setCellValue('R1', $codeRHRDOT)
                ->setCellValue('S1', '')
				->setCellValue('T1', $codeRD)
				->setCellValue('U1', $codeSH)
				->setCellValue('V1', $codeSHRD)
				->setCellValue('W1', $codeRH)
				->setCellValue('X1', $codeRHRD)
				->setCellValue('Y1', $codeRD);

    $objPHPExcel->getActiveSheet()->getStyle('N1:X1')->getFont()->setBold(true);

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A2', 'Emp #')
                ->setCellValue('B2', 'Employee Name')
                ->setCellValue('C2', 'Type')
                ->setCellValue('D2', 'Days Present')
                ->setCellValue('E2', 'Days Absent')
                ->setCellValue('F2', 'Late/Tardiness')
                ->setCellValue('G2', 'Total Tardy(Min)')
                ->setCellValue('H2', 'Total OT')
                ->setCellValue('I2', 'Approved OT');

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('K2', "") // Basic
                ->setCellValue('L2', $rateND)
                ->setCellValue('M2', $rateOT)
                ->setCellValue('N2', $rateRDOT)
                ->setCellValue('O2', $rateSHOT)
                ->setCellValue('P2', $rateSHRDOT)
                ->setCellValue('Q2', $rateRHOT)
                ->setCellValue('R2', $rateRHRDOT)
                ->setCellValue('S2', '')
				->setCellValue('T2', $rateRD)
				->setCellValue('U2', $rateSH)
				->setCellValue('V2', $rateSHRD)
				->setCellValue('W2', $rateRH)
				->setCellValue('X2', $rateRHRD)
                ->setCellValue('Y2', $rateRDp)				
				->setCellValue('Z2', "TOTAL");

    $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(8);
    $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(9);
    $objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth(5);
    $objPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension("L")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("M")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("O")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("P")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("Q")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("R")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("S")->setWidth(2);
    $objPHPExcel->getActiveSheet()->getColumnDimension("T")->setWidth(6);
	$objPHPExcel->getActiveSheet()->getColumnDimension("U")->setWidth(6);
	$objPHPExcel->getActiveSheet()->getColumnDimension("V")->setWidth(6);
	$objPHPExcel->getActiveSheet()->getColumnDimension("W")->setWidth(6);
	$objPHPExcel->getActiveSheet()->getColumnDimension("X")->setWidth(6);
	$objPHPExcel->getActiveSheet()->getColumnDimension("Y")->setWidth(6);
	$objPHPExcel->getActiveSheet()->getColumnDimension("Z")->setWidth(12);

    $sheet = $objPHPExcel->getActiveSheet(); 

    $header = "A2:I2";
    $sheet->getStyle($header)->getFont()->setName("Verdana");
    $sheet->getStyle($header)->getFont()->setSize(8);
    $sheet->getStyle($header)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
    $sheet->getStyle($header)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle($header)->getFill()->getStartColor()->setRGB("2DA8EA");
    $sheet->getStyle($header)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $sheet->getStyle($header)->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));
    $sheet->getStyle($header)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $header2 = "K2:X2";
	$sheet->getStyle($header2)->getFont()->setName("Verdana");
    $sheet->getStyle($header2)->getFont()->setSize(8);
    $sheet->getStyle($header2)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
    $sheet->getStyle($header2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle($header2)->getFill()->getStartColor()->setRGB("2DA8EA");
    $sheet->getStyle($header2)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $sheet->getStyle($header2)->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));
    $sheet->getStyle($header2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$header3 = "Y2:Z2";
	$sheet->getStyle($header3)->getFont()->setName("Verdana");
    $sheet->getStyle($header3)->getFont()->setSize(8);
    $sheet->getStyle($header3)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
    $sheet->getStyle($header3)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle($header3)->getFill()->getStartColor()->setRGB("2DA8EA");
    $sheet->getStyle($header3)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $sheet->getStyle($header3)->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));
    $sheet->getStyle($header3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $sheet->getStyle('K1:Y1')->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle('K1:Y1')->getFont()->setSize(9);
    $sheet->getStyle('K1:Y1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('K1:Y1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $sheet->getStyle('K1:Y1')->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));
    $sheet->getStyle('L1:Y1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
    $sheet->getStyle('L1:Y1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('L1:Y1')->getFill()->getStartColor()->setRGB('2E45EA');

    $sheet->getStyle('K2:Z2')->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle('Z2')->getFont()->setSize(9);
    $sheet->getStyle('K2:Z2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('K2:Z2')->getFill()->getStartColor()->setRGB('2DA8EA');
    $sheet->getStyle('K2:Z2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('K2:Z2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $sheet->getStyle('K2:Z2')->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));

    $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('A1')->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle('A1:I1')->getFont()->setSize(9);
    $sheet->getStyle('A1:I1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
    $sheet->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A1:I1')->getFill()->getStartColor()->setRGB('2E45EA');
	
	/* <!-- Holiday and Restday Rate --> */
	$sheet->getStyle('Y1:Z1')->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle('Y1:Z1')->getFont()->setSize(9);
	$sheet->getStyle('Y1:Z1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
    $sheet->getStyle('Y1:Z1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('Y1:Z1')->getFill()->getStartColor()->setRGB('1A1C58');
    $sheet->getStyle('Y1:Z1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('Y1:Z1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $sheet->getStyle('Y1:Z1')->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));
	/* <!-- Holiday and Restday Rate --> */
	
	$sheet->getStyle('J')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
	$sheet->getStyle('K')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
    
    foreach($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) { 
        $rd->setRowHeight(5); 
    }   
	
	$row = 3;
	
	$results = read_timekeeping_summary_by_uid($uid);	
	foreach($results as $result) {
		$emp_uid = $result->emp_uid;
		
		$name = read_employee_name_by_uid($emp_uid);		
		$emp_num = getEmployeeDetailsByUid($emp_uid);
		
		$get_salary = getSalaryByUid($emp_uid);
		$base_salary = $get_salary->base_salary;
		$pay_period = $get_salary->pay_period_name;
		
		# work_days_per_year work_hours_per_day work_hours_start work_hours_end break_hours 
		$work_policy = read_work_policy();
		$work_days_per_year = $work_policy->work_days_per_year;
		$work_hours_per_day = $work_policy->work_hours_per_day;
		
		$total_tardy_min = 0;
		
		# Daily Weekly Bi-Weekly Semi-Monthly Monthly Quarterly Semi-Annual Annual Fixed
		switch($pay_period) {
			case "Monthly":
				$basic = $base_salary;
				$daily = ($base_salary * 12) / $work_days_per_year;
				$total_tardy_min = $result->total_tardiness;
				break;
			case "Daily":
				$basic = ($base_salary * $work_days_per_year) / 12;
				$daily = $base_salary;
				$total_tardy_min = $result->total_tardy_min;
				break;
			default:
				$basic = $base_salary;
				$daily = ($base_salary * 12) / $work_days_per_year;
				break;
		}
		
		$hourly = $daily / $work_hours_per_day;
		
		$objPHPExcel->getActiveSheet()->setCellValue("A" . $row, $emp_num->username);
		$objPHPExcel->getActiveSheet()->getStyle("A" . $row)->getNumberFormat()->setFormatCode("0000");
		$objPHPExcel->getActiveSheet()->setCellValue("B" . $row, $name);
		$objPHPExcel->getActiveSheet()->setCellValue("C" . $row, $pay_period); //Type
		$objPHPExcel->getActiveSheet()->setCellValue("D" . $row, $result->days_present);
		$objPHPExcel->getActiveSheet()->setCellValue("E" . $row, $result->days_absent);		
		$objPHPExcel->getActiveSheet()->setCellValue("F" . $row, $result->total_tardy);
		$objPHPExcel->getActiveSheet()->setCellValue("G" . $row, $total_tardy_min);
		
		$objPHPExcel->getActiveSheet()->setCellValue("H" . $row, "00:00:00");
		$objPHPExcel->getActiveSheet()->setCellValue("I" . $row, "00:00:00");		
		
		// ND, RegOT, RDOT, SHOT, SHRDOT, RHOT, RHRDOT, RD, SH, SHRD, RH, RHRD
		$overtime_holiday = read_temp_overtime_holiday_summary_by_uid_emp_uid($uid, $emp_uid);
		$objPHPExcel->getActiveSheet()->setCellValue("L" . $row, $overtime_holiday->nd);
		$objPHPExcel->getActiveSheet()->setCellValue("M" . $row, $overtime_holiday->ot);
		$objPHPExcel->getActiveSheet()->setCellValue("N" . $row, $overtime_holiday->rdot);
		$objPHPExcel->getActiveSheet()->setCellValue("O" . $row, $overtime_holiday->shot);
		$objPHPExcel->getActiveSheet()->setCellValue("P" . $row, $overtime_holiday->shrdot);
		$objPHPExcel->getActiveSheet()->setCellValue("Q" . $row, $overtime_holiday->rhot);
		$objPHPExcel->getActiveSheet()->setCellValue("R" . $row, $overtime_holiday->rhrdot);
		
		$objPHPExcel->getActiveSheet()->setCellValue("T" . $row, 0);
		$objPHPExcel->getActiveSheet()->setCellValue("U" . $row, 0);
		$objPHPExcel->getActiveSheet()->setCellValue("V" . $row, 0);
		$objPHPExcel->getActiveSheet()->setCellValue("W" . $row, 0);
		$objPHPExcel->getActiveSheet()->setCellValue("X" . $row, 0);		
		$objPHPExcel->getActiveSheet()->setCellValue("Y" . $row, 0);
		
		if( $pay_period === "Daily" ) {
			$objPHPExcel->getActiveSheet()->setCellValue("T" . $row, $overtime_holiday->rd);
		}else {
			$objPHPExcel->getActiveSheet()->setCellValue("Y" . $row, $overtime_holiday->rd);
		}
			
		$objPHPExcel->getActiveSheet()->setCellValue("U" . $row, $overtime_holiday->sh);
		$objPHPExcel->getActiveSheet()->setCellValue("V" . $row, $overtime_holiday->shrd);
		$objPHPExcel->getActiveSheet()->setCellValue("W" . $row, $overtime_holiday->rh);
		$objPHPExcel->getActiveSheet()->setCellValue("X" . $row, $overtime_holiday->rhrd);
		
		
		//$formula = "= (J" . $row . "*L". $row . "*L$2) + (J" . $row . "*M" . $row . "*M$2) + (J" . $row . "*N" . $row . "*N$2) + (J" . $row . "*O". $row . "*O$2) + (J" . $row . "*P". $row . "*P$2) + (J" . $row . "*Q". $row . "*Q$2) + (J" . $row . "*R". $row . "*R$2) + (J" . $row . "*S". $row . "*S$2) + (J" . $row . "*T". $row . "*T$2) + (J" . $row . "*U". $row . "*U$2) + (J" . $row . "*V". $row . "*V$2) + (J" . $row . "*W". $row . "*W$2) + (J" . $row . "*X". $row . "*X$2)";
		
		$formula = "=";
		$formula .= "(J" . $row . "*L". $row . "*L$2)+";
		$formula .= "(J" . $row . "*M" . $row . "*M$2)+";
		$formula .= "(J" . $row . "*N" . $row . "*N$2)+";
		$formula .= "(J" . $row . "*O". $row . "*O$2)+";
		$formula .= "(J" . $row . "*P". $row . "*P$2)+";
		$formula .= "(J" . $row . "*Q". $row . "*Q$2)+";
		$formula .= "(J" . $row . "*R". $row . "*R$2)+";
		$formula .= "(J" . $row . "*S". $row . "*S$2)+";
		$formula .= "(J" . $row . "*T". $row . "*T$2)+";
		$formula .= "(J" . $row . "*U". $row . "*U$2)+";
		$formula .= "(J" . $row . "*V". $row . "*V$2)+";
		$formula .= "(J" . $row . "*W". $row . "*W$2)+";
		$formula .= "(J" . $row . "*X". $row . "*X$2)+";		
		$formula .= "(J" . $row . "*Y". $row . "*Y$2)";
		
		$objPHPExcel->getActiveSheet()->setCellValue("K" . $row, ""); // Basic Pay
		$objPHPExcel->getActiveSheet()->setCellValue("J" . $row, $hourly); // Hourly Rate
		$objPHPExcel->getActiveSheet()->setCellValue("Z" . $row, $formula); // Total Overtime and Holiday
		
		$cols = array('K');
		foreach ($cols as $cname) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($cname)->setVisible(FALSE);
		}
		
		$sheet->getStyle("F" . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle("H" . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle("I" . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$rows = "A".$row.":Z".$row;
		$sheet->getStyle($rows)->getFont()->setName("Verdana");
		$sheet->getStyle($rows)->getFont()->setSize(8);
		$sheet->getStyle("J".$row)->getNumberFormat()->setFormatCode('###,###,##0.00');
		$sheet->getStyle("K".$row)->getNumberFormat()->setFormatCode('###,###,##0.00');
		$sheet->getStyle("Z".$row)->getNumberFormat()->setFormatCode('###,###,##0.00');
		
		$row++;
		
	}
	
	$total_row = $row;
	$last_row = $row - 1;
	$objPHPExcel->getActiveSheet()->setCellValue("Z" . $total_row, "=SUM(Z3:Z" . $last_row . ")");
	
	$sheet->getStyle("Z" . $total_row)->applyFromArray(array("font" => array( "bold" => true)));
	$sheet->getStyle("Z" . $total_row)->getNumberFormat()->setFormatCode('###,###,##0.00');
	$sheet->getStyle("Z" . $total_row)->getFont()->setName("Verdana");
	$sheet->getStyle("Z" . $total_row)->getFont()->setSize(8);
	
    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('TimekeepingSummary');
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    // Save Excel 2007 file    
	$callStartTime = microtime(true);
    $fileName = "timekeeping_summary_" . date('YmdHis') . ".xlsx";
    $writer = new PHPExcel_Writer_Excel5($objPHPExcel);
    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$fileName.'"');
    header('Cache-Control: max-age=0');
    $writer->save("php://output");	
	
	echo "Success";
?>