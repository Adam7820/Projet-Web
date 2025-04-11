<!-- carte_interactive.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Carte Interactive - Décès en France</title>
  <!-- Lien vers la bibliothèque Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="carte_interactive.css">
  <style>
    /* Style de la carte */
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
    .deces-list {
      margin-top: 20px;
    }
    .deces-item {
      margin-bottom: 15px;
      padding: 10px;
      background-color: #f1f1f1;
      border-radius: 8px;
    }
    .deces-item h4 {
      margin: 0;
      font-size: 1.2rem;
    }
    .deces-item p {
      margin: 5px 0;
    }
  </style>
</head>
<body>
  <!-- Header avec le lien vers la page d'accueil -->
  <header>
    <h1>Carte Interactive des Décès en France</h1>
    <nav>
      <a href="index.html">Retour à l'Accueil</a>
    </nav>
  </header>

  <main>
    <!-- Carte interactive -->
    <div id="map"></div>

    <!-- Card pour mettre en contexte -->
    <div class="card">
      <h3>Statistiques des Décès par Région</h3>
      <p>Visualisez les données récentes des décès en France en cliquant sur les différentes régions de la carte.</p>
      <img src="images/deces-france.jpg" alt="imge de representation(provisoire)" style="width:100%; border-radius: 8px;">
    </div>

    <!-- Liste des décès avec résumé et statistiques -->
    <div class="deces-list">
      <div class="deces-item">
        <h4>Décès en Île-de-France</h4>
        <p><strong>Total des décès : 12 500(provisoire)</strong></p>
        <p>En Île-de-France, la mortalité a légèrement diminué en 2024 avec une baisse de 2 % par rapport à l'année précédente.</p>
      </div>
      <div class="deces-item">
        <h4>Décès en Provence-Alpes-Côte d'Azur</h4>
        <p><strong>Total des décès : 8 000</strong></p>
        <p>La région Provence-Alpes-Côte d'Azur a observé une stabilisation du taux de mortalité avec un léger pic en décembre 2023.</p>
      </div>
      <div class="deces-item">
        <h4>Décès en Nouvelle-Aquitaine</h4>
        <p><strong>Total des décès : 6 500</strong></p>
        <p>La région a connu un taux de décès stable, sans variations notables par rapport à 2023.</p>
      </div>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Statistiques France - Données INSEE</p>
    <p><a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a></p>
  </footer>

  <script>
    // Initialisation de la carte Leaflet
    var map = L.map('map').setView([46.603354, 1.888334], 6);  // Coordonnées centrales de la France

    // Ajout du fond de carte
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Ajouter un marker à Paris (exemple)
    L.marker([48.8566, 2.3522]).addTo(map)
      .bindPopup('<b>Paris</b><br>Statistiques des décès')
      .openPopup();

    // Ajouter un autre marker à Lyon (exemple)
    L.marker([45.75, 4.85]).addTo(map)
      .bindPopup('<b>Lyon</b><br>Statistiques des décès')
      .openPopup();
  </script>
</body>
</html>
