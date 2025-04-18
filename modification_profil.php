<link rel="stylesheet" href="modification_profil.css">
<header>
    <h1>Jardins de France</h1>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="carte_interactive.php">Carte Interactive</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profil.php">Mon Profil</a>
            <a href="profil.php?logout=true">Déconnexion</a>
        <?php else: ?>
            <a href="connexion.php">Connexion</a>
            <a href="inscription.php">Inscription</a>
        <?php endif; ?>
    </nav>
</header>
<?php
require_once 'database.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$pdo = connectToDbAndGetPdo();
$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Si l'utilisateur n'existe pas, déconnecter et rediriger
    session_destroy();
    header('Location: connexion.php');
    exit;
}

$errors = [];
$success = false;

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation des champs
    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire";
    }
    if (empty($prenom)) {
        $errors[] = "Le prénom est obligatoire";
    }
    if (empty($email)) {
        $errors[] = "L'email est obligatoire";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format d'email invalide";
    }

    // Vérifier si l'email existe déjà (si modifié)
    if ($email !== $user['email']) {
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Cette adresse email est déjà utilisée";
        }
    }

    // Vérifier le mot de passe actuel si l'utilisateur souhaite le modifier
    if (!empty($new_password)) {
        if (empty($current_password)) {
            $errors[] = "Le mot de passe actuel est nécessaire pour définir un nouveau mot de passe";
        } elseif (!password_verify($current_password, $user['mot_de_passe'])) {
            $errors[] = "Le mot de passe actuel est incorrect";
        } elseif (strlen($new_password) < 8) {
            $errors[] = "Le nouveau mot de passe doit contenir au moins 8 caractères";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "Les nouveaux mots de passe ne correspondent pas";
        }
    }

    // Mise à jour des informations
    if (empty($errors)) {
        // Préparer la mise à jour
        $sql = "UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, telephone = ?, bio = ?";
        $params = [$nom, $prenom, $email, $telephone, $bio];

        // Ajouter le mot de passe à la mise à jour si nécessaire
        if (!empty($new_password)) {
            $sql .= ", mot_de_passe = ?";
            $params[] = password_hash($new_password, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = ?";
        $params[] = $user_id;

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($params)) {
            $success = true;
            
            // Mettre à jour les informations de session
            $_SESSION['nom'] = $nom;
            $_SESSION['prenom'] = $prenom;
            
            // Récupérer les informations mises à jour
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $errors[] = "Une erreur est survenue lors de la mise à jour du profil";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    
    <main class="container">
        <h1>Mon Profil</h1>
        
        <?php if ($success): ?>
            <div class="alert success">
                Votre profil a été mis à jour avec succès!
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="profile-form">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="5"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
            </div>
            
            <h2>Changer votre mot de passe</h2>
            <p>Laissez vide si vous ne souhaitez pas modifier votre mot de passe</p>
            
            <div class="form-group">
                <label for="current_password">Mot de passe actuel</label>
                <input type="password" id="current_password" name="current_password">
            </div>
            
            <div class="form-group">
                <label for="new_password">Nouveau mot de passe</label>
                <input type="password" id="new_password" name="new_password">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Mettre à jour mon profil</button>
            </div>
        </form>
    </main>
    
</body>
</html>