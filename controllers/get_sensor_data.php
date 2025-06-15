<?php
// Ce fichier PHP sera le point d'API pour récupérer les données des graphiques en JSON

// Activer l'affichage des erreurs pour le débogage (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/constants.php';
include PROJECT_ROOT . '/config/autoload.php';
include PROJECT_ROOT . '/modele/peripherals/sensors/getReadings.php';
include PROJECT_ROOT . '/modele/peripherals/sensors/listSensors.php';
include PROJECT_ROOT . '/modele/peripherals/actuators/listActuators.php'; // Inclure si les actionneurs ont des relevés à afficher
include PROJECT_ROOT . '/modele/peripherals/actuators/getActivations.php'; // Spécifique au buzzer

// Définir l'en-tête pour indiquer que la réponse est du JSON
header('Content-Type: application/json');

$chartData = [
    'temperature' => ['labels' => [], 'data' => []],
    'humidity' => ['labels' => [], 'data' => []],
    'light' => ['labels' => [], 'data' => []],
    'distance' => ['labels' => [], 'data' => []],
    'sound' => ['labels' => [], 'data' => []],
    'buzzer' => ['labels' => [], 'data' => []],
];

// Définir une limite pour le nombre de points de données pour les graphiques
$chartDataLimit = 30; // Récupérer les 30 dernières mesures pour chaque capteur

// Récupérer les ID d'objet des capteurs (nécessaire pour l'API)
$sensorsList = getSensorsList();
$sensorDescriptionsToIds = [];
foreach ($sensorsList as $sensor) {
    $sensorDescriptionsToIds[$sensor['description']] = $sensor['id_objet'];
}

// Récupérer et formater les données pour chaque type de capteur
// Les IDs d'objet (id_objet) devront correspondre à votre base de données réelle
// ID 1: Température, ID 2: Humidité, ID 3: Luminosité, ID 4: Distance, ID 5: Son
// ID 6: Buzzer (activation)

// Température
$tempReadings = getSensorReadings($sensorDescriptionsToIds['Temperature'] ?? 1, $chartDataLimit);
if ($tempReadings) {
    usort($tempReadings, function($a, $b) {
        return strtotime($a['date_mesure']) - strtotime($b['date_mesure']);
    });
    foreach ($tempReadings as $reading) {
        $chartData['temperature']['labels'][] = (new DateTime($reading['date_mesure'], new DateTimeZone('UTC')))->format('d/m H:i:s');
        $chartData['temperature']['data'][] = round($reading['valeur_mesure'], 2);
    }
}
// Humidité
$humidityReadings = getSensorReadings($sensorDescriptionsToIds['Humidité'] ?? 2, $chartDataLimit);
if ($humidityReadings) {
    usort($humidityReadings, function($a, $b) {
        return strtotime($a['date_mesure']) - strtotime($b['date_mesure']);
    });
    foreach ($humidityReadings as $reading) {
        $chartData['humidity']['labels'][] = (new DateTime($reading['date_mesure'], new DateTimeZone('UTC')))->format('d/m H:i:s');
        $chartData['humidity']['data'][] = round($reading['valeur_mesure'], 2);
    }
}
// Luminosité
$lightReadings = getSensorReadings($sensorDescriptionsToIds['Luminosité'] ?? 3, $chartDataLimit);
if ($lightReadings) {
    usort($lightReadings, function($a, $b) {
        return strtotime($a['date_mesure']) - strtotime($b['date_mesure']);
    });
    foreach ($lightReadings as $reading) {
        $chartData['light']['labels'][] = (new DateTime($reading['date_mesure'], new DateTimeZone('UTC')))->format('d/m H:i:s');
        $chartData['light']['data'][] = round($reading['valeur_mesure'], 2);
    }
}
// Distance
$distanceReadings = getSensorReadings($sensorDescriptionsToIds['Distance'] ?? 4, $chartDataLimit);
if ($distanceReadings) {
    usort($distanceReadings, function($a, $b) {
        return strtotime($a['date_mesure']) - strtotime($b['date_mesure']);
    });
    foreach ($distanceReadings as $reading) {
        $chartData['distance']['labels'][] = (new DateTime($reading['date_mesure'], new DateTimeZone('UTC')))->format('d/m H:i:s');
        $chartData['distance']['data'][] = round($reading['valeur_mesure'], 2);
    }
}
// Son
$soundReadings = getSensorReadings($sensorDescriptionsToIds['Son'] ?? 5, $chartDataLimit);
if ($soundReadings) {
    usort($soundReadings, function($a, $b) {
        return strtotime($a['date_mesure']) - strtotime($b['date_mesure']);
    });
    foreach ($soundReadings as $reading) {
        $chartData['sound']['labels'][] = (new DateTime($reading['date_mesure'], new DateTimeZone('UTC')))->format('d/m H:i:s');
        $chartData['sound']['data'][] = round($reading['valeur_mesure'], 2);
    }
}

// Buzzer Activations - Compter l'activation heure par heure
$buzzerActivations = getActivations($chartDataLimit);
if ($buzzerActivations) {
    usort($buzzerActivations, function($a, $b) {
        return strtotime($a['date_mesure']) - strtotime($b['date_mesure']);
    });

    $hourlyActivations = [];
    foreach ($buzzerActivations as $activation) {
        $dateTime = new DateTime($activation['date_mesure'], new DateTimeZone('UTC'));
        // Utiliser le format 'd/m H:00' pour regrouper par heure sur des jours différents
        $hourLabel = $dateTime->format('d/m H:00'); 

        if (!isset($hourlyActivations[$hourLabel])) {
            $hourlyActivations[$hourLabel] = 0;
        }
        $hourlyActivations[$hourLabel]++;
    }

    // Convertir les données agrégées en format Chart.js
    foreach ($hourlyActivations as $label => $count) {
        $chartData['buzzer']['labels'][] = $label;
        $chartData['buzzer']['data'][] = $count;
    }
}

// Encoder les données des graphiques en JSON et les afficher
try {
    echo json_encode($chartData, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    // En cas d'erreur, renvoyer un JSON d'erreur
    http_response_code(500); // Définir un code de statut HTTP 500 pour indiquer une erreur serveur
    echo json_encode(['error' => 'Erreur lors de l\'encodage JSON des données: ' . $e->getMessage()]);
    error_log("Erreur d'encodage JSON dans get_chart_data.php: " . $e->getMessage());
}
?>
