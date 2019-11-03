<?php

defined('_EXEC') or die;

$this->dependencies->getDependencies([
    'css' => [
        '{$path.plugins}DataTables/css/jquery.dataTables.min.css',
        '{$path.plugins}DataTables/css/dataTables.material.min.css',
        '{$path.plugins}DataTables/css/responsive.dataTables.min.css',
        '{$path.plugins}DataTables/css/buttons.dataTables.min.css'
    ],
    'js' => [
        '{$path.js}pages/reports.js',
        '{$path.plugins}DataTables/js/jquery.dataTables.min.js',
        '{$path.plugins}DataTables/js/dataTables.material.min.js',
        '{$path.plugins}DataTables/js/dataTables.responsive.min.js',
        '{$path.plugins}DataTables/js/dataTables.buttons.min.js',
        '{$path.plugins}DataTables/js/pdfmake.min.js',
        '{$path.plugins}DataTables/js/vfs_fonts.js',
        '{$path.plugins}DataTables/js/buttons.html5.min.js'
    ]
]);
?>

%{header}%

<main class="body">
    <div class="content">
        <div class="box-tab-buttons">
            <a href="/reports/inventories/existence" class="view" button-tab>Invetarios</a>
            <a href="/reports/sales" button-tab>Ventas</a>
            <!-- <a href="/reports/graphs" button-tab>Estadísticas</a> -->
            <div class="clear"></div>
        </div>
        <div class="padding">
            {$html}
        </div>
    </div>
</main>
