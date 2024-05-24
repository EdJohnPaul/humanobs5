<?php
  
  require_once("vendor/assets/lib/dompdf/dompdf_config.inc.php");
  require_once "functions.loader.php";

  $uid = $_POST['empuid'];
  $applyNo = $_POST['applyNo'];
  $soaData = cash_advance_soa($uid,$applyNo); 
  $employees = read_employee_name_by_uid($uid);
  $tableData = loop_SOAData($soaData);

  $html = '<html>
  <style>
    body{
        font-family: Arial, Helvetica, sans-serif;
    }
    label{
        font-weight: bold;
    }
    .header{
        text-align: center;
        margin: 5px,1px,1px,1px;
    }
    #dateGroup{
        position: absolute;
        right: 0px;
        font-size: 11pt;
    }
    #empNameGroup{
        position: absolute;
        left: 0px;
        font-size: 11pt;
    }
    #applicationNoGroup{
        position: absolute;
        left: 0px;
        top: 220px;
        font-size: 11pt;
    }
    #amountGroup{
        position: absolute;
        right: 0px;
        top: 220px;
        font-size: 11pt;
    }
    .rowtop{
        margin-bottom: 20px:
    }
    #tableSOA{
        width: 100%;
        border-collapse: collapse;
        text-align: center;
        font-size: 11pt;
    }
    #tableSOA th {
        padding-top: 12px;
        padding-bottom: 12px;
    }
    #tableSOA td {
        padding-top: 5px;
        padding-bottom: 5px;
    }
    #address{
        font-size: 10pt;
    }
    #contacts{
        font-size: 10pt;
        margin-bottom: 40px;
    }
    #subtitle{
        margin-bottom: 20px;
    }
    @page { 
        margin-top: 1.5in;
        margin-bottom: 1in;
        margin-left: 1in;
        margin-right: 1in;
    }
  </style>
  <body>
    <div class="box" id="SOAReport" name="soa">
        <div class="row">
            <div class="col-md-12">
                <h3 class="header">AVASIA INFORMATION SYSTEMS INC.</h3>
                <p class="header" id="address">Unit 2 5/F Bloomingdale Plaza, Shaw Blvd. Brgy. Kapitolyo, Pasig City, Metro Manila</p>
                <p class ="header" id="contacts">(+63) 2 8671-0072 || sales@avasiaonline.com</p>
                <h4 class="header" id="title">Statement of Accounts</h4>
                <h4 class="header" id="subtitle">Cash Advance</h4>
                <br>
            </div>
        </div>
        <div class="rowtop">
            <div class="empNameGroup" id="empNameGroup">
            <label for="">Employee Name: </label><span id="empName">'.$employees.' </span>
            </div>
            <div class="dateGroup" id="dateGroup">
                <label>Date: </label><span id="currentDate">'.date("F d, Y").'</span>
            </div>
            <div class="applicationNoGroup" id="applicationNoGroup">
                <label for="">Application No:</label>
                <span>'.$soaData["payments"][0]['applicationNo'].'</span>
            </div>
            <div class="amountGroup" id="amountGroup">
                <label for="">Amount: </label>
                <span id="loanAmount">'.'PHP '.$soaData['loanGranted'].'</span>
            </div>
        </div>
        <br>
        <br>
        <br>
        <hr>
        <br>
        <div class="soaTable">
            <table id="tableSOA">
                <thead>
                    <tr>
                        <th>Date Posted</th>
                        <th>Reference No.</th>
                        <th>Amortization</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody id="loadData">
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold;">'.number_format($soaData['loanGranted'],2).'</td>
                    </tr>'.loop_SOAData($soaData).'
                    <tr colspan=4>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Total</td>
                        <td></td>
                        <td style="font-weight: bold;">'.number_format(totalAmort($soaData),2).'</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>   
    </div>
  </body>
  </html>';

if ( get_magic_quotes_gpc() )
$html = stripslashes($html);
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("CashAdvanceSOA.pdf", array("Attachment" => false));
exit(0);

function loop_SOAData($soaData)
{
    $table = null;
    $total = 0;
    foreach($soaData["payments"] as $result) {
        $date = date('m/d/Y',strtotime($result["datePosted"]));
        $table .='<tr>
                <td>'.$date.'</td>
                <td>'.$result["referNo"].'</td>
                <td>'.number_format($result["amountPaid"],2).'</td>
                <td>'.number_format($soaData["loanGranted"]-=$soaData["amortization"],2).'</td>
                </tr>';
    }
    return $table ;
}
function totalAmort($soaData)
{
    $total = 0;
    foreach($soaData["payments"] as $result) {
        $total += $result["amountPaid"]; 
    }
    return $total;
}
?>

        
        
      

