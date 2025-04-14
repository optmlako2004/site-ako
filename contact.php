<?php
// Configuration
$serveur = "localhost";
$utilisateur = "root"; // À remplacer par votre utilisateur
$motdepasse = ""; // À remplacer par votre mot de passe
$basededonnees = "contact";

// Connexion sécurisée avec PDO
try {
    $connexion = new PDO(
        "mysql:host=$serveur;dbname=$basededonnees;charset=utf8",
        $utilisateur,
        $motdepasse,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} // Avant
catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Après
catch(PDOException $e) {
    error_log('Erreur SQL : '.$e->getMessage());
    die("Erreur de connexion : " . $e->getMessage());
}


// Après la connexion PDO
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/error.log');



// Traitement du formulaire
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nettoyage
    $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Validation
    if (empty($nom)) {
        $errors['nom'] = "Le nom est obligatoire";
    } elseif (strlen($nom) > 50) {
        $errors['nom'] = "Maximum 50 caractères";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email invalide";
    } elseif (strlen($email) > 100) {
        $errors['email'] = "Maximum 100 caractères";
    }

    if (empty($message)) {
        $errors['message'] = "Message obligatoire";
    } elseif (strlen($message) > 1000) {
        $errors['message'] = "Maximum 1000 caractères";
    }

    // Insertion en base
    if (empty($errors)) {
        try {
            $requete = $connexion->prepare(
                "INSERT INTO messages (nom, email, message) 
                VALUES (:nom, :email, :message)"
            );
            
            $requete->execute([
                ':nom' => $nom,
                ':email' => $email,
                ':message' => $message
            ]);
            
            $success = true;
        } catch(PDOException $e) {
            $errors['bdd'] = "Erreur technique : " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Christian Ako</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .error { color: #dc2626; font-size: 0.875rem; }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">
    <!-- Header identique à votre version précédente -->
    
    <main class="p-10 text-left">
        <h2 class="text-3xl font-bold">Me Contacter</h2>

        <?php if($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded my-4">
                Message enregistré avec succès !
            </div>
        <?php elseif(!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded my-4">
                <?php foreach($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        

        <form method="post" class="mt-4">
            <div class="mb-4">
                <label class="block font-medium">Nom :</label>
                <input type="text" name="nom" 
                    value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" 
                    class="w-full p-2 border <?= isset($errors['nom']) ? 'border-red-500' : 'border-gray-300' ?> rounded">
                <?php if(isset($errors['nom'])): ?>
                    <span class="error"><?= $errors['nom'] ?></span>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block font-medium">Email :</label>
                <input type="email" name="email" 
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                    class="w-full p-2 border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?> rounded">
                <?php if(isset($errors['email'])): ?>
                    <span class="error"><?= $errors['email'] ?></span>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block font-medium">Message :</label>
                <textarea name="message" 
                    class="w-full p-2 border <?= isset($errors['message']) ? 'border-red-500' : 'border-gray-300' ?> rounded" 
                    rows="5"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                <?php if(isset($errors['message'])): ?>
                    <span class="error"><?= $errors['message'] ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="mt-4 bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-700">
                Envoyer
            </button>
        </form>
    </main>
</body>
</html>
