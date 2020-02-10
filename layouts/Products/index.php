<?php

defined('_EXEC') or die;

$this->dependencies->getDependencies([
    'css' => [
        '{$path.plugins}DataTables/css/jquery.dataTables.min.css',
        '{$path.plugins}DataTables/css/dataTables.material.min.css',
        '{$path.plugins}DataTables/css/responsive.dataTables.min.css',
        '{$path.plugins}DataTables/css/buttons.dataTables.min.css',
        '{$path.plugins}fancybox/source/jquery.fancybox.css'
    ],
    'js' => [
        '{$path.js}pages/products.js',
        '{$path.plugins}DataTables/js/jquery.dataTables.min.js',
        '{$path.plugins}DataTables/js/dataTables.material.min.js',
        '{$path.plugins}DataTables/js/dataTables.responsive.min.js',
        '{$path.plugins}DataTables/js/dataTables.buttons.min.js',
        '{$path.plugins}DataTables/js/pdfmake.min.js',
        '{$path.plugins}DataTables/js/vfs_fonts.js',
        '{$path.plugins}DataTables/js/buttons.html5.min.js',
        '{$path.plugins}fancybox/source/jquery.fancybox.pack.js',
        '{$path.plugins}fancybox/source/jquery.fancybox.js'
    ],
    'other' => [
        '<script type="text/javascript">
            $(document).ready(function()
            {
                $(".fancybox-thumb").fancybox({
                    openEffect  : "elastic",
                    closeEffect : "elastic",
                    prevEffect	: "none",
    		        nextEffect	: "none",
                    padding     : "0",
                    helpers	:
                    {
                    	thumbs	:
                        {
                    		width	: 50,
                    		height	: 50
                    	}
                    }
	           });
            });
        </script>'
    ]
]);
?>

%{header}%
<main class="body">
    <div class="content">
        <div class="box-buttons">
            <a data-button-modal="deleteProducts"><i class="material-icons">delete</i><span>Eliminar</span></a>
            <!-- <a data-button-modal="deactivateProducts"><i class="material-icons">block</i><span>Desactivar</span></a>
            <a data-button-modal="activateProducts"><i class="material-icons">check</i><span>Activar</span></a>
            <a data-button-modal="importFromExcel"><i class="material-icons">cloud_upload</i><span>Importar desde excel</span></a> -->
            <a data-button-modal="products"><i class="material-icons">add</i><span>Nuevo</span></a>
            <a href="/products/flirts"><i class="material-icons">link</i><span>Productos ligados</span></a>
            <a href="/products/categories_one"><i class="material-icons">turned_in</i><span>Categorías</span></a>
            <a href="/products/tags/all"><i class="material-icons">local_offer</i><span>Etiquetas</span></a>
        </div>
        <div class="table-responsive-vertical padding">
            <table id="tblProducts" class="display" data-page-length="100">
                <thead>
                    <tr>
                        <th width="20px"></th>
                        <th width="40px"></th>
                        <th width="100px">Folio</th>
                        <th>Nombre</th>
                        <th>Categorías</th>
                        <th width="50px">Unidad</th>
                        <th width="180px">Precio</th>
                        <th width="50px">Tipo</th>
                        <th width="100px">Estado</th>
                        <th width="35px"></th>
                    </tr>
                </thead>
                <tbody>
                    {$tblProducts}
                </tbody>
            </table>
        </div>
    </div>
</main>
{$mdlProducts}
<section class="modal" data-modal="importFromExcel">
    <div class="content">
        <header>
            <h6>Importar prospectos desde Excel</h6>
        </header>
        <main>
            <form name="importFromExcel">
                <fieldset class="input-group">
                    <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Excel</span>
                        <input name="xlsx" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
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
<section class="modal alert" data-modal="activateProducts">
    <div class="content">
        <header>
            <h6>Alerta</h6>
        </header>
        <main>
            <p>¿Está seguro de que desea activar está selección de productos?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="activateProducts">Aceptar</a>
        </footer>
    </div>
</section>
<section class="modal alert" data-modal="deactivateProducts">
    <div class="content">
        <header>
            <h6>Alerta</h6>
        </header>
        <main>
            <p>¿Está seguro de que desea desactivar está selección de productos?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="deactivateProducts">Aceptar</a>
        </footer>
    </div>
</section>
<section class="modal alert" data-modal="deleteProducts">
    <div class="content">
        <header>
            <h6>Alerta</h6>
        </header>
        <main>
            <p>¿Está seguro de que desea eliminar está selección de productos?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="deleteProducts">Aceptar</a>
        </footer>
    </div>
</section>
