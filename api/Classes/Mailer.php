<?php

    use PHPMailer\PHPMailer\PHPMailer;
    include '../../libs/vendor/autoload.php';

    class Mailer {

        public function sendMail($email, $subject, $body) {
            $mail = new PHPMailer;
            $array = [];
            $mail->isSMTP();
            // $mail->SMTPDebug = 2;
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = "tesseraticketingsystem@gmail.com";
            $mail->Password = "tesseraticket";
            $mail->setFrom('tesseraticketingsystem@gmail.com', 'TESSERA TICKETING SYSTEM');
            $mail->addReplyTo('tesseraticketingsystem@gmail.com', 'Reply me');
            $mail->addAddress($email);
            $mail->Subject = $subject;
            $mail->Body = $body;
            if (!$mail->send()) {
                if($mail->ErrorInfo != 'SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting'){
                    echo "Mailer Error: " . $mail->ErrorInfo;
                } 
            } else {
                // echo "Message sent!";
            }
        }

    }