<?php
function connectToDbAndGetPdo() {
    $host = "localhost";
    $dbname = "Projet_PG2";
    $user = "root";
    $password = "";
    
    try {
        $db = new PDO(
            "mysql:host=$host;dbname=$dbname",
            $user,
            $password,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::MYSQL_ATTR_DIRECT_QUERY => true)
        );
        return $db;
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        return null;
    }
}


?>
