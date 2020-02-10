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
            <a href="/inventories" button-tab>Inventarios</a>
            <a href="/inventories/inputs/{$idInventory}" button-tab>Entradas</a>
            <a href="/inventories/outputs/{$idInventory}" button-tab>Salidas</a>
            <a href="/inventories/stocks/{$idInventory}" class="view" button-tab>Stocks</a>
            <div class="clear"></div>
        </div>
        <div class="title">
            <h2>{$title}</h2>
        </div>
        <div class="box-buttons">
            <?php if (Session::getValue('level') == 10) : ?>
            <a data-button-modal="deleteStocks"><i class="material-icons">delete</i><span>Eliminar</span></a>
            <?php endif; ?>
            <a data-button-modal="stocks"><i class="material-icons">add</i><span>Nuevo</span></a>
            <div class="clear"></div>
        </div>
        <div class="table-responsive-vertical padding">
            {$tblStocks}
        </div>
    </div>
</main>
<section class="modal" data-modal="stocks">
    <div class="content">
        <header>
            <h6>Nuevo Stock</h6>
        </header>
        <main>
            <form name="stocks" data-submit-action="new">
                <fieldset class="input-group">
                    <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Mínimo</span>
                        <input type="number" name="min" min="1" autofocus>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Máximo</span>
                        <input type="number" name="max" min="1">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Producto</span>
                        <select name="product" class="chosen-select">
                            {$lstProducts}
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
<?php if (Session::getValue('level') == 10) : ?>
    <section class="modal alert" data-modal="deleteStocks">
        <div class="content">
            <header>
                <h6>Alerta</h6>
            </header>
            <main>
                <p>¿Está seguro de que desea eliminar este stock?</p>
            </main>
            <footer>
                <a button-close>Cancelar</a>
                <a data-action="deleteStocks">Aceptar</a>
            </footer>
        </div>
    </section>
<?php endif; ?>
