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
        '{$path.js}pages/directory.js',
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
            {$btnDeleteContacts}
            <a data-button-modal="contacts"><i class="material-icons">add</i><span>Nuevo</span></a>
            {$btnCategories}
            <div class="clear"></div>
        </div>
        <div class="table-responsive-vertical padding">
            <table id="tblContacts" class="display" data-page-length="100">
                <thead>
                    <tr>
                        <th width="20px"></th>
                        <th>Nombre</th>
                        <th>Correo electrónico</th>
                        <th>Número Teléfonico</th>
                        <th>Cliente</th>
                        <th>Categoría</th>
                        <th width="35px"></th>
                    </tr>
                </thead>
                <tbody>
                    {$lstContacts}
                </tbody>
            </table>
        </div>
    </div>
</main>

<section class="modal" data-modal="contacts">
    <div class="content">
        <header>
            <h6>Nuevo contacto</h6>
        </header>
        <main>
            <form name="contacts" data-submit-action="new">
                <fieldset class="input-group">
                    <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Nombre</span>
                        <input type="text" name="name" autofocus>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Email</span>
                        <input type="email" name="email">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Número telefónico</span>
                        <select name="phoneCountryCode" class="span2 chosen-select">
                            {$lstCountriesPhoneCodes}
                        </select>
                        <input type="number" name="phoneNumber" class="span8 margin-left-right">
                        <select name="phoneType" class="span2">
                            <option value="Móvil">Móvil</option>
                            <option value="Local">Local</option>
                        </select>
                        <div class="clear"></div>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Cliente</span>
                        <select name="client" class="chosen-select">
                            <option value="">Sin cliente</option>
                            {$lstClients}
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Categoría</span>
                        <select name="category" class="chosen-select">
                            <option value="">Sin categoría</option>
                            {$lstCategories}
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

{$mdlDeleteContacts}
