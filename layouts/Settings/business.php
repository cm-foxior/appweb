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
        '{$path.js}pages/settings.js',
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
            <!-- <a href="/settings/generals" button-tab>Generales</a> -->
            <a href="/settings/business" class="view" button-tab>Mi empresa</a>
            <a href="/settings/sales" button-tab>Ventas</a>
            <div class="clear"></div>
        </div>
        <div class="padding">
            <div class="span6 pr">
                <div class="upload-image no-margin">
                    <div class="image-preview" data-image-src="{$logotype}"></div>
                </div>
            </div>
            <div class="span6 pl">
                <fieldset class="input-group">
                    <label data-important>
                        <span>Nombre</span>
                        <input type="text" value="{$name}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Sitio Web</span>
                        <input type="text" value="{$website}" disabled>
                    </label>
                </fieldset>
            </div>
            <div class="clear"></div>
            <fieldset class="input-group">
                <a data-action="getBusinessSettingsToEdit">Editar</a>
            </fieldset>
        </div>
    </div>
</main>

<section class="modal" data-modal="editBusinessSettings">
    <div class="content">
        <header>
            <h6>Editar</h6>
        </header>
        <main>
            <form name="editBusinessSettings">
                <fieldset class="input-group">
                    <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Nombre</span>
                        <input type="text" name="name">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Sitio Web</span>
                        <input type="text" name="website">
                    </label>
                </fieldset>
                <div class="upload-image">
                    <div class="image-preview" image-preview="image-preview"></div>
                    <a select-image>Seleccionar imagen</a>
                    <a clear-image>Eliminar imagen</a>
                    <input id="image-preview" name="logotype" type="file" accept="image/*" image-preview="image-preview"/>
                </div>
            </form>
        </main>
        <footer>
            <a button-cancel>Cancelar</a>
            <a button-success>Aceptar</a>
        </footer>
    </div>
</section>
