<?php

namespace App\Utils;

use App\Exceptions\ConfigFileNotFoundException;

class Config
{
    public static function getFileContent(string $filename)
    {
        $filePath = realpath(BASE_PATH . "/configs/" . $filename . ".php");

        if (!$filePath) {
            throw new ConfigFileNotFoundException();
        }

        return require $filePath;
    }

    public static function get(string $filename, string $key = null)
    {
        $fileContents = self::getFileContent($filename);

        if (is_null($key)) return $fileContents;

        return $fileContents[$key] ?? null;
    }
}