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
        '{$path.js}pages/prospects.js',
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
            {$btnDeleteProspect}
            <a data-button-modal="prospects"><i class="material-icons">add</i><span>Nuevo</span></a>
            <!-- <a data-button-modal="importFromExcel"><i class="material-icons">cloud_upload</i><span>Importar desde excel</span></a> -->
            <a data-button-modal="addToClients"><i class="material-icons">person_add</i><span>Agregar a clientes</span></a>
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
                        <th width="35px"></th>
                    </tr>
                </thead>
                <tbody>
                    {$lstProspects}
                </tbody>
            </table>
        </div>
    </div>
</main>
<section class="modal" data-modal="prospects">
    <div class="content">
        <header>
            <h6>Nuevo prospecto</h6>
        </header>
        <main>
            <form name="prospects" data-submit-action="new">
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
                        <span><span class="required-field">*</span>Email</span>
                        <input type="email" name="email">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Número telefónico</span>
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
                        <span>Dirección</span>
                        <input type="text" name="address">
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
                            <option value="all">Todos los prospectos</option>
                            <option value="selected">Prospectos seleccionados</option>
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
<section class="modal alert" data-modal="addToClients">
    <div class="content">
        <header>
            <h6>Alerta</h6>
        </header>
        <main>
            <p>¿Está seguro de que desea agregar está selección de prospectos a clientes?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="addToClients">Aceptar</a>
        </footer>
    </div>
</section>
{$mdlDeleteProspects}
