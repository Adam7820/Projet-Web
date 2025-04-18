<?php
require_once 'database.php';
$db = connectToDbAndGetPdo();

// Récupération des paramètres de recherche
$recherche = isset($_GET['recherche']) ? $_GET['recherche'] : '';
$region = isset($_GET['region']) ? $_GET['region'] : '';
$departement = isset($_GET['departement']) ? $_GET['departement'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

// Construction de la requête SQL pour rechercher dans la table existante
$sql = "SELECT * FROM liste_des_jardins_remarquables WHERE 1=1";
$params = [];

if (!empty($recherche)) {
    $sql .= " AND (`Nom du jardin` LIKE :recherche OR Commune LIKE :recherche OR Description LIKE :recherche)";
    $params['recherche'] = "%$recherche%";
}

if (!empty($region)) {
    $sql .= " AND Region = :region";
    $params['region'] = $region;

}

if (!empty($departement)) {
    $sql .= " AND Departement = :departement";
    $params['departement'] = $departement;
}

if (!empty($type)) {
    $sql .= " AND Types LIKE :type";
    $params['type'] = "%$type%";
}

// Exécution de la requête
$query = $db->prepare($sql);
$query->execute($params);
$jardins = $query->fetchAll(PDO::FETCH_ASSOC);

// Récupération des régions pour le filtre
$regionsQuery = $db->query("SELECT DISTINCT Region FROM liste_des_jardins_remarquables ORDER BY Region");
$regions = $regionsQuery->fetchAll(PDO::FETCH_COLUMN);

// Récupération des départements pour le filtre
$departementsQuery = $db->query("SELECT DISTINCT Departement FROM liste_des_jardins_remarquables ORDER BY Departement");
$departements = $departementsQuery->fetchAll(PDO::FETCH_COLUMN);

// Récupération des types de jardins
$typesQuery = $db->query("SELECT DISTINCT Types FROM liste_des_jardins_remarquables ORDER BY Types");
$typesJardins = [];
while ($row = $typesQuery->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($row['Types'])) {
        $types = explode(',', $row['Types']);
        foreach ($types as $t) {
            $t = trim($t);
            if (!empty($t) && !in_array($t, $typesJardins)) {
                $typesJardins[] = $t;
            }
        }
    }
}
sort($typesJardins);

