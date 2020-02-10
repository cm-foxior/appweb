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
            <a href="/products/tags" class="view" button-tab>Etiquetas</a>
            <div class="clear"></div>
        </div>
        <div class="padding">
            <div id="tags" class="tags-papers">
                {$lstTags}
            </div>
            <div class="tags-options">
                {$frmCreateFreeList}
                <form name="updatePrintOptions">
                    {$fdtSearchDate}
                    {$fdtItemsNumber}
                    <fieldset class="input-group">
                        <label data-important>
                            <span>Lado de impresión</span>
                            <select name="printSide">
                                <option value="A">Impresión de folios</option>
                                <option value="B">Impresión de logotipos</option>
                            </select>
                        </label>
                        <a data-action="updatePrintOptions">Actualizar</a>
                        <a data-action="createFreeList" class="color-gray">Crear lista</a>
                        <a data-action="deleteFreeList" class="color-gray hidden">Eliminar lista</a>
                        <a data-action="printTags" class="color-gray">Imprimir</a>
                    </fieldset>
                </form>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</main>
