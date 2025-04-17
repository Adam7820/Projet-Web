<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Carte Interactive - Naissances en France</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <?php require_once "database.php";
  $pdo = connectToDbAndGetPdo(); ?>

  <link rel="stylesheet" href="carte_interactive.css">
  <style>
    #map {
      height: 500px;
      width: 100%;
    }
    .card {
      background-color: #fff;
      border-radius: 10px;
      padding: 20px;
      margin-top: 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .card h3 {
      margin: 0;
      font-size: 1.5rem;
    }
    .naissance-list {
      margin-top: 20px;
    }
    .naissance-item {
      margin-bottom: 15px;
      padding: 10px;
      background-color: #f1f9ff;
      border-radius: 8px;
    }
    .naissance-item h4 {
      margin: 0;
      font-size: 1.2rem;
    }
    .naissance-item p {
      margin: 5px 0;
    }
  </style>
</head>
<body>
  <header>
    <h1>Carte Interactive des Naissances en France</h1>
    <nav>
      <a href="index.html">Retour à l'Accueil</a>
    </nav>
  </header>

  <main>
    <div id="map"></div>

    <div class="card">
      <h3>Évolution des Naissances par Région</h3>
      <p>Explorez les données de natalité en France depuis l’an 2000 en cliquant sur les différentes régions.</p>
      <img src="images/naissances-france.jpg" alt="Naissances en France" style="width:100%; border-radius: 8px;">
    </div>

    <div class="naissance-list">
      <div class="naissance-item">
        <h4>Île-de-France</h4>
        <p><strong>Total depuis 2000 : 2 400 000 naissances</strong></p>
        <p>La région reste la plus dynamique démographiquement avec une natalité soutenue chaque année.</p>
      </div>
      <div class="naissance-item">
        <h4>Auvergne-Rhône-Alpes</h4>
        <p><strong>Total depuis 2000 : 1 800 000 naissances</strong></p>
        <p>Une forte natalité dans les métropoles comme Lyon ou Grenoble, bien au-dessus de la moyenne nationale.</p>
      </div>
      <div class="naissance-item">
        <h4>Nouvelle-Aquitaine</h4>
        <p><strong>Total depuis 2000 : 1 250 000 naissances</strong></p>
        <p>Une région stable avec des pics dans les zones urbaines comme Bordeaux et La Rochelle.</p>
      </div>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Statistiques France - Données INSEE</p>
    <p><a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a></p>
  </footer>

  <script>
    var map = L.map('map').setView([46.603354, 1.888334], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker([48.8566, 2.3522]).addTo(map)
      .bindPopup('<b>Paris</b><br>Naissances depuis 2000')
      .openPopup();

    L.marker([45.75, 4.85]).addTo(map)
      .bindPopup('<b>Lyon</b><br>Naissances depuis 2000')
      .openPopup();
  </script>
</body>
</html>
