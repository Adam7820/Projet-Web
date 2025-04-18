<?php
// Tu pourras plus tard inclure ici des connexions à une base de données ou d'autres fonctions PHP
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>ÉcloSens - Le patrimoine des jardins et parcs de France</title>
  <meta name="description" content="Découvrez le patrimoine végétal français à travers nos plus beaux jardins et parcs. Exploration interactive, histoire et biodiversité.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="index.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

  <!-- En-tête -->
  <header>
    <h1>ÉcloSens - Le patrimoine des jardins et parcs de France</h1>
    <nav>
      <a href="carte_interactive.php">Carte Interactive</a>
      <a href="types_jardins.php">Types de Jardins</a>
      <a href="patrimoine.php">Patrimoine UNESCO</a>
      <a href="connexion.php">Connexion</a>
      <a href="inscription.php">Inscription</a>
    </nav>
  </header>

  <!-- Contenu principal -->
  <main>
    <section class="intro">
      <h2>Bienvenue sur ÉcloSens, votre portail vers la magie verte de France</h2>
      <p>
        La France abrite un trésor inestimable de plus de 2300 jardins remarquables, chacun racontant une histoire unique 
        à travers ses allées, ses fontaines et ses espèces végétales. Des jardins à la française de Le Nôtre aux parcs 
        contemporains écologiques, notre patrimoine paysager reflète notre histoire, notre culture et notre rapport à la nature.
      </p>
      <p>
        Sur ÉcloSens, vous découvrirez :
        <ul>
          <li>Une carte interactive des jardins et parcs classés par région et par époque</li>
          <li>Des collections botaniques exceptionnelles et leur histoire</li>
          <li>Les jardins labellisés "Jardins Remarquables" et patrimoine UNESCO</li>
          <li>Un calendrier des floraisons et des événements saisonniers</li>
          <li>Des itinéraires thématiques pour vos escapades vertes</li>
        </ul>
      </p>
      <div class="button-group">
        <a href="carte_interactive.php" class="button">Explorer la carte des jardins</a>
      </div>
    </section>

    <!-- Image panoramique d'un jardin français -->
    <div class="panorama">
      <img src="/api/placeholder/1200/400" alt="Vue panoramique du Jardin des Tuileries au printemps, avec ses alignements parfaits et ses fontaines" />
      <p class="caption">Le Jardin des Tuileries, exemple parfait du jardin à la française et de la symétrie maîtrisée</p>
    </div>

    <section class="highlights">
      <div class="highlight">
        <h3>+2300 jardins remarquables</h3>
        <p>Un patrimoine végétal exceptionnel réparti sur tout le territoire français, du petit jardin de curé au domaine royal.</p>
        <img src="/api/placeholder/250/150" alt="Rose ancienne dans un jardin de château" />
      </div>
      <div class="highlight">
        <h3>Jardins historiques</h3>
        <p>De Versailles à Villandry, découvrez les chefs-d'œuvre paysagers qui ont traversé les siècles et influencé l'art des jardins mondial.</p>
        <img src="/api/placeholder/250/150" alt="Parterres géométriques de Villandry" />
      </div>
      <div class="highlight">
        <h3>Biodiversité préservée</h3>
        <p>Explorez les jardins botaniques et conservatoires qui préservent notre patrimoine végétal et développent l'écologie de demain.</p>
        <img src="/api/placeholder/250/150" alt="Collection d'espèces rares au Jardin des Plantes" />
      </div>
    </section>

    <section class="card">
      <h3>À la découverte des jardins par saison</h3>
      <p>
        Chaque saison transforme nos jardins en tableaux vivants aux ambiances uniques. Au printemps, 
        les jardins de Giverny s'illuminent des couleurs impressionnistes qui ont inspiré Monet. 
        L'été révèle la splendeur des roseraies comme celle de l'Haÿ-les-Roses avec plus de 3000 variétés. 
        L'automne enflamme les arboretums comme celui des Barres dans le Loiret, tandis que l'hiver 
        dévoile la structure élégante des jardins à la française et leurs topiaires sculptées.
      </p>
      <p>
        Notre calendrier interactif vous permet de planifier vos visites au meilleur moment pour 
        chaque jardin, qu'il s'agisse de la floraison des cerisiers du Parc de Sceaux en avril ou 
        des illuminations hivernales du Jardin des Plantes.
      </p>
    </section>
    
    <!-- Galerie de jardins emblématiques -->
    <section class="garden-gallery">
      <h3>Joyaux de notre patrimoine paysager</h3>
      
    </section>
  </main>

  <!-- Pied de page -->
  <footer>
    <p>&copy; 2025 ÉcloSens - Le patrimoine des jardins et parcs de France</p>
    <p><a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a> | <a href="#">Crédits photos</a></p>
    <p>Données issues du Comité des Parcs et Jardins de France et du Ministère de la Culture</p>
  </footer>

</body>
</html>