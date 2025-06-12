<?php
require_once(PROJECT_ROOT . '/modele/connectToSharedDB.php');

function insertActivation(): ?int
{
    try {
        $pdo = connectToSharedDB();
        $sql = "INSERT INTO mesures (id_objet, valeur_mesure)
                VALUES (6, 1);";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $stmt->closeCursor();
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        // Error executing the query
        $error = $e->getMessage();
        echo mb_convert_encoding("Database access error: $error \n", 'UTF-8', 'UTF-8');
        return null;
    }
}