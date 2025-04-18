<?php
function connectToDbAndGetPdo() {
    $host = "mysql-adam7820.alwaysdata.net";
    $dbname = "adam7820_eclosens";
    $user = "adam7820";
    $password = "HW6UG_uwDab.J@q";

    try {
        $db = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8",
            $user,
            $password,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            )
        );
        return $db;
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        return null;
    }
}
?>
