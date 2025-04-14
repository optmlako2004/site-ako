<?php

// En haut du fichier admin.php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Créez un système d'authentification simple dans login.php

require 'connexion.php'; // Créez ce fichier avec la configuration PDO

$messages = $connexion->query("SELECT * FROM messages ORDER BY date_envoi DESC")->fetchAll();
?>

<table class="w-full">
    <thead>
        <tr>
            <th class="p-2">Date</th>
            <th class="p-2">Nom</th>
            <th class="p-2">Email</th>
            <th class="p-2">Message</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($messages as $msg): ?>
        <tr class="border-b">
            <td class="p-2"><?= date('d/m/Y H:i', strtotime($msg['date_envoi'])) ?></td>
            <td class="p-2"><?= htmlspecialchars($msg['nom']) ?></td>
            <td class="p-2"><?= htmlspecialchars($msg['email']) ?></td>
            <td class="p-2"><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
