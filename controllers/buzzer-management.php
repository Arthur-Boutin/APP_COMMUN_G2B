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

if (isset($_POST) && count($_POST) > 0) {
    // declenchement buzzer
    if (isset($_POST['push-buzzer-btn']) && $_POST['push-buzzer-btn'] === 'buzzer-pressed') {
        if ($buzzer->open()) {
            $buzzer->turnOnFor($buzzer->getBuzzDuration());
            $buzzer->turnOff();
            $buzzer->close();
        } else {
            $errors['availability'] = "Aucun buzzer n'est connecté ou le port COM est incorrect.";
        }
    }

    // gere seuil
    if (isset($_POST['threshold'])) {
        $threshold = intval($_POST['threshold']);
        $buzzer->setThreshold($threshold);
        try {
            $fileReader->write("threshold", $threshold);
        } catch (Exception $e) {

        }
        echo json_encode(['success' => true, 'threshold' => $buzzer->getThreshold()]);
        exit;
    }

    // gere duree buzz
    if (isset($_POST['buzzDuration'])) {
        $buzzDuration = intval($_POST['buzzDuration']);
        $buzzer->setBuzzDuration($buzzDuration);
        $fileReader->write("duration", $buzzDuration);
        echo json_encode(['success' => true, 'buzzDuration' => $buzzer->getBuzzDuration()]);
        exit;
    }
}

$lastTemperatureReading = null;
foreach (getSensorsList() as $sensor) {
    if ($sensor['nom'] === 'dht_temp') {
        $lastReading = getLastReading($sensor['id_objet']);

        if ($lastReading !== null) {
            $lastTemperatureReading = round($lastReading['valeur_mesure'], 2) . " " . $sensor['unite'];
        }
        break;
    }
}

$threshold = $buzzer->getThreshold();
$buzzDuration = $buzzer->getBuzzDuration();

include_once PROJECT_ROOT . '/views/components/header.html';
include_once PROJECT_ROOT . '/views/buzzer-management.html';
include_once PROJECT_ROOT . '/views/components/footer.html';