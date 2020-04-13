<?php

namespace App\Traits;

trait StringAdditions
{
    /**
     * Check if a given string starts with a given substring.
     * @see https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php#834355
     */
    function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * Check if a given string ends with a given substring.
     * @see https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php#834355
     */
    function endsWith($haystack, $needle)
    {
       $length = strlen($needle);

       return $length === 0 ||
       (substr($haystack, -$length) === $needle);
    }

    /**
     * Check if a specific substring is within a string
     */
    function contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}