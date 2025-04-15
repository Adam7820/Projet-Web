<?php
// Tu pourras plus tard inclure ici des connexions à une base de données ou d'autres fonctions PHP
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Naissances en France depuis 2000</title>
  <meta name="description" content="Explorez les statistiques de natalité en France depuis l'an 2000 grâce aux données de l'INSEE.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="index.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

  <!-- En-tête -->
  <header>
    <h1>Naissances en France depuis l'an 2000</h1>
    <nav>
      <a href="carte_interactive.php">Carte Interactive</a>
    </nav>
  </header>

  <!-- Contenu principal -->
  <main>
    <section class="intro">
      <h2>Bienvenue sur notre portail dédié aux naissances</h2>
      <p>
        Depuis l’an 2000, la France enregistre des millions de naissances chaque année. Ces données reflètent les
        dynamiques démographiques, les tendances régionales et les choix culturels des parents. Grâce aux statistiques de l'INSEE,
        nous vous proposons un aperçu clair de l'évolution de la natalité en France.
      </p>
      <p>
        Sur ce site, vous trouverez :
        <ul>
          <li>Une carte interactive avec les naissances par région et par année</li>
          <li>Un tableau des prénoms les plus donnés aux nouveau-nés</li>
          <li>Des statistiques officielles mises à jour</li>
        </ul>
      </p>
      <div class="button-group">
        <a href="carte_interactive.php" class="button">Carte Interactive</a>
        <a href="prenoms.html" class="button secondary">Top des prénoms</a>
      </div>
    </section>

    <section class="highlights">
      <div class="highlight">
        <h3>+16 millions de naissances</h3>
        <p>Une dynamique nationale soutenue depuis deux décennies.</p>
      </div>
      <div class="highlight">
        <h3>Focus régional</h3>
        <p>Comparez les tendances entre régions : Île-de-France, PACA, ARA...</p>
      </div>
      <div class="highlight">
        <h3>Données officielles</h3>
        <p>Statistiques issues directement de l'INSEE, mises à jour régulièrement.</p>
      </div>
    </section>
  </main>

  <!-- Pied de page -->
  <footer>
    <p>&copy; 2025 Statistiques France - Données INSEE</p>
    <p><a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a></p>
  </footer>

</body>
</html>
