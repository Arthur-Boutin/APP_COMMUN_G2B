<?php
require_once(PROJECT_ROOT . '/modele/connectToSharedDB.php');

function getSensorReadings(int $idObjet, int $limit = PHP_INT_MAX): ?array
{
    try {
        $limit = (int) $limit;

        $pdo = connectToSharedDB();
        $sql = "SELECT * FROM `mesures` WHERE id_objet=:idObjet LIMIT $limit;";

        $stmt = $pdo->prepare($sql);
        $bool = $stmt->execute([':idObjet' => $idObjet]);
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

function getLastReading(int $idObjet) {
    try {
        $pdo = connectToSharedDB();
        $sql = "SELECT * FROM `mesures` WHERE id_objet=:idObjet ORDER BY date_mesure DESC LIMIT 1;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':idObjet' => $idObjet]);

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