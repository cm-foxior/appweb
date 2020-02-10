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
        '{$path.js}pages/products.js',
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
            <a href="/products" button-tab>Productos</a>
            <a href="/products/flirts" class="view" button-tab>Productos ligados</a>
            <div class="clear"></div>
        </div>
        <div class="box-buttons">
            <a data-button-modal="deleteFlirts"><i class="material-icons">delete</i><span>Eliminar</span></a>
            <a data-button-modal="flirts" data-number="one"><i class="material-icons">add</i><span>Nuevo</span></a>
            <div class="clear"></div>
        </div>
        <div class="table-responsive-vertical padding">
            <table id="tblFlirts" class="display" data-page-length="100" data-table-order="1">
                <thead>
                    <tr>
                        <th></th>
                        <th>Producto ligado</th>
                        <th>Producto principal</th>
                        <th>Stock base</th>
                        <th>Stock actual</th>
                        <th width="35px"></th>
                    </tr>
                </thead>
                <tbody>
                    {$lstFlirts}
                </tbody>
            </table>
        </div>
    </div>
</main>

<section class="modal" data-modal="flirts">
    <div class="content">
        <header>
            <h6>Nuevo producto ligado</h6>
        </header>
        <main>
            <form name="flirts" data-submit-action="new">
                <fieldset class="input-group">
                    <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Producto ligado</span>
                        <select name="product_1" class="chosen-select">
                            <option value="">Seleccionar</option>
                            {$lstProducts}
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Producto principal</span>
                        <select name="product_2" class="chosen-select">
                            <option value="">Seleccionar</option>
                            {$lstProducts}
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Stock base</span>
                        <input type="text" name="stock_base">
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

<section class="modal alert" data-modal="deleteFlirts">
    <div class="content">
        <header>
            <h6>Alerta</h6>
        </header>
        <main>
            <p>¿Esta seguro que desea eliminar esta selección de productos ligados?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="deleteFlirts">Aceptar</a>
        </footer>
    </div>
</section>
