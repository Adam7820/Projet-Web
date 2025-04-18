<?php
require_once 'database.php';
session_start();

// Rediriger si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: profil.php');
    exit;
}

$errors = [];

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation des champs
    if (empty($email)) {
        $errors[] = "L'email est obligatoire";
    }
    
    if (empty($password)) {
        $errors[] = "Le mot de passe est obligatoire";
    }
    
    // Vérification des identifiants
    if (empty($errors)) {
        $pdo = connectToDbAndGetPdo();
        $stmt = $pdo->prepare("SELECT id, email, mot_de_passe, nom, prenom FROM utilisateurs WHERE email = ? AND statut = 'actif'");
        $stmt->execute([$email]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Connexion réussie
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];
            
            // Mettre à jour la date de dernière connexion
            $updateStmt = $pdo->prepare("UPDATE utilisateurs SET derniere_connexion = NOW() WHERE id = ?");
            $updateStmt->execute([$user['id']]);
            
            // Redirection vers la page de profil
            header('Location: profil.php');
            exit;
        } else {
            $errors[] = "Email ou mot de passe incorrect";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Jardins de France</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .btn {
            padding: 10px 15px;
            background-color: #0055a4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn:hover {
            background-color: #003d7a;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Jardins de France</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="carte_interactive.php">Carte Interactive</a>
            <a href="inscription.php">Inscription</a>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>Connexion</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="connexion.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn">Se connecter</button>
            </form>
            
            <div class="register-link">
                <p>Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Statistiques France - Données INSEE</p>
        <p><a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a></p>
    </footer>
</body>
</html>