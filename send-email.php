

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Assurez-vous que le contenu reçu est au format JSON
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {
    // Recevoir le contenu brut POSTé
    $content = trim(file_get_contents("php://input"));

    // Décoder le JSON reçu
    $decoded = json_decode($content, true);

    // Validation simple des données (à adapter selon vos besoins)
    if(is_array($decoded)) {
        try {
            // Récupération des données
            $fullName = $decoded['fullName'];
            $ratings = $decoded['ratings'];
            $averageRating = $decoded['averageRating'];
            $dateEnvoi = date("Y-m-d H:i:s"); // Format de date et heure

            // Construction du contenu de l'email
            $contenu = "Date d'envoie: $dateEnvoi<br>";
            // Construction du contenu de l'email
            $contenu .= "Nom Complet: $fullName<br>";
            foreach($ratings as $rating) {
                $contenu .= "Question " . $rating['question'] . ": " . $rating['rating'] . " étoiles<br>";
            }
            $contenu .= "<br>Moyenne des évaluations: $averageRating";

            // Appel de la fonction d'envoi d'email
            sendEmail($contenu);

            echo "Email envoyé avec succès.";

        } catch(Exception $e) {
            // Gestion des erreurs
            http_response_code(500);
            echo "Erreur lors de l'envoi de l'email : " . $e->getMessage();
        }
    } else {
        // Les données reçues ne sont pas un tableau
        http_response_code(400); // Bad Request
        echo "Erreur dans les données reçues.";
    }
} else {
    http_response_code(415); // Unsupported Media Type
    echo "Type de contenu non supporté.";
}

function sendEmail($contenu) {
    $emailDestinataire='azoulaye42@gmail.com';
    $nomDestinataire = 'eden';
    $objetMail= 'Résultat de l\'enquête Vizit déménagement';

    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'spotifone36@gmail.com';
        $mail->Password = 'xsmtpsib-9da7a948e35fd054aba8e1f495223d2985e1ebf1ec6a1297d80a7a0b557cae2a-Qz173PcadtUHZ2jC';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('contact@spotifone.com', 'Vizit Déménagement');
        $mail->addAddress($emailDestinataire, $nomDestinataire);

                // Spécifiez l'encodage du contenu de l'email
                $mail->CharSet = 'UTF-8';

        $mail->isHTML(true);
        $mail->Subject = $objetMail;
        $mail->Body = $contenu;

        $mail->send();

        return true;

    } catch (Exception $e) {
        throw new Exception("Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}");
    }
}

?>
