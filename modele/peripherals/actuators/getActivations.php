<?php
require_once(PROJECT_ROOT . '/modele/connectToSharedDB.php');

function getActivations(int $limit = PHP_INT_MAX): ?array
{
    try {
        $limit = (int) $limit;

        $pdo = connectToSharedDB();
        $sql = "SELECT * FROM `mesures` WHERE id_objet=6 LIMIT $limit;";

        $stmt = $pdo->prepare($sql);
        $bool = $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        return count($results) > 0 ? $results : null;
    }
    catch (PDOException $e) {
        // Error executing the query
        $error = $e->getMessage();
        echo mb_convert_encoding("Database access error: $error \n", 'UTF-8', 'UTF-8');
        return null;
    }
}

function getLastActivation() {
    try {
        $pdo = connectToSharedDB();
        $sql = "SELECT * FROM `mesures` WHERE id_objet=6 ORDER BY date_mesure DESC LIMIT 1;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        return $result !== false ? $result : null;
    }
    catch (PDOException $e) {
        $error = $e->getMessage();
        echo mb_convert_encoding("Database access error: $error \n", 'UTF-8', 'UTF-8');
        return null;
    }
}