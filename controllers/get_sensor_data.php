<?php

// Assurez-vous que le chemin vers constants.php est correct par rapport à l'emplacement de ce fichier.
require_once '../config/constants.php';
// Incluez votre autoload.php pour charger les classes ou fonctions nécessaires.
include PROJECT_ROOT . '/config/autoload.php';

// Incluez les fichiers qui contiennent connectToSharedDB, getSensorsList et getActuatorsList.
// Assurez-vous que ces chemins sont corrects.
require_once PROJECT_ROOT . '/modele/connectToSharedDB.php'; // Contient connectToSharedDB()
require_once PROJECT_ROOT . '/modele/peripherals/sensors/listSensors.php'; // Contient getSensorsList()
require_once PROJECT_ROOT . '/modele/peripherals/actuators/listActuators.php'; // Contient getActuatorsList()

// Définit le type de contenu de la réponse comme JSON
header('Content-Type: application/json');

/**
 * Récupère les mesures historiques pour un ID d'objet donné.
 *
 * @param int $idObjet L'ID du capteur ou de l'actionneur.
 * @param int $limit Le nombre maximum de relevés historiques à récupérer.
 * @return array Un tableau de tableaux associatifs, chacun contenant 'valeur_mesure' et 'date_mesure'.
 */
function getHistoricalReadings(int $idObjet, int $limit = 10): array
{
    try {
        $pdo = connectToSharedDB();
        // Requête pour récupérer les 'valeur_mesure' et 'date_mesure' pour un 'id_objet' donné.
        // ORDER BY date_mesure DESC LIMIT :limit; va récupérer les plus récents en premier.
        $sql = "SELECT valeur_mesure, date_mesure FROM `mesures` WHERE id_objet=:idObjet ORDER BY date_mesure DESC LIMIT :limit;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idObjet', $idObjet, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Inverse le tableau pour que les données les plus anciennes apparaissent en premier sur le graphique.
        return array_reverse($results);

    } catch (PDOException $e) {
        // Enregistre l'erreur dans les logs du serveur.
        return [];
    }
}

$chartData = [];

// Obtient la liste de tous les capteurs et actionneurs pour mapper les descriptions aux ID.
$sensors = getSensorsList();
$actuators = getActuatorsList();

$sensorIdMap = [];
foreach ($sensors as $sensor) {
    $sensorIdMap[$sensor['description']] = (int)$sensor['id_objet'];
}

$actuatorIdMap = [];
foreach ($actuators as $actuator) {
    $actuatorIdMap[$actuator['description']] = (int)$actuator['id_objet'];
}

// --- Récupération des données pour chaque type de graphique ---
// Pour la température
$tempId = $sensorIdMap['Capteur de Température'] ?? null; // REMPLACEZ 'Capteur de Température' par la description réelle.
if ($tempId) {
    $history = getHistoricalReadings($tempId, 10);
    $labels = [];
    $dataPoints = [];
    foreach ($history as $record) {
        $labels[] = (new DateTime($record['date_mesure']))->format('d/m H:i');
        $dataPoints[] = (float)$record['valeur_mesure'];
    }
    $chartData['temperature'] = ['labels' => $labels, 'data' => $dataPoints];
}

// Pour l'humidité
$humidityId = $sensorIdMap['Capteur d\'Humidité'] ?? null; // REMPLACEZ 'Capteur d\'Humidité' par la description réelle.
if ($humidityId) {
    $history = getHistoricalReadings($humidityId, 10);
    $labels = [];
    $dataPoints = [];
    foreach ($history as $record) {
        $labels[] = (new DateTime($record['date_mesure']))->format('d/m H:i');
        $dataPoints[] = (float)$record['valeur_mesure'];
    }
    $chartData['humidity'] = ['labels' => $labels, 'data' => $dataPoints];
}

// Pour la luminosité
$lightId = $sensorIdMap['Capteur de Luminosité'] ?? null; // REMPLACEZ 'Capteur de Luminosité' par la description réelle.
if ($lightId) {
    $history = getHistoricalReadings($lightId, 10);
    $labels = [];
    $dataPoints = [];
    foreach ($history as $record) {
        $labels[] = (new DateTime($record['date_mesure']))->format('d/m H:i');
        $dataPoints[] = (float)$record['valeur_mesure'];
    }
    $chartData['light'] = ['labels' => $labels, 'data' => $dataPoints];
}

// Pour la distance
$distanceId = $sensorIdMap['Capteur de Distance'] ?? null; // REMPLACEZ 'Capteur de Distance' par la description réelle.
if ($distanceId) {
    $history = getHistoricalReadings($distanceId, 10);
    $labels = [];
    $dataPoints = [];
    foreach ($history as $record) {
        $labels[] = (new DateTime($record['date_mesure']))->format('d/m H:i');
        $dataPoints[] = (float)$record['valeur_mesure'];
    }
    $chartData['distance'] = ['labels' => $labels, 'data' => $dataPoints];
}

// Pour le son
$soundId = $sensorIdMap['Capteur de Son'] ?? null; // REMPLACEZ 'Capteur de Son' par la description réelle.
if ($soundId) {
    $history = getHistoricalReadings($soundId, 10);
    $labels = [];
    $dataPoints = [];
    foreach ($history as $record) {
        $labels[] = (new DateTime($record['date_mesure']))->format('d/m H:i');
        $dataPoints[] = (float)$record['valeur_mesure'];
    }
    $chartData['sound'] = ['labels' => $labels, 'data' => $dataPoints];
}

// Retourne les données encodées en JSON
echo json_encode($chartData);

?>
