<?php
declare(strict_types=1);
$config = require __DIR__ . '/config.php';
function page(string $title, string $message, bool $success = false): never {
    $color = $success ? '#18794e' : '#b42318';
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.$safeTitle.' — LIGOZZA</title><style>body{margin:0;min-height:100vh;display:grid;place-items:center;background:#f5f5f5;color:#111;font-family:Arial,sans-serif}.box{max-width:520px;margin:24px;background:white;border-radius:20px;padding:36px;box-shadow:0 12px 40px #0001}h1{letter-spacing:3px}p{line-height:1.6;color:#444}.status{color:'.$color.';font-weight:700}a{display:inline-block;margin-top:14px;padding:12px 18px;background:#111;color:white;text-decoration:none;border-radius:8px}</style></head><body><main class="box"><h1>LIGOZZA</h1><p class="status">'.$safeTitle.'</p><p>'.$safeMessage.'</p><a href="index.html">Back to LIGOZZA</a></main></body></html>';
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.html'); exit; }
// Honeypot anti-spam field: bots often fill this hidden input.
if (!empty($_POST['website'])) page('Registration could not be completed', 'Please return to the website and try again.');
$name = trim((string)($_POST['name'] ?? ''));
$email = strtolower(trim((string)($_POST['email'] ?? '')));
if ($name === '' || mb_strlen($name) > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) {
    page('Check your details', 'Enter your name and a valid email address, then try again.');
}
if ($config['db_name'] === 'YOUR_DATABASE_NAME' || $config['db_user'] === 'YOUR_DATABASE_USER') {
    page('Setup required', 'The website owner needs to add the hosting database details to config.php before registration can be activated.');
}
try {
    $dsn = 'mysql:host='.$config['db_host'].';dbname='.$config['db_name'].';charset=utf8mb4';
    $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]);
    $stmt = $pdo->prepare('INSERT INTO ligozza_registrations (full_name, email) VALUES (:name, :email)');
    $stmt->execute([':name' => $name, ':email' => $email]);
    $id = (int)$pdo->lastInsertId();
    $memberId = '#' . $id;
} catch (PDOException $e) {
    // MySQL duplicate-key error means this email has already registered.
    if (($e->errorInfo[1] ?? null) == 1062) page('Email already registered', 'This email address has already joined LIGOZZA. Each email can register only once. If you think this is a mistake, contact the store owner.');
    error_log('LIGOZZA registration database error: ' . $e->getMessage());
    page('Temporarily unavailable', 'Registration could not be saved right now. Please try again later.');
}
$admin = $config['admin_email'];
$from = $config['from_email'];
$subjectAdmin = 'New LIGOZZA registration ' . $memberId;
$bodyAdmin = "A new LIGOZZA Join With Us registration was created.\n\nRegistration ID: {$memberId}\nName: {$name}\nEmail: {$email}\nRegistered at: " . date(DATE_ATOM) . "\n";
$subjectUser = 'Your LIGOZZA registration ID ' . $memberId;
$bodyUser = "Hi {$name},\n\nThanks for joining LIGOZZA!\n\nYour unique registration ID is: {$memberId}\n\nKeep this ID for your reference.\n\nLIGOZZA Team\n";
$headers = "From: {$config['site_name']} <{$from}>\r\n" . "Content-Type: text/plain; charset=UTF-8\r\n";
// The database record and unique ID are saved before emails are attempted.
$adminSent = filter_var($admin, FILTER_VALIDATE_EMAIL) && mail($admin, $subjectAdmin, $bodyAdmin, $headers);
$userSent = mail($email, $subjectUser, $bodyUser, $headers);
if (!$adminSent || !$userSent) error_log('LIGOZZA registration saved as '.$memberId.' but one or more emails could not be sent. Check hosting mail configuration.');
page('Welcome to LIGOZZA!', 'Your registration ID is ' . $memberId . '. Your registration has been saved. An email is being sent to your inbox; if it does not arrive, check Spam/Junk or contact the store owner.', true);
