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

/* Header - Start */

$letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
$columns = ["#", "Employee No.", "Lastname", "Firstname", "Middlename", "Nationality", "Gender", "Marital Status", "Birthdate", "Email Address", "Address", "SSS", "PHIC", "HDMF", "TIN", "Date Hired"];

$col_ctr = 0;
for($i=0;$i<count($columns);$i++) {
    $row = "1"; $col = $letters[$i].$row; $value = $columns[$i]; $width = strlen($value); if($width<=2){$width = 5;}else if($width>=3&&$width<15) {$width=10;} $letter = $letters[$i];
    $objPHPExcel->getActiveSheet()->SetCellValue($col, $value);
    $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle($col)->applyFromArray(array("font" => array( "bold" => true)));
    $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
    $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);
    $objPHPExcel->getActiveSheet()->getColumnDimension($letters[$i])->setWidth($width);
    $col_ctr++;
}
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

$first_letter = "A";
$last_letter = $letter;

$header = "$first_letter$row:$last_letter$row";
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle($header)->getFill()->getStartColor()->setRGB("e0dbdb");
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle($header)->getBorders()->getAllBorders()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));

/* Header - End */

/* Body - Start */
$row = 2;
$ctr = 1;
$results = getActiveEmployees();
foreach($results as $result) {
    $annual_income = 0;
    $tax_status = null;
    $taxable_income = 0;
    $nontaxable_income = 0;
    $emp_uid = $result->emp_uid;
    $emp_number = get_employee_number_by_emp_uid($emp_uid); $emp_number = STR_PAD($emp_number, 4, "0");
    $name = read_employee_name_by_uid($emp_uid);
    $lastname = $result->lastname; 
    $firstname = $result->firstname;
    $middlename = $result->middlename;
    $nationality = $result->nationality; if(empty($nationality)||$nationality==="") {$nationality="N/A";}
    $address = $result->house_number . " " . $result->barangay . " " . $result->city . " " . $result->region;

    $columns = [$ctr, $emp_number, $lastname, $firstname, $middlename, $nationality, $result->gender, $result->marital, $result->bday, $result->email, $address, $result->sss_no, $result->philhealth_no, $result->pagibig_no, $result->tax_no, 'N/A'];

    $col_ctr = 0;
    for($i=0;$i<count($columns);$i++) {
        $col = $letters[$i].$row; $value = $columns[$i]; $width = strlen($value); if($width<=5){$width = 10;}else if($width>=6&&$width<15) {$width=20;} $letter = $letters[$i];
        $objPHPExcel->getActiveSheet()->SetCellValue($col, $value);
        //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setName("Verdana");
        $objPHPExcel->getActiveSheet()->getStyle($col)->getFont()->setSize(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension($letters[$i])->setWidth($width);
        $col_ctr++;
    }
    $ctr++;
    $row++;
}
/* Body - End */

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Masterfile');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Save Excel 2007 file    
$callStartTime = microtime(true);
$fileName = "masterfile_" . date('YmdHis') . ".xlsx";
$writer = new PHPExcel_Writer_Excel5($objPHPExcel);
$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$fileName.'"');
header('Cache-Control: max-age=0');
$writer->save("php://output");