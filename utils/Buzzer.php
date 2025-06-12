<?php

namespace utils;
use DateTime;
use DateTimeZone;

require_once(PROJECT_ROOT . '/modele/peripherals/actuators/insertActivation.php');
require_once(PROJECT_ROOT . '/modele/peripherals/actuators/getActivations.php');

class Buzzer
{
    private static ?Buzzer $instance = null;
    private string $comPort;
    private int $baudRate;
    private $handle;

    private function __construct(string $comPort, int $baudRate = 115200) // 115200 est baud rate pour connexion USB Serial
    {
        $this->comPort = "\\\\.\\" . $comPort;
        $this->baudRate = $baudRate;
    }

    public static function getInstance(string $comPort): Buzzer
    {
        if (self::$instance === null) {
            self::$instance = new Buzzer($comPort);
        }
        return self::$instance;
    }

    public function open(): bool
    {
        exec("mode {$this->comPort} BAUD={$this->baudRate} PARITY=n DATA=8 STOP=1 xon=off");
        $this->handle = @fopen($this->comPort, "w+");

        if (!$this->handle) {
            return false;
        }

        return true;
    }

    public function turnOn(): bool
    {
        if (!$this->handle) { return false;}

        for ($i = 0; $i < 500; $i++) {
            fwrite($this->handle, "1");
            //usleep(150);
        }
        return true;
    }

    public function turnOnFor(int $seconds = 1): bool
    {
        if (!$this->handle) { return false; }

        $start = time();
        while (time() - $start < $seconds) {
            $this->turnOn();
            usleep(100000);
        }

        insertActivation();
        return true;
    }

    public function turnOff(): bool
    {
        if (!$this->handle) { return false; }
        fwrite($this->handle, "0");

        return true;
    }

    public static function getLastNActivations(int $n): array
    {
        return getActivations($n);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function getLastActivationToString(): ?string
    {
        $activation = getLastActivation();

        $formattedDate = new DateTime($activation['date_mesure'], new DateTimeZone('UTC'))
            ->setTimezone(new DateTimeZone('Europe/Paris'))
            ->format('d/m/Y à H:i:s');

        return "Buzzer - le " . $formattedDate;
    }

    public function close(): void
    {
        if ($this->handle) {
            fclose($this->handle);
            $this->handle = null;
        }
    }
}