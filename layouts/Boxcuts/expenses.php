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
            <a data-button-modal="expenses"><i class="material-icons">add</i><span>Nuevo</span></a>
            <a href="/boxcuts"><i class="material-icons">arrow_back</i><span>Regresar</span></a>
            <div class="clear"></div>
        </div>
        <div class="table-responsive-vertical padding">
            {$tblExpenses}
        </div>
    </div>
</main>

<section class="modal" data-modal="expenses">
    <div class="content">
        <header>
            <h6>Nuevo gasto</h6>
        </header>
        <main>
            <form name="expenses" data-submit-action="new">
                <fieldset class="input-group">
                    <label data-important>
                        <span>Fecha</span>
                        <input type="datetime-local" name="dateTime">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Total</span>
                        <input type="number" name="total">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Descripción</span>
                        <textarea name="description"></textarea>
                    </label>
                </fieldset>
                {$lstBranchOffices}
            </form>
        </main>
        <footer>
            <a button-cancel>Cancelar</a>
            <a button-success>Aceptar</a>
        </footer>
    </div>
</section>
