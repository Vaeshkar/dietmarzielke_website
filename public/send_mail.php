<?php
header('Content-Type: application/json; charset=utf-8');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Methode nicht erlaubt.']);
    exit;
}

// Retrieve and sanitize fields
$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';
$consent = isset($_POST['consent']) ? $_POST['consent'] : '';

// Validation
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Bitte füllen Sie alle Pflichtfelder aus.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.']);
    exit;
}

if ($consent !== 'yes') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Bitte akzeptieren Sie die Datenschutzerklärung.']);
    exit;
}

// Recipient email address
$to = 'zielke@betreuungen-zielke.de';
$subject = 'Neue Kontaktanfrage von der Website';

// Construct email headers
// Prevent header injection by removing newlines from user input in headers
$name_clean = str_replace(array("\r", "\n"), '', $name);
$email_clean = str_replace(array("\r", "\n"), '', $email);

$headers = [
    'MIME-Version: 1.0',
    'Content-type: text/plain; charset=utf-8',
    'From: website-formular@betreuungen-zielke.de',
    'Reply-To: ' . $email_clean,
    'X-Mailer: PHP/' . phpversion()
];

// Construct email body
$email_body = "Sie haben eine neue Nachricht über das Kontaktformular erhalten:\n\n";
$email_body .= "Name: " . $name_clean . "\n";
$email_body .= "E-Mail: " . $email_clean . "\n\n";
$email_body .= "Nachricht:\n" . $message . "\n";

// Send the email
if (mail($to, $subject, $email_body, implode("\r\n", $headers))) {
    echo json_encode(['success' => true, 'message' => 'Vielen Dank! Ihre Nachricht wurde erfolgreich gesendet.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Fehler beim Senden der E-Mail. Bitte versuchen Sie es später noch einmal.']);
}
