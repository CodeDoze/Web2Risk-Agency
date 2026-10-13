<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
$config = require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

// anti-spam : champ caché que seuls les robots remplissent
if (!empty($_POST['website'] ?? '')) {
    header('Location: index.html#contact');
    exit;
}

$name    = trim($_POST['name'] ?? '');
$email   = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$message = trim($_POST['message'] ?? '');

if ($name === '' || !$email || $message === '') {
    http_response_code(400);
    exit('Please fill in all fields with valid values.');
}

// empêche l'injection d'en-têtes dans le nom
$name = str_replace(["\r", "\n"], ' ', $name);

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = $config['host'];
    $mail->SMTPAuth   = $config['username'] !== '';
    $mail->Username   = $config['username'];
    $mail->Password   = $config['password'];
    $mail->SMTPSecure = $config['username'] !== '' ? PHPMailer::ENCRYPTION_STARTTLS : '';
    $mail->SMTPAutoTLS = $config['username'] !== '';
    $mail->Port       = $config['port'];
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($config['from'], 'Web2Risk Website');
    $mail->addAddress($config['to']);
    $mail->addReplyTo($email, $name);

    $mail->Subject = "New message from $name";
    $mail->Body    = "Name: $name\nEmail: $email\n\n$message";

    $mail->send();
    header('Location: index.html?mail=sent#contact');
} catch (Exception $e) {
    header('Location: index.html?mail=error#contact');
}
