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
        '{$path.js}pages/warranties.js',
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
            <a data-button-modal="deleteWarranties"><i class="material-icons">delete</i><span>Eliminar</span></a>
            <a data-button-modal="warranties"><i class="material-icons">add</i><span>Nuevo</span></a>
            <div class="clear"></div>
        </div>
        <div class="table-responsive-vertical padding">
            <table class="display" data-page-length="100">
                <thead>
                    <tr>
                        <th width="20px"></th>
                        <th>Garantía</th>
                        <th width="35px"></th>
                    </tr>
                </thead>
                <tbody>
                    {$lstWarranties}
                </tbody>
            </table>
        </div>
    </div>
</main>

<section class="modal" data-modal="warranties">
    <div class="content">
        <header>
            <h6>Nueva garantía</h6>
        </header>
        <main>
            <form name="warranties" data-submit-action="new">
                <fieldset class="input-group">
                    <label data-important>
                        <span>Cantidad de tiempo</span>
                        <input type="number" name="quantity" maxlength="4">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Periodo de tiempo</span>
                        <select name="timeFrame">
                            <option value="">Selecciona una opción</option>
                            <option value="1">Días</option>
                            <option value="2">Meses</option>
                            <option value="3">Años</option>
                        </select>
                    </label>
                </fieldset>
            </form>
        </main>
        <footer>
            <a button-cancel>Cancelar</a>
            <a button-success>Aceptar</a>
        </footer>
    </div>
</section>

<section class="modal alert" data-modal="deleteWarranties">
    <div class="content">
        <header>
            <h6>Alerta</h6>
        </header>
        <main>
            <p>¿Está seguro de que desea eliminar está selección de garantías?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="deleteWarranties">Aceptar</a>
        </footer>
    </div>
</section>
