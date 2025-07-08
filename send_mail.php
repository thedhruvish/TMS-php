<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

require_once "./config.php";

//Create an instance; passing `true` enables exceptions
function send_otp_mail($email, $otp, $message)
{

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = HOST;                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = USERNAME;                     //SMTP username
        $mail->Password   = PASSWORD;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom(MAIL_ID, 'TMS system');
        $mail->addAddress($email, 'User');     //Add a recipient

        $otp_templest =  '<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <link
      rel="preload"
      as="image"
      href="https://react-email-demo-mros9ph6b-resend.vercel.app/static/plaid-logo.png" />
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <meta name="x-apple-disable-message-reformatting" />
    <!--$-->
  </head>
  <body
    style="background-color:#ffffff;font-family:HelveticaNeue,Helvetica,Arial,sans-serif">
    <table
      align="center"
      width="100%"
      border="0"
      cellpadding="0"
      cellspacing="0"
      role="presentation"
      style="max-width:360px;background-color:#ffffff;border:1px solid #eee;border-radius:5px;box-shadow:0 5px 10px rgba(20,50,70,.2);margin-top:20px;margin:0 auto;padding:68px 0 130px">
      <tbody>
        <tr style="width:100%">
          <td>
            <img
              alt="Plaid"
              height="88"
              src="https://react-email-demo-mros9ph6b-resend.vercel.app/static/plaid-logo.png"
              style="display:block;outline:none;border:none;text-decoration:none;margin:0 auto"
              width="212" />
            <p
              style="font-size:11px;line-height:16px;color:#0a85ea;font-weight:700;font-family:HelveticaNeue,Helvetica,Arial,sans-serif;height:16px;letter-spacing:0;margin:16px 8px 8px 8px;text-transform:uppercase;text-align:center;margin-top:16px;margin-right:8px;margin-bottom:8px;margin-left:8px">
              Verify Your Identity
            </p>
            <h1
              style="color:#000;display:inline-block;font-family:HelveticaNeue-Medium,Helvetica,Arial,sans-serif;font-size:20px;font-weight:500;line-height:24px;margin-bottom:0;margin-top:0;text-align:center">
              Enter the following code to ' . $message . '
            </h1>
            <table
              align="center"
              width="100%"
              border="0"
              cellpadding="0"
              cellspacing="0"
              role="presentation"
              style="background:rgba(0,0,0,.05);border-radius:4px;margin:16px auto 14px;vertical-align:middle;width:280px">
              <tbody>
                <tr>
                  <td>
                    <p
                      style="font-size:32px;line-height:40px;color:#000;font-family:HelveticaNeue-Bold;font-weight:700;letter-spacing:6px;padding-bottom:8px;padding-top:8px;margin:0 auto;display:block;text-align:center;margin-top:0;margin-right:auto;margin-bottom:0;margin-left:auto">
                      ' . $otp . '
                    </p>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table> 
    </body>
    </html>
        ';
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'OTP verification for ' . $message;
        $mail->Body    = $otp_templest;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
