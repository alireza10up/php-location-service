<?php

namespace App\Utils;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class Validators
{
    public static function validateLocation(array $data): array
    {
        $errors = [];

        $validator = Validation::createValidator();

        $violations = $validator->validate($data['term'], [
            new Assert\NotBlank(),
            new Assert\Length(['min' => 3]),
            new Assert\Regex('/^[a-zA-Z0-9\s\p{Arabic}%20]+$/u'),
        ]);

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $errors['term'][] = $violation->getMessage();
            }
        }

        $floatPattern = '/^[-+]?[0-9]+(\.[0-9]+)?$/';

        if (!isset($data['lat']) || !preg_match($floatPattern, $data['lat'])) {
            $errors['lat'] = 'Latitude coordinates are invalid.';
        }

        if (!isset($data['lng']) || !preg_match($floatPattern, $data['lng'])) {
            $errors['lng'] = 'Longitude coordinates are invalid.';
        }

        return $errors;
    }
}
