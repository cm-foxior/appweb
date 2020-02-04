<?php

defined('_EXEC') or die;

$this->dependencies->getDependencies([
    'css' => [

    ],
    'js' => [
        '{$path.js}pages/dashboard.js'
    ],
    'other' => [

    ]
]);
?>
