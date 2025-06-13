<?php

namespace utils;

require_once(PROJECT_ROOT . '/config/constants.php');
use Exception;

class FileReader
{
    private static ?FileReader $instance = null;
    final string $filePath = DATA;
    private array $fileContent = [];

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->open();
    }

    public static function getInstance(): FileReader
    {
        if (self::$instance === null) {
            self::$instance = new FileReader();
        }
        return self::$instance;
    }

    /**
     * @throws Exception
     */
    public function open(): array
    {
        if (!file_exists($this->filePath)) {
            throw new Exception("File not found: " . $this->filePath);
        }

        $content = file_get_contents($this->filePath);
        $this->fileContent = json_decode($content, true);

        if ($this->fileContent === false) {
            throw new Exception("Could not read file: " . $this->filePath);
        }
        return $this->fileContent;
    }

    /**
     * @throws Exception
     */
    public function write(string $attribute, int $value): void
    {
        if (!array_key_exists($attribute, $this->fileContent)) {
            throw new Exception("Attribute not found in file: " . $attribute);
        }

        $this->fileContent[$attribute] = $value;

        if (file_put_contents($this->filePath, json_encode($this->fileContent)) === false) {
            throw new Exception("Could not write to file: " . $this->filePath);
        }
    }

    public function read(string $attribute): int
    {
        if (!array_key_exists($attribute, $this->fileContent)) {
            return 0;
        }
        return $this->fileContent[$attribute];
    }
}