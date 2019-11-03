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
        '{$path.js}pages/boxcuts.js',
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
        <div class="box-buttons">
            <a href="/boxcuts"><i class="material-icons">arrow_back</i><span>Regresar</span></a>
            <div class="clear"></div>
        </div>
        <form name="searchTotals" class="padding">
            <fieldset class="input-group span3 pr">
                <label data-important>
                    <span>Inicio</span>
                    <input type="datetime-local" name="startDateTime">
                </label>
            </fieldset>
            <fieldset class="input-group span3 pr">
                <label data-important>
                    <span>Término</span>
                    <input type="datetime-local" name="endDateTime">
                </label>
            </fieldset>
            <fieldset class="input-group span3 pr">
                <label data-important>
                    <span>Vendedor</span>
                    <select name="seller" class="chosen-select">
                        <option value="">Seleccione una opción</option>
                        <option value="all">Todos los vendedores</option>
                        {$lstSellers}
                    </select>
                </label>
            </fieldset>
            {$lstBranchOffices}
            <div class="clear"></div>
            <fieldset class="input-group span3 pr">
                <label data-important>
                    <span>Inicio de caja</span>
                    <input type="number" name="startTotal">
                </label>
            </fieldset>
            <fieldset class="input-group span3 pr">
                <label data-important>
                    <span>Total de pesos en caja</span>
                    <input type="number" name="mxnTotal">
                </label>
            </fieldset>
            <fieldset class="input-group span3 pr">
                <label data-important>
                    <span>Total de dólares en caja</span>
                    <input type="number" name="usdTotal">
                </label>
            </fieldset>
            <fieldset class="input-group span3">
                <div class="space20"></div>
                <a data-button-modal="searchTotals">Buscar totales</a>
                <a href="/boxcuts/expenses">Subir gastos</a>
            </fieldset>
            <div class="clear"></div>
        </form>
    </div>
</main>

<section class="modal alert" data-modal="searchTotals">
    <div class="content">
        <header>
            <h6>Alerta</h6>
        </header>
        <main>
            <p>Para que los gastos adicionales se apliquen en este corte de caja tiene que subirlos primeramente.</p>
        </main>
        <footer>
            <a href="/boxcuts/expenses" button-close>Subir gastos</a>
            <a data-action="searchTotals">Buscar totales</a>
        </footer>
    </div>
</section>
