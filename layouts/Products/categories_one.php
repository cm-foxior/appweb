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
        <div class="box-buttons">
            <a data-button-modal="deleteCategories"><i class="material-icons">delete</i><span>Eliminar</span></a>
            <a data-button-modal="categories" data-number="one"><i class="material-icons">add</i><span>Nuevo</span></a>
            <a href="/products"><i class="material-icons">arrow_back</i><span>Regresar</span></a>
            <div class="clear"></div>
        </div>
        <div class="box-tab-buttons">
            <a href="/products/categories_one" class="view" button-tab>Categorías</a>
            <a href="/products/categories_two" button-tab>Subcategorías 1</a>
            <a href="/products/categories_tree" button-tab>Subcategorías 2</a>
            <a href="/products/categories_four" button-tab>Subcategorías 3</a>
            <div class="clear"></div>
        </div>
        <div class="table-responsive-vertical padding">
            <table id="tblCategories" class="display" data-page-length="100" data-table-order="2">
                <thead>
                    <tr>
                        <th width="20px"></th>
                        <th width="40px"></th>
                        <th>Categoría</th>
                        <th width="35px"></th>
                    </tr>
                </thead>
                <tbody>
                    {$lstCategories}
                </tbody>
            </table>
        </div>
    </div>
</main>

<section class="modal" data-modal="categories">
    <div class="content">
        <header>
            <h6>Nueva categoría</h6>
        </header>
        <main>
            <form name="categories" data-submit-action="new">
                <fieldset class="input-group">
                    <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Nombre</span>
                        <input type="text" name="name" autofocus>
                    </label>
                </fieldset>
                <div class="upload-image">
                    <div class="image-preview" image-preview="image-preview"></div>
                    <a select-image>Seleccionar imagen</a>
                    <input id="image-preview" name="avatar" type="file" accept="image/*" image-preview="image-preview"/>
                </div>
            </form>
        </main>
        <footer>
            <a button-cancel>Cancelar</a>
            <a button-success>Aceptar</a>
        </footer>
    </div>
</section>

<section class="modal alert" data-modal="deleteCategories">
    <div class="content">
        <header>
            <h6>Alerta</h6>
        </header>
        <main>
            <p>¿Esta seguro que desea eliminar esta selección de categorías?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="deleteCategories">Aceptar</a>
        </footer>
    </div>
</section>
