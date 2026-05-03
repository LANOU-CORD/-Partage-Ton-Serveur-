<?php
// Désactiver l'affichage des erreurs pour ne pas casser le JSON de retour
error_reporting(0);
header('Content-Type: application/json');

// Vérifier si la requête est bien un POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $name = strip_tags($_POST['server_name']);
    $link = filter_var($_POST['server_link'], FILTER_VALIDATE_URL);
    $desc = strip_tags($_POST['server_desc']);

    if (!$link) {
        echo json_encode(['status' => 'error', 'message' => 'Lien invalide']);
        exit;
    }

    // Votre URL de webhook Discord (celle que vous m'avez partagée)
    $webhookurl = "https://discord.com/api/webhooks/1500518530876047391/t1wNqvxSbK7alxUA07fL6EzGu8r1KYOj6zY-rAM4sgkU7_lvnsszaeNdp30nZds9Ej8i";

    // Préparer le message pour Discord
    $json_data = json_encode([
        "content" => "📢 **Nouvelle demande de partenariat !**",
        "embeds" => [
            [
                "title" => "📄 Informations sur le serveur",
                "color" => 16753920, // Couleur orange
                "fields" => [
                    [
                        "name" => "Nom du serveur",
                        "value" => $name,
                        "inline" => false
                    ],
                    [
                        "name" => "Lien d'invitation",
                        "value" => $link,
                        "inline" => false
                    ],
                    [
                        "name" => "Description",
                        "value" => $desc,
                        "inline" => false
                    ]
                ],
                "timestamp" => date("c")
            ]
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    // Envoyer la requête à Discord
    $ch = curl_init($webhookurl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status >= 200 && $status < 300) {
        echo json_encode(['status' => 'success', 'message' => 'Demande envoyée avec succès !']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l’envoi à Discord.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
}
