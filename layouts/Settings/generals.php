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
    <div class="content">
        <div class="box-tab-buttons">
            <!-- <a href="/settings/generals" class="view" button-tab>Generales</a> -->
            <a href="/settings/business" button-tab>Mi empresa</a>
            <a href="/settings/sales" button-tab>Ventas</a>
            <div class="clear"></div>
        </div>
    </div>
</main>
