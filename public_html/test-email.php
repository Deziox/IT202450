<?php
    $to_email_address = 'dezioxe@gmail.com';
    $subject = "HEROKU EMAIL TEST";
    $message = "<h1>THIS IS A TEST EMAIL LOL</h1>";
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
    mail($to_email_address,$subject,$message,[$headers]);
?>