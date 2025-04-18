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

$success_message = '';
$error_message = '';

// Traitement du téléchargement de photo de profil
if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['photo_profil']['name'];
    $filetype = pathinfo($filename, PATHINFO_EXTENSION);
    
    // Vérifier l'extension du fichier
    if (in_array(strtolower($filetype), $allowed)) {
        // Créer un nom de fichier unique
        $newname = 'profile_' . $user_id . '_' . time() . '.' . $filetype;
        $target = 'uploads/' . $newname;
        
        // Créer le dossier uploads s'il n'existe pas
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }
        
        // Déplacer le fichier
        if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $target)) {
            // Mettre à jour la base de données
            $stmt = $pdo->prepare("UPDATE utilisateurs SET photo_profil = ? WHERE id = ?");
            $stmt->execute([$newname, $user_id]);
            
            // Mettre à jour l'affichage
            $user['photo_profil'] = $newname;
            $success_message = "Photo de profil mise à jour avec succès.";
        } else {
            $error_message = "Erreur lors du téléchargement de la photo.";
        }
    } else {
        $error_message = "Format de fichier non autorisé. Utilisez JPG, PNG ou GIF.";
    }
}

// Traitement de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: connexion.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil - Jardins de France</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 30px;
            border: 3px solid #0055a4;
        }
        
        .profile-info {
            flex-grow: 1;
        }
        
        .profile-name {
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        
        .profile-email {
            color: #666;
            margin: 0;
        }
        
        .profile-actions {
            margin-top: 10px;
        }
        
        .profile-actions a {
            display: inline-block;
            margin-right: 10px;
            text-decoration: none;
        }
        
        .profile-section {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .profile-section h3 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .btn {
            padding: 10px 15px;
            background-color: #0055a4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-secondary {
            background-color: #6c757d;
        }
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <header>
        <h1>Jardins de France</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="carte_interactive.php">Carte Interactive</a>
            <a href="profil.php?logout=true">Déconnexion</a>
        </nav>
    </header>

    <main>
        <div class="profile-container">
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>
            
            <div class="profile-header">
                <img src="<?= !empty($user['photo_profil']) && $user['photo_profil'] != 'default_profile.png' ? 'uploads/' . htmlspecialchars($user['photo_profil']) : 'https://via.placeholder.com/150' ?>" alt="Photo de profil" class="profile-image">
                <div class="profile-info">
                    <h2 class="profile-name"><?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?></h2>
                    <p class="profile-email"><?= htmlspecialchars($user['email']) ?></p>
                    <div class="profile-actions">
                        <a href="modification_profil.php" class="btn">Modifier le profil</a>
                        <a href="profil.php?logout=true" class="btn btn-secondary">Déconnexion</a>
                    </div>
                </div>
            </div>
            
            <div class="profile-section">
                <h3>Changer la photo de profil</h3>
                <form action="profil.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="photo_profil">Choisir une nouvelle photo</label>
                        <input type="file" id="photo_profil" name="photo_profil" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn">Mettre à jour la photo</button>
                </form>
            </div>
            
            <div class="profile-section">
                <h3>Informations personnelles</h3>
                <p><strong>Nom:</strong> <?= htmlspecialchars($user['nom']) ?></p>
                <p><strong>Prénom:</strong> <?= htmlspecialchars($user['prenom']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <?php if (!empty($user['telephone'])): ?>
                    <p><strong>Téléphone:</strong> <?= htmlspecialchars($user['telephone']) ?></p>
                <?php endif; ?>
                <?php if (!empty($user['bio'])): ?>
                    <p><strong>Bio:</strong> <?= nl2br(htmlspecialchars($user['bio'])) ?></p>
                <?php endif; ?>
                <p><strong>Membre depuis:</strong> <?= date('d/m/Y', strtotime($user['date_inscription'])) ?></p>
                
                <div style="margin-top: 20px;">
                    <a href="modification_profil.php" class="btn">Modifier les informations</a>
                    <a href="supprimer_profil.php" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte? Cette action est irréversible.')">Supprimer mon compte</a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Statistiques France - Données INSEE</p>
        <p><a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a></p>
    </footer>
</body>
</html>