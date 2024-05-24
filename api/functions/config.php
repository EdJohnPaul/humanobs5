<?php
$vendorDir = dirname(dirname(__FILE__)) . "/vendor/"; //print $vendorDir;
$baseDir = dirname($vendorDir);
require_once $vendorDir . 'idiorm.php';
require_once $vendorDir . 'Slim/Slim.php';
require_once $vendorDir . 'assets/lib/Base32.php';
require_once $vendorDir . 'assets/lib/dompdf/dompdf_config.inc.php';
require_once $vendorDir . 'Classes/PHPExcel.php';
require_once $vendorDir .'Classes/PHPExcel/IOFactory.php';
require_once $vendorDir . 'Classes/PHPExcel/Writer/Excel2007.php';

//echo $vendorDir;