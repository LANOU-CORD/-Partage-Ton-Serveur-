<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Accès non autorisé.");
}

$server_name = strip_tags($_POST['server_name']);
$server_link = filter_var($_POST['server_link'], FILTER_VALIDATE_URL);
$server_desc = strip_tags($_POST['server_desc']);

if (!$server_link) {
    die("Lien d'invitation invalide.");
}

// ⚠️ REMPLACEZ CETTE LIGNE PAR VOTRE VRAI WEBHOOK SECRÉTISÉ 
$webhook_url = "https://discord.com/api/webhooks/1500518530876047391/t1wNqvxSbK7alxUA07fL6EzGu8r1KYOj6zY-rAM4sgkU7_lvnsszaeNdp30nZds9Ej8i";

$json_data = json_encode([
    "content" => "📢 **Nouvelle demande de partenariat !**",
    "embeds" => [
        [
            "title" => "📄 Informations sur le serveur",
            "color" => 16753920,
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

if ($status >= 200 && $status < 300) {
    header('Location: index.html?success=1#partenariat');
} else {
    header('Location: index.html?error=1#partenariat');
}
?>
