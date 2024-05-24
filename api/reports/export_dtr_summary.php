<?php
    header("Access-Control-Allow-Origin: *");
    require_once "functions.loader.php";
	//require_once 'functions.php';
    //require 'Slim/Slim.php';
    //require("assets/lib/Base32.php");
    //require_once("assets/lib/dompdf/dompdf_config.inc.php");
    //require_once("../Classes/PHPExcel.php");
    //require_once("../Classes/PHPExcel/IOFactory.php");
    //require_once("../Classes/PHPExcel/Writer/Excel2007.php");
	
    /*$var = $_GET["data"];
    $param = explode(".", $var);
    $startDate = $param[0];
    $endDate = $param[1];
    $cost = $param[2];*/
	
	$var = $_GET["var"];
	$param = explode(".", $var);
	$uid = $param[0];
	$token = $param[1];


    //$start = date("F d, Y", strtotime($startDate));
    //$end = date("F d, Y", strtotime($endDate));

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
    // Add some data
    $rateCode30 = "30%";
    $rateCode100 = "100%";
    $rateCode10 = "10%";
    $rateCode125 = "125%";
    $rateCode130 = "130%";
    $rateCode150 = "150%";
    $rateCode200 = "200%";
    $rateCode260 = "260%";

    $codeND = "ND";
    $codeRegOT = "RegOT";
    $codeRHROT = "RHROT";
    $codeRHOT = "RHOT";
    $codeSHROT = "SHROT";
    $codeRDOT = "RDOT";
    $codeSHOT = "SHOT";

    // $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:I1');
    // $objPHPExcel->getActiveSheet()->setCellValue("A1", "TIMEKEEPING RECORD AS OF " . $start . " TO " . $end);
    // $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

    $title = "Timekeeping Record as of ".$start." to ".$end;
    
    //$sheet->mergeCells('A1:F1');
    $objPHPExcel->getActiveSheet()->mergeCells("A1:I1");
    $objPHPExcel->getActiveSheet()->SetCellValue("A1", $title);
    $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true)));
    // $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);
    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setName("Verdana");
    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(8);

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('L1:M1');
    $objPHPExcel->getActiveSheet()->setCellValue("L1", "Monthly");
    $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('N1', 'ND')
                ->setCellValue('O1', 'REG')
                ->setCellValue('P1', 'RDSD')
                ->setCellValue('Q1', 'RDOSD')
                ->setCellValue('R1', 'RHD')
                ->setCellValue('S1', 'RHORD');

    $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('R1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('S1')->getFont()->setBold(true);

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A2', 'Emp #')
                ->setCellValue('B2', 'Employee Name')
                ->setCellValue('C2', 'Type')
                ->setCellValue('D2', 'Days Present')
                ->setCellValue('E2', 'Days Absent')
                ->setCellValue('F2', 'Late/Tardiness')
                ->setCellValue('G2', 'Total Tardy')
                ->setCellValue('H2', 'Total OT')
                ->setCellValue('I2', 'Approved OT');

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('K2', "Basic")
                ->setCellValue('L2', $rateCode30)
                ->setCellValue('M2', $rateCode100)
                ->setCellValue('N2', $rateCode10)
                ->setCellValue('O2', $rateCode125)
                ->setCellValue('P2', $rateCode130)
                ->setCellValue('Q2', $rateCode150)
                ->setCellValue('R2', $rateCode200)
                ->setCellValue('S2', $rateCode260)
                ->setCellValue('T2', "TOTAL");

    $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(13);
    $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(8);
    $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(9);
    $objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth(11);

    $objPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension("L")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("M")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("O")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("P")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("Q")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("R")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("S")->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension("T")->setWidth(12);

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

    $header2 = "K2:T2";

    $sheet->getStyle($header2)->getFont()->setName("Verdana");
    $sheet->getStyle($header2)->getFont()->setSize(8);
    $sheet->getStyle($header2)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
    $sheet->getStyle($header2)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle($header2)->getFill()->getStartColor()->setRGB("2DA8EA");
    $sheet->getStyle($header2)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $sheet->getStyle($header2)->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));
    $sheet->getStyle($header2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


    $sheet->getStyle('K1:T1')->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle('K1:T1')->getFont()->setSize(9);
    $sheet->getStyle('K1:T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('K1:T1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $sheet->getStyle('K1:T1')->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));
    $sheet->getStyle('L1:S1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
    $sheet->getStyle('L1:S1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('L1:S1')->getFill()->getStartColor()->setRGB('2E45EA');

    $sheet->getStyle('K2:T2')->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle('K2:T2')->getFont()->setSize(9);
    $sheet->getStyle('K2:T2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('K2:T2')->getFill()->getStartColor()->setRGB('2DA8EA');
    $sheet->getStyle('K2:T2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('K2:T2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $sheet->getStyle('K2:T2')->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));

    $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('A1')->applyFromArray(array("font" => array( "bold" => true)));
    // $sheet->getRowDimension(1)->setRowHeight(20);
    $sheet->getStyle('A1:I1')->getFont()->setSize(12);
    $sheet->getStyle('A1:I1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
    $sheet->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A1:I1')->getFill()->getStartColor()->setRGB('2E45EA');
    
    foreach($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) { 
        $rd->setRowHeight(5); 
    }   

    // Rename worksheet
    // echo date('H:i:s') , " Rename worksheet<br/>";
    $objPHPExcel->getActiveSheet()->setTitle('TimekeepingSummary');
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Save Excel 2007 file
    
	$callStartTime = microtime(true);
    $fileName = date('Y-m-d') . " Timesheet Summary" . ".xlsx";
    $writer = new PHPExcel_Writer_Excel5($objPHPExcel);
    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$fileName.'"');
    header('Cache-Control: max-age=0');
    $writer->save("php://output");
	
	
    echo "SUCCESS";
    // echo jsonify($response);
?>