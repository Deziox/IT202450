<?php
    require_once("../PHPMailer/PHPMailerAutoload.php");
    //aestheticushelp@gmail.com
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = '465';
    $mail->isHTML();
    $mail->Username = 'aestheticushelp@gmail.com';
    $mail->Password = '@$sa4G<KU_aLN&\'97';
    $mail->SetFrom('no-reply@aestheticus.com');
    $mail->Subject = 'Test email from aestheticus';
    $mail->Body = '<html><h1>THIS IS A TEST EMAIL, no memes</h1></html>';
    $mail->AddAddress('dezioxe@gmail.com');

    $mail->send();
?>