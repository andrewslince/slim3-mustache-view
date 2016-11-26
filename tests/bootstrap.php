<?php

include realpath(__DIR__ . DIRECTORY_SEPARATOR . '..') .
    DIRECTORY_SEPARATOR . 'vendor/autoload.php';

/**
 * Debug variables
 * @author Andrews Lince <andrews.lince@gmail.com>
 * @since  1.0.3
 * @param  mixed   $param
 * @param  boolean $exit
 * @return void
 */
function dbg($param, $exit = true)
{
    echo PHP_EOL;

    print_r($param);

    echo PHP_EOL;

    if ($exit) {
        exit;
    }
}
