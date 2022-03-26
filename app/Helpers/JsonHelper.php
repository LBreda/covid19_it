<?php

namespace App\Helpers;

class JsonHelper
{
    /**
     * JSON linter, inspired by a comment on
     * https://stackoverflow.com/questions/13236819/how-to-fix-badly-formatted-json-in-php
     * @param string $json
     * @return string
     */
    public static function lint(string $json): string
    {
        $fixes = [
            // Remove trailing commas in arrays of objects
            "/(}),(\s*+[^{])/" => '$1$2',

            // Remove non-printable characters
            '/[\x00-\x1F\x80-\xFF]/' => '',
        ];

        return preg_replace(array_keys($fixes), array_values($fixes), $json);
    }
}
