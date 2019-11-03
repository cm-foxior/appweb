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
        '{$path.js}pages/clients.js',
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
            {$btnDeleteClients}
            <a data-button-modal="clients"><i class="material-icons">add</i><span>Nuevo</span></a>
            <!-- {$btnDeactivateClients}
            {$btnActivateClients} -->
            <a data-button-modal="sendMassEmail"><i class="material-icons">mail</i><span>Enviar correo masivo</span></a>
            <div class="clear"></div>
        </div>
        <div class="table-responsive-vertical padding">
            <table class="display" data-page-length="100">
                <thead>
                    <tr>
                        <th width="20px"></th>
                        <th>Nombre</th>
                        <th>Correo electrónico</th>
                        <th>Número Teléfonico</th>
                        <th>RFC</th>
                        <th>Tipo</th>
                        <th width="100px">Estado</th>
                        <th width="35px"></th>
                    </tr>
                </thead>
                <tbody>
                    {$lstClients}
                </tbody>
            </table>
        </div>
    </div>
</main>

<section class="modal" data-modal="clients">
    <div class="content">
        <header>
            <h6>Nuevo cliente</h6>
        </header>
        <main>
            <form name="clients" data-submit-action="new">
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
                        <span><span class="required-field">*</span>Tipo</span>
                        <select name="type">
                            <option value="Minorista">Minorista</option>
                            <option value="Mayorista">Mayorista</option>
                            <option value="Empresarial">Empresarial</option>
                        </select>
                    </label>
                </fieldset>
                <h4 class="title margin-top-30">Información de contacto</h4>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Email</span>
                        <input type="email" name="email">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Número telefónico</span>
                        <select name="phoneCountryCode" class="span2">
                            <option value="52">[+52] México</option>
                            <!-- {$lstCountriesPhoneCodes} -->
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
                        <span>Dirección</span>
                        <input type="text" name="address">
                    </label>
                </fieldset>
                <h4 class="title margin-top-30">Información fiscal</h4>
                <fieldset class="input-group">
                    <label data-important>
                        <span>País</span>
                        <select name="fiscalCountry">
                            <option value="México">México</option>
                            <!-- {$lstCountries} -->
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span id="fiscalName">Razón social</span>
                        <input type="text" name="fiscalName">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span id="fiscalCode">RFC</span>
                        <input type="text" name="fiscalCode" class="uppercase">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Dirección fiscal</span>
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
<section class="modal" data-modal="sendMassEmail">
    <div class="content">
        <header>
            <h6>Enviar correo masivo</h6>
        </header>
        <main>
            <form name="sendMassEmail">
                <fieldset class="input-group">
                    <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Asunto</span>
                        <input type="text" name="subject" autofocus>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Mensaje</span>
                        <textarea name="message"></textarea>
                    </label>
                </fieldset>
                <div class="upload-image">
                    <div class="image-preview" image-preview="image-preview"></div>
                    <a select-image>Seleccionar imagen</a>
                    <a clear-image>Eliminar imagen</a>
                    <input id="image-preview" name="image" type="file" accept="image/*" image-preview="image-preview"/>
                </div>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Enviar a</span>
                        <select name="sendTo">
                            <option value="all">Todos los clientes</option>
                            <option value="selected">Clientes seleccionados</option>
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
{$mdlActivateClients}
{$mdlDeactivateClients}
{$mdlDeleteClients}
