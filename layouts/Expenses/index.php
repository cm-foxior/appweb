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
        '{$path.js}pages/expenses.js',
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
            <a data-button-modal="delete"><i class="material-icons">delete</i><span>Eliminar</span></a>
            <a data-button-modal="expenses"><i class="material-icons">add</i><span>Nuevo</span></a>
            <div class="clear"></div>
        </div>
        <div class="table-responsive-vertical padding">
            <table class="display" data-page-length="100">
                <thead>
                    <tr>
                        <th width="20px"></th>
                        <th width="170px">Fecha</th>
                        <th>Nombre</th>
                        <th width="100px">Factura</th>
                        <th width="35px"></th>
                    </tr>
                </thead>
                <tbody>
                    {$tbl}
                </tbody>
            </table>
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
                    <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Nombre</span>
                        <input type="text" name="name" autofocus>
                    </label>
                </fieldset>
                <fieldset class="input-group span4 pr">
                    <label data-important>
                        <span>Factura</span>
                        <input type="text" name="bill">
                    </label>
                </fieldset>
                <fieldset class="input-group span4 pr">
                    <label data-important>
                        <span>Costo</span>
                        <input type="number" name="cost">
                    </label>
                </fieldset>
                <fieldset class="input-group span4">
                    <label data-important>
                        <span>Pago</span>
                        <select name="payment">
                            <option value="">Sin pago</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Cheque">Cheque</option>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group span6 pr">
                    <label data-important>
                        <span><span class="required-field">*</span>Fecha</span>
                        <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>">
                    </label>
                </fieldset>
                <fieldset class="input-group span6">
                    <label data-important>
                        <span><span class="required-field">*</span>Hora</span>
                        <input type="time" name="hour" value="<?php echo date('H:i:s', time()); ?>">
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
<section class="modal alert" data-modal="delete">
    <div class="content">
        <header>
            <h6>Alerta</h6>
        </header>
        <main>
            <p>¿Está seguro de que desea eliminar está selección de gastos?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="delete">Aceptar</a>
        </footer>
    </div>
</section>
