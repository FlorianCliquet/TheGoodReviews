<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";

$config = json_decode(file_get_contents('../../../config.json'), true);

$smtpId = $config['smtp_id'];
$smtpPassword = $config['smtp_password'];

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.gmail.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->Username = $smtpId;
$mail->Password = $smtpPassword;

$mail->isHtml(true);

return $mail;
?>