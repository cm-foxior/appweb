<?php

defined('_EXEC') or die;

$this->dependencies->getDependencies([
    'css' => [

    ],
    'js' => [
        '{$path.js}pages/users.js'
    ],
    'other' => [

    ]
]);
?>

%{header}%

<main class="body">
    <div class="content">
        <div class="box-buttons">
            <a href="/users"><i class="material-icons">arrow_back</i><span>Regresar</span></a>
            <div class="clear"></div>
        </div>
        <div class="padding">
            <div class="span6 pr">
                <h4 class="title">Información de contacto</h4>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Nombre completo</span>
                        <input type="text" value="{$name}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Email</span>
                        <input type="email" value="{$email}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Telefono</span>
                        <input type="text" value="{$phoneNumber}" disabled>
                    </label>
                </fieldset>
                <h4 class="title margin-top-30">Información de usuario</h4>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Nombre de usuario</span>
                        <input type="text" value="{$username}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Nivel de acceso</span>
                        <input type="text" value="{$level}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Sucursal asignada</span>
                        <input type="text" value="{$branchOffice}" disabled>
                    </label>
                </fieldset>
                <h4 class="title margin-top-30">Información de registro</h4>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Estado</span>
                        <input type="text" value="{$status}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Fecha de registro</span>
                        <input type="text"  value="{$registrationDate}" disabled>
                    </label>
                </fieldset>
            </div>
            <div class="span6 pl">
                <div class="upload-image">
                    <div class="image-preview" data-image-src="{$avatar}"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</main>
