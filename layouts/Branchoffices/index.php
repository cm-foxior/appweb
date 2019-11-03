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
        '{$path.js}pages/branchoffices.js',
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
            <a data-button-modal="deleteBranchOffices"><i class="material-icons">delete</i><span>Eliminar</span></a>
            <a data-button-modal="branchOffices"><i class="material-icons">add</i><span>Nuevo</span></a>
            <!-- <a data-button-modal="deactivateBranchOffices"><i class="material-icons">block</i><span>Desactivar</span></a>
            <a data-button-modal="activateBranchOffices"><i class="material-icons">check</i><span>Activar</span></a> -->
            <div class="clear"></div>
        </div>
        <div class="table-responsive-vertical padding">
            <table class="display" data-page-length="100">
                <thead>
                    <tr>
                        <th width="20px"></th>
                        <th>Nombre</th>
                        <th width="100px">Estado</th>
                        <th width="35px"></th>
                    </tr>
                </thead>
                <tbody>
                    {$lstBranchOffices}
                </tbody>
            </table>
        </div>
    </div>
</main>

<section class="modal" data-modal="branchOffices">
    <div class="content">
        <header>
            <h6>Nueva sucursal</h6>
        </header>
        <main>
            <form name="branchOffices" data-submit-action="new">
                <fieldset class="input-group">
                    <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Nombre</span>
                        <input type="text" name="name" autofocus>
                    </label>
                </fieldset>
                <h4 class="title margin-top-30">Información de contacto</h4>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Email</span>
                        <input type="email" name="email">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Número telefónico</span>
                        <select name="phoneCountryCode" class="span2">
                            <option value="52">[+52] México</option>
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
                        <span><span class="required-field">*</span>Dirección</span>
                        <input type="text" name="address">
                    </label>
                </fieldset>
                <h4 class="title margin-top-30">Información fiscal</h4>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>País</span>
                        <select name="fiscalCountry">
                            <option value="México">México</option>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Razón social</span>
                        <input type="text" name="fiscalName">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>RFC</span>
                        <input type="text" name="fiscalCode" class="uppercase">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Régimen Fiscal</span>
                        <input type="text" name="fiscalRegime">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Dirección fiscal</span>
                        <input type="text" name="fiscalAddress">
                    </label>
                    <label class="checkbox" data-important>
                        <input type="checkbox" data-action="assignSameAddress">
                        <span>Usar dirección principal</span>
                        <div class="clear"></div>
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
<section class="modal alert" data-modal="activateBranchOffices">
    <div class="content">
        <header>
            <h6>Alerta</h6>
        </header>
        <main>
            <p>¿Está seguro de que desea activar está selección de sucursales?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="activateBranchOffices">Aceptar</a>
        </footer>
    </div>
</section>
<section class="modal alert" data-modal="deactivateBranchOffices">
    <div class="content">
        <header>
            <h6>Alerta</h6>
        </header>
        <main>
            <p>¿Está seguro de que desea desactivar está selección de sucursales?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="deactivateBranchOffices">Aceptar</a>
        </footer>
    </div>
</section>
<section class="modal alert" data-modal="deleteBranchOffices">
    <div class="content">
        <header>
            <h6>Alerta</h6>
        </header>
        <main>
            <p>¿Está seguro de que desea eliminar está selección de sucursales?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="deleteBranchOffices">Aceptar</a>
        </footer>
    </div>
</section>
