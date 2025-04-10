<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/CSS/header.css">
    <link rel="stylesheet" href="assets/CSS/footer.css">
    <link rel="stylesheet" href="assets/CSS/index.css">
    <script src="assets/JS/script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>La mort sous toutes ses formes</title>
    
</head>

<?php
    $current_page = 'index.php';
    include_once 'partials/header.php';
?>
<?php
// Connexion à la base de données
include 'utils/database.php';

// Récupérer le nombre total de parties jouées
$stmt = $db->query("SELECT COUNT(*) AS total_games FROM Score");
$totalGames = $stmt->fetch(PDO::FETCH_ASSOC)['total_games'];

// Récupérer le nombre total de joueurs connectés
$stmt = $db->query("SELECT COUNT(DISTINCT identifiant_joueur) AS total_connected_players FROM Score WHERE date_partie >= CURDATE()");
$totalConnectedPlayers = $stmt->fetch(PDO::FETCH_ASSOC)['total_connected_players'];

// Récupérer le record (score le plus élevé)
$stmt = $db->query("SELECT MAX(score_partie) AS record FROM Score");
$record = $stmt->fetch(PDO::FETCH_ASSOC)['record'];

// Récupérer le nombre total de joueurs inscrits
$stmt = $db->query("SELECT COUNT(*) AS total_registered_players FROM Utilisateur");
$totalRegisteredPlayers = $stmt->fetch(PDO::FETCH_ASSOC)['total_registered_players'];
?>


<body>

   
    <div class="banner-container">
        <div class="banner-content">
            <h1 class="banner-title">BIENVENU DANS NOTRE STUDIO!</h1>
            <span class="banner-subtitle">Venez challenger les cerveaux les plus agiles !</span>
            <a href="games/memory.php" class="banner-button">Jouer !</a>
        </div>
    </div>



    <main>
    <div class="container">
        <!-- Section 1 : Caractéristiques -->
        <section class="features-section">
            <article class="feature-item">
                <div class="feature-image-container">
                    <img src="https://media.discordapp.net/attachments/1189142906234208327/1307777917127884820/image.png?ex=673b8a98&is=673a3918&hm=74231f44d131f622525bc83c527600a41c23db65285b11e68295847c9d1c4b57&=&format=webp&quality=lossless&width=1100&height=618" alt="Défiez votre mémoire">
                </div>
                <div class="feature-text-container">
                    <h2 class="feature-title">Explorez les Profondeurs de Votre Mémoire et Testez Vos Limites Cognitives</h2>
                    <p class="feature-description">Plongez dans un monde fascinant où chaque détail a son importance ! "The Power of Memory" vous propose des défis passionnants pour tester et améliorer vos compétences de mémorisation. Vous serez confronté à une série d'épreuves variées qui mettront votre mémoire à l'épreuve dans des contextes différents et stimulants. Êtes-vous prêt à relever ce défi ? Développez vos capacités cognitives et poussez vos limites pour devenir le maître de votre propre mémoire.</p>
                </div>
            </article>
            <article class="feature-item">
                <div class="feature-image-container">
                    <img src="https://media.discordapp.net/attachments/1189142906234208327/1307779812496113735/photo-1575291786213-f932b9d57c25.png?ex=673b8c5c&is=673a3adc&hm=73f244039666cbbc6e67ecd1c683f74246f49bd88e57e82ea274178638147280&=&format=webp&quality=lossless&width=782&height=978" alt="Explorez des niveaux">
                </div>
                <div class="feature-text-container">
                    <h2 class="feature-title">Explorez des Niveaux Uniques</h2>
                    <p class="feature-description">Dans "The Power of Memory," chaque niveau mettra à l'épreuve votre capacité à retenir des séquences, des formes et des couleurs. Au début de chaque niveau, vous aurez quelques secondes pour mémoriser l'ordre des éléments. Ensuite, il faudra les replacer dans le bon ordre pour avancer. Plus vous progressez, plus le défi devient complexe et les séquences, difficiles à retenir.</p>
                </div>
            </article>
            <article class="feature-item">
                <div class="feature-image-container">
                    <img src="https://media.discordapp.net/attachments/1189142906234208327/1307780216927555644/photo-1652197881268-d625ad54402b.png?ex=673b8cbd&is=673a3b3d&hm=bc87122942f2d814f31a4ad954ebdbb8c14770ae604394c8fc9c7c49f6e24fc1&=&format=webp&quality=lossless&width=524&height=700" alt="Entraînez-vous et progressez">
                </div>
                <div class="feature-text-container">
                    <h2 class="feature-title">Entraînez-vous et Progressez</h2>
                    <p class="feature-description">Rejouez pour améliorer votre score, débloquez de nouvelles récompenses et voyez à quel point votre mémoire peut s'améliorer. Avec "The Power of Memory", chaque partie est une nouvelle chance d'apprendre et de progresser.</p>
                </div>
            </article>
        </section>

