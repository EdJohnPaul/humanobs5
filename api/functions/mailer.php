<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function mail_request_to_admin($email,$empName,$loanAmount)
{
    $mail = new PHPMailer();
    try
    {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                     
        $mail->isSMTP();                                           
        $mail->Host       = 'avasiaonline.com';                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'humano.support@avasiaonline.com';                     
        $mail->Password   = 'humano2k23';                               
        $mail->SMTPSecure = 'ssl';            
        $mail->Port       = 465;                                    
    
        //Recipients
        $mail->setFrom($email);
        $mail->addAddress('humano.support@avasiaonline.com');     
        $mail->addReplyTo('humano.support@noreply.com');
    
        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Cash Advance Application';
        $mail->Body    = '<div class="container" style="height: 500px; width: 700px; background-color: #FFFFFF; margin: auto; padding: 10px; !important">
                            <div class="header" style="height: 120px; width: auto; background-color: #48d1cc; border: 0; padding: 0; !important">
                                <br>
                                <h1 class="brand" style="text-align-last: center; font-family: Arial, Helvetica, sans-serif; color: #FFFFFF;">HUMANO</h1>
                            </div>
                            <div class="email-body" style="border-top-style: solid; border-bottom-style: solid; border-width: 1pt; height: 200px; padding: 50px; !important">
                                <p style="font-family: Arial, Helvetica, sans-serif; !important">Hello <span>Admin</span>!</p>
                                <p style="font-family: Arial, Helvetica, sans-serif; text-align: justify; !important">Mr/Ms/Mrs. '.$empName.' is applying for Cash Advance Loan amounting PHP '.$loanAmount.'. Log onto your account to review his/her request through the provided link</p>
                                <br>
                                <a class="button" id="cashAdvApplyTab" href="http://apps.humano.web/app/#/cash/advance/requests/" style="background-color: #008CBA;border: none; color: white; font-size: 16px; padding: 10px 10px; text-decoration: none; border-radius: 12px;!important"> >>> To Cash Advance Application <<< </a>
                            </div>
                            <div class="footer" style="height: 50px; width: auto; background-color: #353957; border: 0; padding: 0;"></div>
                        </div>';
        $mail->AltBody = 
        $mail->send();
        // echo 'Message has been sent';
        $emailSent = 1;
    }
    catch (Exception $e) {
        // echo "Message could not be sent";
        $emailSent = 0;
        // Mailer Error: {$mail->ErrorInfo}";
    }
    // echo $emailSent;
    return $emailSent;
    
}

