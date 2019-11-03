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
        '{$path.js}pages/users.js',
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
            <a data-button-modal="deleteUsers"><i class="material-icons">delete</i><span>Eliminar</span></a>
            <a data-button-modal="users"><i class="material-icons">add</i><span>Nuevo</span></a>
            <a data-button-modal="deactivateUsers"><i class="material-icons">block</i><span>Desactivar</span></a>
            <a data-button-modal="activateUsers"><i class="material-icons">check</i><span>Activar</span></a>
            <div class="clear"></div>
        </div>
        <div class="table-responsive-vertical padding">
            {$tblUsers}
        </div>
    </div>
</main>

<section class="modal" data-modal="users">
    <div class="content">
        <header>
            <h6>Nuevo usuario</h6>
        </header>
        <main>
            <form name="users" data-submit-action="new">
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
                        <input type="email" name="email" class="lowercase">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Número telefónico</span>
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
                <h4 class="title margin-top-30">Información de usuario</h4>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Usuario</span>
                        <input type="text" name="username" class="lowercase">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Contraseña</span>
                        <input type="password" name="password">
                        <span class="legend">Mínimo 8 carácteres</span>
                    </label>
                    <label class="checkbox" data-important>
                        <input type="checkbox" data-action="showPassword">
                        <span>Ver contraseña</span>
                        <input type="checkbox" data-action="randomPassword">
                        <span>Contraseña aleatoria</span>
                        <div class="clear"></div>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Nivel de acceso</span>
                        <select name="level">
                            {$lstLevel}
                            <option value="8">[8] Inventarista</option>
        					<option value="7">[7] Vendedor</option>
                        </select>
                    </label>
                </fieldset>
                <div class="upload-image">
                    <div class="image-preview" image-preview="image-preview"></div>
                    <a select-image>Seleccionar imagen</a>
                    <a clear-image>Eliminar imagen</a>
                    <input id="image-preview" name="avatar" type="file" accept="image/*" image-preview="image-preview"/>
                </div>
                {$lstBranchOffices}
            </form>
        </main>
        <footer>
            <a button-cancel>Cancelar</a>
            <a button-success>Aceptar</a>
        </footer>
    </div>
</section>
<section class="modal" data-modal="restoreUserPassword">
    <div class="content">
        <header>
            <h6>Restablecer contraseña</h6>
        </header>
        <main>
            <form name="restoreUserPassword">
                <fieldset class="input-group">
                    <label data-important>
                        <span>Nueva contraseña</span>
                        <input type="password" name="newPassword" autofocus>
                    </label>
                    <label class="checkbox" data-important>
                        <input type="checkbox" data-action="showPasswordRestored">
                        <span>Ver contraseña</span>
                        <input type="checkbox" data-action="randomPasswordRestored">
                        <span>Contraseña aleatoria</span>
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
<section class="modal alert" data-modal="activateUsers">
    <div class="content">
        <header>
            <h6>Activar</h6>
        </header>
        <main>
            <p>¿Está seguro de que desea activar está selección de usuarioes?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="activateUsers">Aceptar</a>
        </footer>
    </div>
</section>
<section class="modal alert" data-modal="deactivateUsers">
    <div class="content">
        <header>
            <h6>Desactivar</h6>
        </header>
        <main>
            <p>¿Está seguro de que desea desactivar está selección de usuarioes?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="deactivateUsers">Aceptar</a>
        </footer>
    </div>
</section>
<section class="modal alert" data-modal="deleteUsers">
    <div class="content">
        <header>
            <h6>Eliminar</h6>
        </header>
        <main>
            <p>¿Está seguro de que desea eliminar está selección de usuarioes?</p>
        </main>
        <footer>
            <a button-close>Cancelar</a>
            <a data-action="deleteUsers">Aceptar</a>
        </footer>
    </div>
</section>
