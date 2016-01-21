<?php

if (!function_exists('array_merge_recursive_config')) {
    function array_merge_recursive_config(array $array1, array $array2)
    {
        $merged = $array1;
    
        foreach ($array2 as $key => &$value) {
            if (is_null($value)) {
                // we want to unset the key in $array1
                unset($merged[$key]);
            } elseif (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = array_merge_recursive_config($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }
    
        return $merged;
    }
}
