<?php
header("Access-Control-Allow-Origin: *");
    
require_once "functions.loader.php";
date_default_timezone_set("Asia/Manila");

$objPHPExcel = new PHPExcel();

$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getProtection()->setPassword("686");
$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
$objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
$objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);

$header = "A1:R1"; 	
$objPHPExcel->getActiveSheet()->mergeCells($header);
//$objPHPExcel->getActiveSheet()->getStyle($header)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->getStartColor()->setRGB("e0dbdb");
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));
$objPHPExcel->getActiveSheet()->getStyle($header)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$col = "A1"; $title = "ALPHALIST OF EMPLOYEES";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $title);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->applyFromArray(array("font" => array( "bold" => true)));
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

$header = "A2:R2";
$objPHPExcel->getActiveSheet()->mergeCells($header);
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->getStartColor()->setRGB("e0dbdb");
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));
$objPHPExcel->getActiveSheet()->getStyle($header)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$col = "A2"; $title = "P  R  E  S  E  N  T     E  M  P  L  O  Y  E  R";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $title);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->applyFromArray(array("font" => array( "bold" => true)));
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

$header = "A3:R3";
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->getStartColor()->setRGB("e0dbdb");
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));

$header = "A4:R4";
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->getStartColor()->setRGB("e0dbdb");
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));

$header = "A5:R5";
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->getStartColor()->setRGB("e0dbdb");
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));


$objPHPExcel->getActiveSheet()->mergeCells("A3:A4");
$objPHPExcel->getActiveSheet()->SetCellValue("A3", "Seq. No.");
$objPHPExcel->getActiveSheet()->getStyle("A3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A3")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A3")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setSize(8);

$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(40);
$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(110);
$objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(10);

