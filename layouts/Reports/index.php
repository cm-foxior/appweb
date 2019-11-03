<?php

defined('_EXEC') or die;

$this->dependencies->getDependencies([
    'css' => [

    ],
    'js' => [
        '{$path.js}pages/settings.js'
    ],
    'other' => [

    ]
]);
?>

%{header}%

<main class="body">
    <div class="content"></div>
</main>
