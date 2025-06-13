<?php
require_once(PROJECT_ROOT . '/modele/connectToSharedDB.php');

function getSensorsList(): array
{
    $pdo = connectToSharedDB();
    $stmt = $pdo->prepare('SELECT DISTINCT c.*
                                    FROM capteur_actionneur c
                                    JOIN type t ON t.id_type = c.id_type
                                    WHERE t.est_actionneur=0;');
    $stmt->execute();
    $sensors = $stmt->fetchAll();

    return $sensors;
}