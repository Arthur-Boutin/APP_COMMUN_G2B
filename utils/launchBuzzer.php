<?php

use utils\Buzzer;
use utils\FileReader;

require_once '../config/constants.php';
include PROJECT_ROOT . '/config/autoload.php';
include PROJECT_ROOT . '/modele/peripherals/sensors/getReadings.php';
include PROJECT_ROOT . '/modele/peripherals/sensors/listSensors.php';

session_start();
$errors = array();

$buzzer = Buzzer::getInstance(BUZZER_COM_PORT);
$fileReader = FileReader::getInstance();

$lastTemperatureReading = null;
foreach (getSensorsList() as $sensor) {
    if ($sensor['nom'] === 'dht_temp') {
        $lastReading = getLastReading($sensor['id_objet']);

        if ($lastReading !== null) {
            $lastTemperatureReading = round($lastReading['valeur_mesure'], 2);
        }
        break;
    }
}

if ($lastTemperatureReading >= $fileReader->read("threshold")) {
    if ($buzzer->open()) {
        $buzzer->turnOnFor($buzzer->getBuzzDuration());
        $buzzer->turnOff();
        $buzzer->close();
    } else {
        $errors['availability'] = "Aucun buzzer n'est connecté ou le port COM est incorrect.";
    }
}