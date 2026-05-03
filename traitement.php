<?php
// Empêche l'accès direct au script
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Accès non autorisé.");
}

// Récupération et nettoyage des données envoyées par le formulaire
$server_name = strip_tags($_POST['server_name']);
$server_link = filter_var($_POST['server_link'], FILTER_VALIDATE_URL);
$server_desc = strip_tags($_POST['server_desc']);

// Vérification de base
if (!$server_link) {
    die("Lien invalide.");
}

// L'URL de votre webhook Discord (Restez discret sur cette URL)
$webhook_url = "https://discord.com/api/webhooks/1500518530876047391/votre-token-securise";

// Formatage du message envoyé dans Discord
$json_data = json_encode([
    "content" => "📢 **Nouvelle demande de partenariat !**",
    "embeds" => [
        [
            "title" => "📄 Informations sur le serveur",
            "color" => 16753920, // Couleur orange en décimal
            "fields" => [
                [
                    "name" => "Nom du serveur",
                    "value" => $server_name,
                    "inline" => false
                ],
                [
                    "name" => "Lien d'invitation",
                    "value" => $server_link,
                    "inline" => false
                ],
                [
                    "name" => "Description",
                    "value" => $server_desc,
                    "inline" => false
                ]
            ],
            "timestamp" => date("c")
        ]
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

// Envoi des données vers le webhook Discord via cURL
$ch = curl_init($webhook_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURL_INFO_HTTP_CODE);
curl_close($ch);

// Redirection ou confirmation de l'envoi
if ($status >= 200 && $status < 300) {
    header('Location: index.html?success=1');
} else {
    header('Location: index.html?error=1');
}
?>
