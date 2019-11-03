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
        '{$path.js}pages/settings.js',
        '{$path.plugins}DataTables/js/jquery.dataTables.min.js',
        '{$path.plugins}DataTables/js/dataTables.material.min.js',
        '{$path.plugins}DataTables/js/dataTables.responsive.min.js',
        '{$path.plugins}DataTables/js/dataTables.buttons.min.js',
        '{$path.plugins}DataTables/js/pdfmake.min.js',
        '{$path.plugins}DataTables/js/vfs_fonts.js',
        '{$path.plugins}DataTables/js/buttons.html5.min.js'
    ],
    'other' => [

    ]
]);
?>

%{header}%

<main class="body">
    <div class="content">
        <div class="box-tab-buttons">
            <!-- <a href="/settings/generals" button-tab>Generales</a> -->
            <a href="/settings/business" button-tab>Mi Empresa</a>
            <a href="/settings/sales" class="view" button-tab>Ventas</a>
            <div class="clear"></div>
        </div>
        <div class="padding">
            {$htmlSalesSettings}
            {$tblPdisSettings}
        </div>
    </div>
</main>

{$mdlEditSalesSettings}

<section class="modal" data-modal="updatePdisSettings">
    <div class="content">
        <header>
            <h6>Actualizar configuraciones PDIS</h6>
        </header>
        <main class="hidden">
            <p></p>
        </main>
        <footer>
            <a button-cancel>Cancelar</a>
            <a data-action="updatePdisSettings">Aceptar</a>
        </footer>
    </div>
</section>
