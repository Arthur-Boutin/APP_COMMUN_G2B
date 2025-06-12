<?php

require_once '../config/constants.php';
include PROJECT_ROOT . '/config/autoload.php';
include PROJECT_ROOT . '/modele/peripherals/sensors/getReadings.php';
include PROJECT_ROOT . '/modele/peripherals/sensors/listSensors.php';
include PROJECT_ROOT . '/modele/peripherals/actuators/listActuators.php';

use utils\Buzzer;

session_start();

$lastSensorsReadings = array();
foreach (getSensorsList() as $sensor) {
    $lastReading = getLastReading($sensor['id_objet']);

    if ($lastReading !== null) {
        $formattedDate = new DateTime($lastReading['date_mesure'], new DateTimeZone('UTC'))
            ->format('d/m/Y à H:i:s');
    }

    $formattedReading = $lastReading ? $sensor['description'] . ' : ' . round($lastReading['valeur_mesure'], 2) . " " . $sensor['unite'] . " - le " . $formattedDate : 'Aucun relevé disponible';
    array_push($lastSensorsReadings, $formattedReading);
}


$lastActuatorsReadings = array();
foreach (getActuatorsList() as $actuator) {
    $lastReading = getLastReading($actuator['id_objet']);
    $formattedDate = new DateTime($lastReading['date_mesure'], new DateTimeZone('UTC'))
        ->format('d/m/Y à H:i:s');

    $formattedReading = $lastReading ? $actuator['description'] . " - le " . $formattedDate : 'Aucune lecture';
    array_push($lastActuatorsReadings, $formattedReading);
}

include_once PROJECT_ROOT . '/views/components/header.html';
include_once PROJECT_ROOT . '/views/data-display.html';
include_once PROJECT_ROOT . '/views/components/footer.html';
