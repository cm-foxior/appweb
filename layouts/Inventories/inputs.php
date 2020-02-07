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

<main class="body <?php if (Session::getValue('level') == 8) : echo 'droped'; endif; ?>">
    <div class="content">
        <div class="box-tab-buttons">
            <a href="" class="view" button-tab>Entradas</a>
            <a href="/inventories/outputs/{$idInventory}" button-tab>Salidas</a>
            <div class="clear"></div>
        </div>
        <div class="box-buttons not-margin">
            <a data-button-modal="inputs"><i class="material-icons">add</i><span>Nuevo</span></a>
            <a href="/inventories"><i class="material-icons">arrow_back</i><span>Regresar</span></a>
            <div class="clear"></div>
        </div>
        <div class="title">
            <h2>{$title}</h2>
        </div>
        <div class="table-responsive-vertical padding">
            <table id="inputsTable" class="display" data-page-length="100">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Proveedor</th>
                        <th>Fecha y hora</th>
                        <th>Tipo</th>
                        <th>Compra</th>
                        <th width="35px"></th>
                    </tr>
                </thead>
                <tbody>
                    {$lstInputs}
                </tbody>
            </table>
        </div>
    </div>
</main>

<section class="modal" data-modal="inputs">
    <div class="content">
        <header>
            <h6>Nuevo entrada</h6>
        </header>
        <main>
            <form name="inputs" class="row" data-submit-action="new">
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
                <fieldset class="input-group span4">
                    <label data-important>
                        <span><span class="required-field">*</span>Cantidad</span>
                        <input type="number" name="quantify">
                    </label>
                </fieldset>
                <fieldset class="input-group span4">
                    <label data-important>
                        <span><span class="required-field">*</span>Tipo</span>
                        <select name="type">
                            <option value="1">Compra</option>
                            <option value="3">Devolución de venta</option>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group span4">
                    <label data-important>
                        <span>Precio de compra</span>
                        <input type="number" name="price">
                    </label>
                </fieldset>
                <?php if (Session::getValue('level') == 10) : ?>
                <fieldset class="input-group span6">
                    <label data-important>
                        <span>Fecha</span>
                        <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>">
                    </label>
                </fieldset>
                <fieldset class="input-group span6">
                    <label data-important>
                        <span>Hora</span>
                        <input type="time" name="hour" value="<?php echo date('H:i:s', time()); ?>">
                    </label>
                </fieldset>
                <?php endif; ?>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Proveedor</span>
                        <select name="provider" class="chosen-select">
                            <option value="">Sin proveedor</option>
                            {$lstProviders}
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

<section class="modal" data-modal="transferInfo">
    <div class="content">
        <header>
            <h6>Transferido desde:</h6>
        </header>
        <main>
            <form>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Inventario</span>
                        <input id="transferInfoInventory" type="text" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Sucursal</span>
                        <input id="transferInfoBranchOffice" type="text" disabled>
                    </label>
                </fieldset>
            </form>
        </main>
        <footer>
            <a button-close>Aceptar</a>
        </footer>
    </div>
</section>
