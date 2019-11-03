<?php

defined('_EXEC') or die;

$this->dependencies->getDependencies([
    'js' => [
        'https://www.google.com/jsapi',
        '{$path.js}pages/reports.js',
        '{$path.js}pages/graphs.js'
    ]
]);
?>

%{header}%

<main class="body">
    <div class="content">
        <div class="box-tab-buttons">
            <a href="/reports/inventories/existence" button-tab>Invetarios</a>
            <a href="/reports/sales" button-tab>Ventas</a>
            <a href="/reports/graphs" class="view" button-tab>Estadísticas</a>
            <div class="clear"></div>
        </div>
        <div class="statistics">
            <div class="row">
                <div class="span12">
                    <h1>Estadisticas de venta</h1>
                </div>
                <div class="span12">
                    <hr />
                </div>
                <div class="clear"></div>
                <div class="span6">
                    <div id="chart_div1" class="chart"></div>
                </div>
                <div class="span6">
                    <div id="chart_div2" class="chart"></div>
                </div>
            </div>
        </div>
    </div>
</main>
