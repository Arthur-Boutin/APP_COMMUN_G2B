<?php
require_once(PROJECT_ROOT . '/modele/connectToSharedDB.php');

function getActuatorsList(): array
{
    $pdo = connectToSharedDB();
    $stmt = $pdo->prepare('SELECT DISTINCT a.*
                                    FROM capteur_actionneur a
                                    JOIN type t ON t.id_type = a.id_type
                                    WHERE t.est_actionneur=1;');
    $stmt->execute();
    $sensors = $stmt->fetchAll();

    return $sensors;
}