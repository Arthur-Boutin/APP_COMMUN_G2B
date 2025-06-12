<?php

include __DIR__ . '/config/autoload.php';

/*$comPort = "\\\\.\\COM3";
$baudRate = 115200;

exec("mode COM3 BAUD=$baudRate PARITY=n DATA=8 STOP=1 xon=off");

$fp = fopen($comPort, "w+");

if (!$fp) {
    echo "Error: Could not open COM port.\n";
    exit(1);
}
exec("mode $comPort BAUD=$baudRate PARITY=n DATA=8 STOP=1 xon=off");


for ($i = 0; $i < 500; $i++) {
    fwrite($fp, "1"); // 1 pour on
    echo "Signal sent to Tiva on COM3.\n";
    //sleep(1);
    //
}


/*while(true){
    fwrite($fp, "1"); // 0 pour off
    echo "Signal sent to Tiva on COM3.\n";
}

fclose($fp);*/


use utils\Buzzer;

$buzzer = Buzzer::getInstance("COM3");
if ($buzzer->open()) {
    $buzzer->turnOnFor(1); // Turn on the actuators
    $buzzer->turnOff();
    $buzzer->close();
} else {
    echo "Failed to open the actuators on COM3.\n";
}

var_dump($buzzer->getLastNActivations(5));
echo $buzzer->getLastActivationToString();