$objPHPExcel->getActiveSheet()->mergeCells("B3:D3");
$objPHPExcel->getActiveSheet()->SetCellValue("B3", "NAME OF EMPLYEES");
$objPHPExcel->getActiveSheet()->getStyle("B3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("B3")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("B3")->getAlignment()->setWrapText(true);
// $objPHPExcel->getActiveSheet()->getStyle("B3")->applyFromArray(array("font" => array( "bold" => true)));
$objPHPExcel->getActiveSheet()->getStyle("B3")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("B3")->getFont()->setSize(8);

$objPHPExcel->getActiveSheet()->mergeCells("G3:H3");
$objPHPExcel->getActiveSheet()->SetCellValue("G3", "EMPLOYMENT PERIOD");
$objPHPExcel->getActiveSheet()->getStyle("G3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("G3")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("G3")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("G3")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("G3")->getFont()->setSize(8);

$objPHPExcel->getActiveSheet()->mergeCells("E3:E4");
$objPHPExcel->getActiveSheet()->SetCellValue("E3", "Nationality/Resident");
$objPHPExcel->getActiveSheet()->getStyle("E3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("E3")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("E3")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("E3")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("E3")->getFont()->setSize(8);

$objPHPExcel->getActiveSheet()->mergeCells("F3:F4");
$objPHPExcel->getActiveSheet()->SetCellValue("F3", "Current Employment");
$objPHPExcel->getActiveSheet()->getStyle("F3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("F3")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("F3")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("F3")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("F3")->getFont()->setSize(8);

$objPHPExcel->getActiveSheet()->mergeCells("I3:I4");
$objPHPExcel->getActiveSheet()->SetCellValue("I3", "Gross Compensation Income");
$objPHPExcel->getActiveSheet()->getStyle("I3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("I3")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("I3")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("I3")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("I3")->getFont()->setSize(8);

$objPHPExcel->getActiveSheet()->SetCellValue("B4", "Last Name");
$objPHPExcel->getActiveSheet()->getStyle("B4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("B4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("B4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("B4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("B4")->getFont()->setSize(8);

$objPHPExcel->getActiveSheet()->SetCellValue("C4", "First Name");
$objPHPExcel->getActiveSheet()->getStyle("C4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("C4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("C4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("C4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("C4")->getFont()->setSize(8);

$objPHPExcel->getActiveSheet()->SetCellValue("D4", "Middle Name");
$objPHPExcel->getActiveSheet()->getStyle("D4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("D4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("D4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("D4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("D4")->getFont()->setSize(8);

$objPHPExcel->getActiveSheet()->SetCellValue("G4", "From (MM/DD)");
$objPHPExcel->getActiveSheet()->getStyle("G4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("G4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("G4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("G4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("G4")->getFont()->setSize(8);

$objPHPExcel->getActiveSheet()->SetCellValue("H4", "To (MM/DD)");
$objPHPExcel->getActiveSheet()->getStyle("H4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("H4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("H4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("H4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("H4")->getFont()->setSize(8);

$objPHPExcel->getActiveSheet()->mergeCells("J3:N3");
$objPHPExcel->getActiveSheet()->SetCellValue("J3", "NON-TAXABLE/EXEMPT");
$objPHPExcel->getActiveSheet()->getStyle("J3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("J3")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("J3")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("J3")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("J3")->getFont()->setSize(8);

$objPHPExcel->getActiveSheet()->mergeCells("O3:R3");
$objPHPExcel->getActiveSheet()->SetCellValue("O3", "TAXABLE");
$objPHPExcel->getActiveSheet()->getStyle("O3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("O3")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("O3")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("O3")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("O3")->getFont()->setSize(8);

//13th Month Pay & Other Benefits
$objPHPExcel->getActiveSheet()->SetCellValue("J4", "13th Month Pay & Other Benefits");
$objPHPExcel->getActiveSheet()->getStyle("J4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("J4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("J4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("J4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("J4")->getFont()->setSize(8);

//De Minimis Benefits
$objPHPExcel->getActiveSheet()->SetCellValue("K4", "De Minimis Benefits");
$objPHPExcel->getActiveSheet()->getStyle("K4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("K4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("K4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("K4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("K4")->getFont()->setSize(8);

//SSS, GSIS, PHIC, HDMF Contributions and Union Dues
$objPHPExcel->getActiveSheet()->SetCellValue("L4", "SSS, GSIS, PHIC, HDMF Contributions and Union Dues");
$objPHPExcel->getActiveSheet()->getStyle("L4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("L4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("L4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("L4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("L4")->getFont()->setSize(8);

//Salaries (P250,000 & below) & Other Forms of Compensation
$objPHPExcel->getActiveSheet()->SetCellValue("M4", "Salaries (P250,000 & below) & Other Forms of Compensation");
$objPHPExcel->getActiveSheet()->getStyle("M4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("M4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("M4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("M4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("M4")->getFont()->setSize(8);

//Total NonTaxable/Exempt Compensation Income
$objPHPExcel->getActiveSheet()->SetCellValue("N4", "Total NonTaxable/Exempt Compensation Income");
$objPHPExcel->getActiveSheet()->getStyle("N4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("N4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("N4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("N4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("N4")->getFont()->setSize(8);

//Basic Salary (net of SSS, GSIS, PHIC, HDMF
$objPHPExcel->getActiveSheet()->SetCellValue("O4", "Basic Salary (net of SSS, GSIS, PHIC, HDMF");
$objPHPExcel->getActiveSheet()->getStyle("O4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("O4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("O4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("O4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("O4")->getFont()->setSize(8);

//13th Month Pay & Other Benefits
$objPHPExcel->getActiveSheet()->SetCellValue("P4", "13th Month Pay & Other Benefits");
$objPHPExcel->getActiveSheet()->getStyle("P4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("P4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("P4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("P4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("P4")->getFont()->setSize(8);

//Salaries and Other Forms of Compensation
$objPHPExcel->getActiveSheet()->SetCellValue("Q4", "Salaries and Other Forms of Compensation");
$objPHPExcel->getActiveSheet()->getStyle("Q4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("Q4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("Q4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("Q4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("Q4")->getFont()->setSize(8);

//Total Taxable Compensation Income
$objPHPExcel->getActiveSheet()->SetCellValue("R4", "Total Taxable Compensation Income");
$objPHPExcel->getActiveSheet()->getStyle("R4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("R4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("R4")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("R4")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("R4")->getFont()->setSize(8);

$objPHPExcel->getActiveSheet()->SetCellValue("A5", "1");
$objPHPExcel->getActiveSheet()->getStyle("A5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A5")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A5")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A5")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("A5")->getFont()->setSize(7);
$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(4);

$col = "B5";
$objPHPExcel->getActiveSheet()->SetCellValue($col, "2a");
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "C5"; $label = "2b";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "D5"; $label = "2c";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "E5"; $label = "3";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "F5"; $label = "4";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "G5"; $label = "5a";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "H5"; $label = "5b";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "I5"; $label = "6a = (6n + 6r)";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "J5"; $label = "6b";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "K5"; $label = "6c";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "L5"; $label = "6d";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "M5"; $label = "6e";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "N5"; $label = "6f = (6b + 6c + 6d + 6e)";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);
$objPHPExcel->getActiveSheet()->getColumnDimension("N")->setWidth(14);

$col = "O5"; $label = "6g";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "P5"; $label = "6h";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "Q5"; $label = "6i";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);

$col = "R5"; $label = "6j = (6o + 6p + 6q)";
$objPHPExcel->getActiveSheet()->SetCellValue($col, $label);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(7);
$objPHPExcel->getActiveSheet()->getColumnDimension("R")->setWidth(12);

$row = 6;
$ctr =1;

$results = getActiveEmployees();
foreach($results as $result) {
    $annual_income = 0;
    $tax_status = null;
    $taxable_income = 0;
    $nontaxable_income = 0;
    $emp_uid = $result->emp_uid;
    //$name = read_employee_name_by_uid($emp_uid);
    $lastname = $result->lastname; 
    $firstname = $result->firstname;
    $middlename = $result->middlename;
    $nationality = $result->nationality; if(empty($nationality)||$nationality==="") {$nationality="N/A";}
    $salary = getSalaryByEmpUid($emp_uid); //echo $salary->base_salary . "</br>";
    if($salary) {
        $pay_period = get_employee_salary($emp_uid, "pay_period");
        if($pay_period==="Monthly") {
            $basic = $salary->base_salary;
            $annual_income = $basic * 12;
        }else if($pay_period==="Daily") {
            $basic = $salary->base_salary;
            $basic = $basic * 26;
            $annual_income = $basic * 12;
        }

        if($annual_income>="250000.01") {
            $tax_status = "Taxable";
            $taxable_income = $annual_income;
            $nontaxable_income = "0.00";
        }else if($annual_income<="250000") {
            $tax_status = "Non-taxable";
            $taxable_income = "0.00";
            $nontaxable_income = $annual_income;
        }

        // echo $lastname . " ";
        // echo $tax_status . " ";
        // echo $annual_income . "</br>";
    
        $header = "A$row:R$row";
        $objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));

        // Sequence Number
        $col = "A$row";
        $objPHPExcel->getActiveSheet()->setCellValue($col, $ctr);
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);
        
        $col = "B$row";
        $objPHPExcel->getActiveSheet()->setCellValue($col, $lastname);
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

        $col = "C$row";
        $objPHPExcel->getActiveSheet()->setCellValue($col, $firstname);
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

        $col = "D$row";
        $objPHPExcel->getActiveSheet()->setCellValue($col, $middlename);
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

        $col = "E$row";
        $objPHPExcel->getActiveSheet()->setCellValue($col, $nationality);
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

        $col = "J$row"; $value = "0.00";
        $objPHPExcel->getActiveSheet()->setCellValue($col, $value);
        $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

        // De Minimis Benefits
        $col = "K$row"; $value = "0.00";
        $objPHPExcel->getActiveSheet()->setCellValue($col, $value);
        $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

        // SSS, GSIS, PHIC, HDMF
        $col = "L$row"; $value = "0.00";
        $objPHPExcel->getActiveSheet()->setCellValue($col, $value);
        $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

        // Salaries
        $col = "M$row"; 
        $objPHPExcel->getActiveSheet()->setCellValue("M" . $row, $nontaxable_income);
        $objPHPExcel->getActiveSheet()->getStyle("M" . $row)->getNumberFormat()->setFormatCode('###,###,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle("M" . $row)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle("M" . $row)->getFont()->setSize(8);

        // Total Non-Taxable/Exempt
        $formula = "=SUM"; $formula .= "(J$row:M$row)";
        $objPHPExcel->getActiveSheet()->setCellValue("N" . $row, $formula);
        $objPHPExcel->getActiveSheet()->getStyle("N" . $row)->getNumberFormat()->setFormatCode('###,###,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle("N" . $row)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle("N" . $row)->getFont()->setSize(8);

        /* ============================================================================ */

        $col = "O$row"; $value = "0.00";
        $objPHPExcel->getActiveSheet()->setCellValue($col, $value);
        $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

        $col = "P$row"; $value = "0.00";
        $objPHPExcel->getActiveSheet()->setCellValue($col, $value);
        $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

        $col = "Q$row";
        $objPHPExcel->getActiveSheet()->setCellValue($col, $taxable_income);
        $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

        $col = "R$row"; $formula = "=SUM"; $formula .= "(O$row:Q$row)";
        $objPHPExcel->getActiveSheet()->setCellValue($col, $formula);
        $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

        $col = "I$row"; $formula = "=SUM"; $formula .= "(N$row+R$row)";
        $objPHPExcel->getActiveSheet()->setCellValue("I" . $row, $formula);
        $objPHPExcel->getActiveSheet()->getStyle("I" . $row)->getNumberFormat()->setFormatCode('###,###,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle("I" . $row)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle("I" . $row)->getFont()->setSize(8);

        $ctr++;
        $row++;
    }
}

$total_row = $row;
$last_row = $row - 1;

// Gross Compensation Income
$col = "I$total_row"; $formula = "=SUM(I6:I$last_row)";
$objPHPExcel->getActiveSheet()->setCellValue($col, $formula);
$objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

// NON-TAXABLE/EXEMPT - START
$col = "J$total_row"; $formula = "=SUM(J6:J$last_row)";
$objPHPExcel->getActiveSheet()->setCellValue($col, $formula);
$objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

$col = "K$total_row"; $formula = "=SUM(K6:K$last_row)";
$objPHPExcel->getActiveSheet()->setCellValue($col, $formula);
$objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

$col = "L$total_row"; $formula = "=SUM(L6:L$last_row)";
$objPHPExcel->getActiveSheet()->setCellValue($col, $formula);
$objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

$col = "M$total_row"; $formula = "=SUM(M6:M$last_row)";
$objPHPExcel->getActiveSheet()->setCellValue($col, $formula);
$objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

$col = "N$total_row"; $formula = "=SUM(N6:N$last_row)";
$objPHPExcel->getActiveSheet()->setCellValue($col, $formula);
$objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);
// NON-TAXABLE/EXEMPT - END

// TAXABLE - START
$col = "O$total_row"; $formula = "=SUM(O6:O$last_row)";
$objPHPExcel->getActiveSheet()->setCellValue($col, $formula);
$objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

$col = "P$total_row"; $formula = "=SUM(P6:P$last_row)";
$objPHPExcel->getActiveSheet()->setCellValue($col, $formula);
$objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

$col = "Q$total_row"; $formula = "=SUM(Q6:Q$last_row)";
$objPHPExcel->getActiveSheet()->setCellValue($col, $formula);
$objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);

$col = "R$total_row"; $formula = "=SUM(R6:R$last_row)";
$objPHPExcel->getActiveSheet()->setCellValue($col, $formula);
$objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('###,###,##0.00');
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);
// TAXABLE - END

$header = "A$row:R$row";
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));

$header = "A$row:H$row";
$objPHPExcel->getActiveSheet()->mergeCells($header);
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->getStartColor()->setRGB("e0dbdb");

$objPHPExcel->getActiveSheet()->SetCellValue("A$row", "TOTALS");
$objPHPExcel->getActiveSheet()->getStyle("A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle("A$row")->applyFromArray(array("font" => array( "bold" => true)));
$objPHPExcel->getActiveSheet()->getStyle("A$row")->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle("A$row")->getFont()->setSize(9);

$stop = $row + 1;
$col = "A".$stop;
$objPHPExcel->getActiveSheet()->SetCellValue($col, "");
$objPHPExcel->getActiveSheet()->getStyle($col)->applyFromArray(array("font" => array( "bold" => true)));
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
$objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(9);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('alphalist-2022');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Save Excel 2007 file    
$callStartTime = microtime(true);
$fileName = "alphalist_" . date('YmdHis') . ".xlsx";
$writer = new PHPExcel_Writer_Excel5($objPHPExcel);
$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$fileName.'"');
header('Cache-Control: max-age=0');
$writer->save("php://output");