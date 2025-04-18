<?php
require_once 'database.php';
$db = connectToDbAndGetPdo();

// Fonction pour nettoyer les données
function cleanData($data) {
    return trim(str_replace('"', '', $data));
}

// Chemin vers le fichier CSV
$fichierCSV = 'jardins.csv';

// Vérifier si le fichier existe
if (!file_exists($fichierCSV)) {
    die("Le fichier $fichierCSV n'existe pas.");
}

// Ouvrir le fichier
$fichier = fopen($fichierCSV, 'r');
if (!$fichier) {
    die("Impossible d'ouvrir le fichier $fichierCSV.");
}

// Lire l'en-tête
$enTete = fgetcsv($fichier, 0, ';');

// Préparer la requête d'insertion
$sql = "INSERT INTO jardins (
            Nom, Code_Postal, Region, Departement, Adresse_complete, 
            Adresse_entree, Numero_voie, Complement_adresse, Commune, 
            Code_INSEE, Code_INSEE_Departement, Code_INSEE_Region, 
            Latitude, Longitude, Site_Internet, Types, Annee_obtention, 
            Description, Auteur, Identifiant_DEPS, Identifiant_origine, 
            Accessible_public, Conditions_ouverture, Equipement, 
            Date_creation, Date_MAJ, coordonnees_geographiques
        ) VALUES (
            :nom, :codePostal, :region, :departement, :adresseComplete,
            :adresseEntree, :numeroVoie, :complementAdresse, :commune,
            :codeINSEE, :codeINSEEDep, :codeINSEERegion,
            :latitude, :longitude, :siteInternet, :types, :anneeObtention,
            :description, :auteur, :identifiantDEPS, :identifiantOrigine,
            :accessiblePublic, :conditionsOuverture, :equipement,
            :dateCreation, :dateMAJ, :coordonneesGeo
        )";

$stmt = $db->prepare($sql);

// Compteur de lignes importées
$compteur = 0;

// Lire chaque ligne du fichier
while (($ligne = fgetcsv($fichier, 0, ';')) !== FALSE) {
    // S'assurer que la ligne a le bon nombre de colonnes
    if (count($ligne) != count($enTete)) {
        echo "Ligne ignorée : nombre de colonnes incorrect.<br>";
        continue;
    }

    // Associer les valeurs aux colonnes
    $data = [];
    for ($i = 0; $i < count($enTete); $i++) {
        $data[$enTete[$i]] = cleanData($ligne[$i]);
    }

    // Préparer les données pour l'insertion
    $stmt->bindValue(':nom', $data['Nom du jardin'] ?? null);
    $stmt->bindValue(':codePostal', $data['Code Postal'] ?? null);
    $stmt->bindValue(':region', $data['Région'] ?? null);
    $stmt->bindValue(':departement', $data['Département'] ?? null);
    $stmt->bindValue(':adresseComplete', $data['Adresse complète'] ?? null);
    $stmt->bindValue(':adresseEntree', $data['Adresse de l\'entrée du public'] ?? null);
    $stmt->bindValue(':numeroVoie', $data['Numéro et libellé de la voie'] ?? null);
    $stmt->bindValue(':complementAdresse', $data['Complément d\'adresse'] ?? null);
    $stmt->bindValue(':commune', $data['Commune'] ?? null);
    $stmt->bindValue(':codeINSEE', $data['Code INSEE'] ?? null);
    $stmt->bindValue(':codeINSEEDep', $data['Code INSEE Département'] ?? null);
    $stmt->bindValue(':codeINSEERegion', $data['Code INSEE Région'] ?? null);
    $stmt->bindValue(':latitude', $data['Latitude'] ?? null);
    $stmt->bindValue(':longitude', $data['Longitude'] ?? null);
    $stmt->bindValue(':siteInternet', $data['Site Internet et autres liens'] ?? null);
    $stmt->bindValue(':types', $data['Types'] ?? null);
    $stmt->bindValue(':anneeObtention', $data['Année d\'obtention'] ?? null);
    $stmt->bindValue(':description', $data['Description'] ?? null);
    $stmt->bindValue(':auteur', $data['Auteur / Nom de l\'illustre'] ?? null);
    $stmt->bindValue(':identifiantDEPS', $data['Identifiant DEPS'] ?? null);
    $stmt->bindValue(':identifiantOrigine', $data['Identifiant origine'] ?? null);
    $stmt->bindValue(':accessiblePublic', $data['Accessible au public'] ?? null);
    $stmt->bindValue(':conditionsOuverture', $data['Conditions d\'ouverture'] ?? null);
    $stmt->bindValue(':equipement', $data['Equipement Précision'] ?? null);
    $stmt->bindValue(':dateCreation', $data['Date de création'] ?? null);
    $stmt->bindValue(':dateMAJ', $data['Date de MAJ'] ?? null);
    $stmt->bindValue(':coordonneesGeo', $data['coordonnees_geographiques'] ?? null);

    // Exécuter la requête
    try {
        $stmt->execute();
        $compteur++;
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion : " . $e->getMessage() . "<br>";
    }
}

// Fermer le fichier
fclose($fichier);

echo "Importation terminée. $compteur lignes ont été importées avec succès.";
?>