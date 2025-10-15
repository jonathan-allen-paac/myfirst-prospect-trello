<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'mailer/PHPMailer.php';
require 'mailer/SMTP.php';
require 'mailer/Exception.php';

function mailersend($addresses,$subject,$body,$attachments) {
    global $email_from, $email_name, $smtp_host, $smtp_user, $smtp_pass, $smtp_port;
    if(count($addresses) >= 1 && $subject != "" && $body != "") {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        //Enable SMTP debugging
        //SMTP::DEBUG_OFF = off (for production use)
        //SMTP::DEBUG_CLIENT = client messages
        //SMTP::DEBUG_SERVER = client and server messages
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = SMTP_HOST;                             //Set the SMTP server to send through
        $mail->SMTPAuth   = SMTP_AUTH;                                   //Enable SMTP authentication
        if(SMTP_AUTH == true) {
            $mail->Username   = SMTP_USERNAME;                             //SMTP username
            $mail->Password   = SMTP_PASS;                             //SMTP password
        }
        //Set the encryption mechanism to use:
        // - PHPMailer::ENCRYPTION_SMTPS (implicit TLS on port 465) or
        // - PHPMailer::ENCRYPTION_STARTTLS (explicit TLS on port 587)
        if(SMTP_TLS == true) {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        $mail->Port       = SMTP_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        //process email
        try {
            //Recipients
            $mail->setFrom(EMAIL_FROM, EMAIL_NAME);
            //add recipients
            foreach ($addresses as $email) {
                $mail->addAddress($email);
            }
            $mail->addReplyTo(EMAIL_FROM, EMAIL_NAME);
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;

            if(count($attachments) >= 1) {
                foreach($attachments as $attachment) {
                    $mail->addAttachment($attachment);
                }
            }
        
            $mail->send();
            $GLOBALS['mailstatus'] = true;
        } catch (Exception $e) {
            //Email failed to send, echo error
            $GLOBALS['mailstatus'] = false;
            $GLOBALS['errmsg'] = $mail->ErrorInfo;
        }
    } else {
        $GLOBALS['mailstatus'] = false;
        $GLOBALS['errmsg'] = "Either addresses, subject or body are empty!";
    }
    
}