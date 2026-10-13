<?php
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

$to      = getenv('MAIL_TO') ?: 'contact@web2risk.local';
$subject = mb_encode_mimeheader("New message from $name", 'UTF-8');
$body    = "Name: $name\nEmail: $email\n\n$message";
$headers = implode("\r\n", [
    'From: Web2Risk Website <website@web2risk.local>',
    'Reply-To: ' . mb_encode_mimeheader($name, 'UTF-8') . " <$email>",
    'Content-Type: text/plain; charset=UTF-8',
]);

if (mail($to, $subject, $body, $headers)) {
    header('Location: index.html?mail=sent#contact');
} else {
    header('Location: index.html?mail=error#contact');
}
