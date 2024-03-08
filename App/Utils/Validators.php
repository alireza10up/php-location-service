<?php

namespace App\Utils;

class Validators
{
    public static function validateLocationName(string $name): array
    {
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Enter the name of the place.';
        }

        if (strlen($name) < 3) {
            $errors[] = 'The place name must have at least 3 characters.';
        }

        if (!preg_match('/^[a-zA-Z0-9\s\p{Arabic}]+$/u', $name)) {
            $errors[] = 'Location names must contain only letters, numbers, and spaces.';
        }

        return $errors;
    }
}