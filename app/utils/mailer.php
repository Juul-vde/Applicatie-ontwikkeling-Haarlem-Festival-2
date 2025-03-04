<?php

namespace App\Utils;

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;


class Mailer
{
    function __construct()
    {
    }

    // this function (for sending email) needs editing the sender's email details
    function sendEmail($receiver, $receiver_name, $subject, $body_string, $attachment, $filename)
    {

        // SERVER SETTINGS
        $mail = new PHPMailer(true);
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'haarlem.thefestival@gmail.com';                     //SMTP username
        $mail->Password = 'wazlutaeazxvsafa';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = 587;
        //-------------------------------------------

        //Recipients (change this for every project)
        $mail->setFrom('haarlem.thefestival@gmail.com', 'Haarlem Festival');
        $mail->addAddress($receiver, $receiver_name);     //Add a recipient
        $mail->addReplyTo('haarlem.thefestival@gmail.com', 'Haarlem Festival');

        //Attachment
        if (!empty($attachment)) {
            $mail->addStringAttachment($attachment, $filename . ".pdf");
        }
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body_string;  // html

        //send the message, check for errors
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        } else {
            echo 'Message sent!';
            return true;
        }
    }
}