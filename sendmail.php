<?php
header('Content-Type: application/json');

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Récupérer les données du formulaire
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Valider les données
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Tous les champs sont obligatoires']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Adresse email invalide']);
    exit;
}

// Configuration PHPMailer
require 'C:/wamp64/www/portfolio_v/vendor/autoload.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    // Paramètres du serveur
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Remplacez par votre serveur SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'sergie.king5@gmail.com'; // Remplacez par votre email
    $mail->Password = 'kkkc wohf sqtg uhqc'; // Remplacez par votre mot de passe
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    // Destinataires
    $mail->setFrom($email, $name);
    $mail->addAddress('sergie.king5@gmail.com'); // Votre adresse email

    // Contenu
    $mail->isHTML(true);
    $mail->Subject = 'Nouveau message depuis votre portfolio: ' . $subject;
    $mail->Body    = "
        <h1>Nouveau message de contact</h1>
        <p><strong>Nom:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Sujet:</strong> {$subject}</p>
        <p><strong>Message:</strong></p>
        <p>{$message}</p>
    ";
    $mail->AltBody = "Nom: {$name}\nEmail: {$email}\nSujet: {$subject}\nMessage:\n{$message}";

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Votre message a été envoyé avec succès. Je vous répondrai dès que possible.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => "Le message n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}"]);
}