<?php

if (!function_exists('decamelize')) {
    /**
     * Decamelize the given string.
     *
     * @param string $input
     * @return string
     */
    function decamelize($input)
    {
        $matches = null;
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}
