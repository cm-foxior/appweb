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
        '{$path.js}pages/inventories.js',
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
            <a data-button-modal="loans"><i class="material-icons">add</i><span>Nuevo</span></a>
            <a href="/inventories"><i class="material-icons">arrow_back</i><span>Regresar</span></a>
            <div class="clear"></div>
        </div>
        <div class="title">
            <h2>{$title}</h2>
        </div>
        <div class="table-responsive-vertical padding">
            <table id="tblLoans" class="display" data-page-length="100">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th width="35px"></th>
                    </tr>
                </thead>
                <tbody>
                    {$lstLoans}
                </tbody>
            </table>
        </div>
    </div>
</main>

<section class="modal" data-modal="loans">
    <div class="content">
        <header>
            <h6>Nuevo prestamo</h6>
        </header>
        <main>
            <form name="loans">
                <fieldset class="input-group">
                    <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Producto</span>
                        <select name="product" class="chosen-select">
                            {$lstProducts}
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Cantidad</span>
                        <input type="number" name="quantity" value="0">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Cliente</span>
                        <select name="client" class="chosen-select">
                            {$lstClients}
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group span6 pr">
                    <label data-important>
                        <span><span class="required-field">*</span>Fecha</span>
                        <input type="date" name="date" value="{$date}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group span6">
                    <label data-important>
                        <span><span class="required-field">*</span>Hora</span>
                        <input type="time" name="time" value="{$time}" disabled>
                    </label>
                    <label class="checkbox" data-important>
                        <input type="checkbox" name="setDateTime">
                        <span>Ajustar fecha y hora</span>
                        <div class="clear"></div>
                    </label>
                </fieldset>
                <div class="clear"></div>
            </form>
        </main>
        <footer>
            <a button-cancel>Cancelar</a>
            <a button-success>Aceptar</a>
        </footer>
    </div>
</section>

<section class="modal alert" data-modal="closeLoan">
    <div class="content">
        <header>
            <h6>Alerta</h6>
        </header>
        <main>
            <p>¿Está seguro de que desea realizar esta acción?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="closeLoan">Aceptar</a>
        </footer>
    </div>
</section>
