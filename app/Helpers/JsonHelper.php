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
            /* Find any character except colons, commas, curly and square brackets surrounded or
             * not by spaces preceded and followed by spaces, colons, commas, curly or square brackets
             * and put quotation marks surrounded by spaces between them
             */
            "/([\s:,\{}\[\]])\s*'([^:,\{}\[\]]*)'\s*([\s:,\{}\[\]])/" => '$1 "$2" $3',

            /* Find any left curly brackets surrounded or not by one or more of any character except
             * spaces, colons, commas, curly and square brackets and put spaces between them
             */
            '/([^\s:,\{}\[\]]*)\{([^\s:,\{}\[\]]*)/' => '$1 { $2',

            /* Find any right curly brackets preceded by one or more of any character except spaces,
             * colons, commas, curly and square brackets and put a space between them
             */
            "/([^\s:,\{}\[\]]+)}/" => '$1 }',

            /* JSON.parse() doesn't allow trailing commas, so, remove trailing commas of any right curly brackets */
            "/(}),\s*/" => '$1',

            /* Find or not one or more of any character except spaces, colons, commas, curly and square brackets
             * followed by one or more of any character except spaces, colons, commas, curly and square brackets
             * and put quotation marks surrounding them
            */
            '/([^\s:,\{}\[\]]+\s*)*[^\s:,\{}\[\]]+/' => '"$0"',

            /* Find one or more of quotation marks or/and apostrophes surrounding any character except colons,
             * commas, curly and square brackets and replace by single quotation marks
             */
            '/["\']+([^"\':,\{}\[\]]*)["\']+/' => '"$1"',

            /* Find or not one or more of any character except spaces, colons, commas, curly and square brackets
             * surrounded by quotation marks followed by one or more spaces and  one or more of any character except
             * spaces, colons, commas, curly and square brackets and add backslashes to its quotation marks
             */
            '/(")([^\s:,\{}\[\]]+)(")(\s+([^\s:,\{}\[\]]+))/' => '\\$1$2\\$3$4',

            /* Find or not one or more of any character except spaces, colons, commas, curly and square brackets
             * surrounded by apostrophes followed by one or more spaces and  one or more of any character except
             * spaces, colons, commas, curly and square brackets and add back slashes to its apostrophes;
             */
            "/(')([^\s:,\{}\[\]]+)(')(\s+([^\s:,\{}\[\]]+))/" => '\\$1$2\\$3$4',

            /* Find any right curly brackets followed by quotation marks */
            '/(})(")/' => '$1, $2',

            /* Find any comma followed by one or more spaces and a right curly bracket and replace by a space
             * followed by a right curly bracket
             */
            '/,\s+(})/' => ' $1',

            /* Find one or more spaces and replace by one space */
            '/\s+/' => ' ',

            /* Find one or more spaces at start of string and remove it */
            '/^\s+/' => '',

            /* Find any non-printable character and remove it */
            '/[\x00-\x1F\x80-\xFF]/' => '',
        ];

        return preg_replace(array_keys($fixes), array_values($fixes), $json);
    }
}