<!-- Section 2 : Statistiques -->
        <section class="stats-section">
            <img class="image_jeux" src="https://media.discordapp.net/attachments/1300455427380740247/1300810888508604556/Capture_decran_2024-10-29_a_14.17.58.png?ex=673be74b&is=673a95cb&hm=e586eb2eaf5cac5510c544ac709d9f024e33026deb962f4f04ad00e13c171674&=&format=webp&quality=lossless&width=1050&height=700" alt="Image jeu">
            <div class="stat-container">
                <div class="stat-item">
                    <span class="stat-value"><?php echo $totalGames; ?></span>
                    <p class="stat-label">Parties jouées</p>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo $totalConnectedPlayers; ?></span>
                    <p class="stat-label">Joueurs Connectés</p>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo $record . "s"; ?></span>
                    <p class="stat-label">Record</p>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo $totalRegisteredPlayers; ?></span>
                    <p class="stat-label">Joueurs Inscrits</p>
                </div>
            </div>
        </section>


<!-- Section 3 : Équipe -->
        <section class="team-section">
            <h3 class="team-title">Notre équipe</h3>
            <img class="fleurdelis" src="https://cdn.discordapp.com/attachments/1189142906234208327/1307786912286769303/image.png?ex=673b92f9&is=673a4179&hm=a1648da0f75aaaf380329c13f3fa6067421c2aff421fb18e71515a2aba6b769b&" alt="Explorez des niveaux">
            <div class="team-member">
                <div class="member-box">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTbPjK4pTCxMg3i_Ms-qGGXs9GYxA2Tr7fRlg&s" alt="Mathéo" class="team-member-img">
                    <h4 class="team-member-name">Mathéo</h4>
                    <span class="team-role">Games Developer</span>
                </div>
            </div>

            <div class="team-member">
                <div class="member-box">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR4Ay0NKAJZVtaQHtJYhNmNgjwdmyqQnSXUekVZZxviBg3SvIf1PB8-rps&s" alt="Elias" class="team-member-img">
                    <h4 class="team-member-name">Elias</h4>
                    <span class="team-role">Game Designer</span>
                </div>
            </div>

            <div class="team-member">
                <div class="member-box">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTI3RCcM42bLnCSQpWHQMKL9z8toohIjOM9lteWW69U_JmI8BBPG2U1__c&s" alt="Adam" class="team-member-img">
                    <h4 class="team-member-name">Adam</h4>
                    <span class="team-role">Game Developer</span>
                </div>
            </div>
            <div class="team-member">
                <div class="member-box">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTI3RCcM42bLnCSQpWHQMKL9z8toohIjOM9lteWW69U_JmI8BBPG2U1__c&s" alt="Adam" class="team-member-img">
                    <h4 class="team-member-name">Noufel</h4>
                    <span class="team-role">Game Developer</span>
                </div>
            </div>
            
        </section>

    </div>
</main>

<?php
    include 'partials/footer.php';
?>
</body>
</html>