// Cette partie est pour récupérer les prénom si vous avez cette table
$topPrenoms = [];
try {
    $query = $db->prepare("
        SELECT departement, prenom, nombre 
        FROM top_prenoms 
        WHERE annee = :annee
        GROUP BY departement
        ORDER BY nombre DESC
    ");
    $query->execute(['annee' => isset($_GET['annee']) ? $_GET['annee'] : 2023]);
    
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $topPrenoms[$row['departement']] = [
            'prenom' => $row['prenom'],
            'nombre' => $row['nombre']
        ];
    }
} catch (PDOException $e) {
    // La table n'existe pas encore ou erreur de requête
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Carte Interactive - Jardins de France</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="index.css">
  <link rel="stylesheet" href="carte_interactive.css">
  <style>
    #map {
      height: 500px;
      width: 100%;
      border-radius: 10px;
      margin-top: 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .search-filter {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .search-filter form {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr 1fr auto;
      gap: 10px;
    }
    @media (max-width: 768px) {
      .search-filter form {
        grid-template-columns: 1fr;
      }
    }
    .search-filter input, .search-filter select {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    .search-filter button {
      padding: 10px 15px;
      background-color: #0055a4;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .search-filter button:hover {
      background-color: #003d7a;
    }
    .jardins-list {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    .jardin-item {
      background-color: white;
      border-radius: 10px;
      padding: 15px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .jardin-item h3 {
      margin-top: 0;
      color: #0055a4;
    }
    .jardin-location {
      display: flex;
      align-items: center;
      margin: 10px 0;
      color: #666;
    }
    .jardin-location svg {
      margin-right: 5px;
    }
    .jardin-description {
      margin-top: 10px;
      font-size: 0.9rem;
      line-height: 1.4;
    }
    .jardin-types {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
      margin-top: 10px;
    }
    .jardin-type {
      background-color: #e6f1ff;
      padding: 3px 8px;
      border-radius: 12px;
      font-size: 0.8rem;
      color: #0055a4;
    }
    .results-info {
      margin: 20px 0;
      padding: 10px 15px;
      background-color: #f8f9fa;
      border-left: 4px solid #0055a4;
      border-radius: 4px;
    }
    .view-on-map {
      display: inline-block;
      margin-top: 10px;
      padding: 5px 10px;
      background-color: #f1f1f1;
      border-radius: 4px;
      text-decoration: none;
      color: #333;
      font-size: 0.9rem;
    }
    .view-on-map:hover {
      background-color: #e0e0e0;
    }
  </style>
</head>
<body>
  <header>
    <h1>Carte Interactive des Jardins de France</h1>
    <nav>
      <a href="index.php">Retour à l'Accueil</a>
      <nav>
      <a href="index.php">Retour à l'Accueil</a>
    <a href="connexion.php">Connexion</a>
    <a href="inscription.php">Inscription</a>
  </nav>
    </nav>
  </header>

  <main>
    <div class="search-filter">
      <form action="carte_interactive.php" method="GET">
        <input type="text" name="recherche" placeholder="Rechercher un jardin ou une commune" value="<?= htmlspecialchars($recherche) ?>">
        <select name="region">
          <option value="">Toutes les régions</option>
          <?php foreach ($regions as $r): ?>
            <option value="<?= htmlspecialchars($r) ?>" <?= $region === $r ? 'selected' : '' ?>><?= htmlspecialchars($r) ?></option>
          <?php endforeach; ?>
        </select>
        <select name="departement">
          <option value="">Tous les départements</option>
          <?php foreach ($departements as $d): ?>
            <option value="<?= htmlspecialchars($d) ?>" <?= $departement === $d ? 'selected' : '' ?>><?= htmlspecialchars($d) ?></option>
          <?php endforeach; ?>
        </select>
        <select name="type">
          <option value="">Tous les types</option>
          <?php foreach ($typesJardins as $t): ?>
            <option value="<?= htmlspecialchars($t) ?>" <?= $type === $t ? 'selected' : '' ?>><?= htmlspecialchars($t) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit">Rechercher</button>
      </form>
    </div>

    <div id="map"></div>

    <?php if (count($jardins) > 0): ?>
      <div class="results-info">
        <p><?= count($jardins) ?> jardin(s) trouvé(s) pour votre recherche</p>
      </div>

      <div class="jardins-list">
        <?php foreach ($jardins as $jardin): ?>
          <div class="jardin-item" id="jardin-<?= htmlspecialchars($jardin['Identifiant DEPS'] ?? '') ?>">
            <h3><?= htmlspecialchars($jardin['Nom du jardin'] ?? '') ?></h3>
            <div class="jardin-location">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
              </svg>
              <?= htmlspecialchars($jardin['Commune'] ?? '') ?>, <?= htmlspecialchars($jardin['Departement'] ?? '') ?> (<?= htmlspecialchars($jardin['Region'] ?? '') ?>)
            </div>
            
            <?php if (!empty($jardin['Types'])): ?>
              <div class="jardin-types">
                <?php foreach (explode(',', $jardin['Types']) as $t): ?>
                  <span class="jardin-type"><?= htmlspecialchars(trim($t)) ?></span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            
            <?php if (!empty($jardin['Description'])): ?>
              <div class="jardin-description">
                <?= htmlspecialchars(substr($jardin['Description'], 0, 150)) . (strlen($jardin['Description']) > 150 ? '...' : '') ?>
              </div>
            <?php endif; ?>
            
            <?php if (!empty($jardin['Latitude']) && !empty($jardin['Longitude'])): ?>
              <a href="#map" class="view-on-map" onclick="centerMap(<?= $jardin['Latitude'] ?>, <?= $jardin['Longitude'] ?>)">Voir sur la carte</a>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="results-info">
        <p>Aucun jardin trouvé pour votre recherche.</p>
      </div>
    <?php endif; ?>
  </main>

  <footer>
    <p>&copy; 2025 Statistiques France - Données INSEE</p>
    <p><a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a></p>
  </footer>

  <script>
    // Initialisation de la carte
    var map = L.map('map').setView([46.603354, 1.888334], 5);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    
    // Fonction pour centrer la carte sur un jardin spécifique
    function centerMap(lat, lng) {
      map.setView([lat, lng], 14);
    }
    
    // Ajout des marqueurs pour les jardins
    <?php foreach ($jardins as $jardin): ?>
    <?php if (!empty($jardin['Latitude']) && !empty($jardin['Longitude'])): ?>
    L.marker([<?= $jardin['Latitude'] ?>, <?= $jardin['Longitude'] ?>]).addTo(map)
      .bindPopup(`
        <strong><?= htmlspecialchars($jardin['Nom du jardin'] ?? '') ?></strong><br>
        <?= htmlspecialchars($jardin['Commune'] ?? '') ?><br>
        <?php if (!empty($jardin['Types'])): ?>
        <em><?= htmlspecialchars($jardin['Types']) ?></em><br>
        <?php endif; ?>
        <a href="#jardin-<?= htmlspecialchars($jardin['Identifiant DEPS'] ?? '') ?>">Plus d'infos</a>
      `);
    <?php endif; ?>
    <?php endforeach; ?>
    
    // Ajuster la vue de la carte si des résultats sont trouvés
    <?php if (count($jardins) > 0 && !empty($jardins[0]['Latitude']) && !empty($jardins[0]['Longitude'])): ?>
    map.setView([<?= $jardins[0]['Latitude'] ?>, <?= $jardins[0]['Longitude'] ?>], 8);
    <?php endif; ?>
  </script>
</body>
</html>