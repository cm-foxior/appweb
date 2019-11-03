<?php

defined('_EXEC') or die;

$this->dependencies->getDependencies([
    'css' => [

    ],
    'js' => [
        '{$path.js}pages/branchoffices.js'
    ],
    'other' => [

    ]
]);
?>

%{header}%

<main class="body">
    <div class="content">
        <div class="box-buttons">
            <a href="/branchoffices"><i class="material-icons">arrow_back</i><span>Regresar</span></a>
            <div class="clear"></div>
        </div>
        <div class="padding">
            <div class="span6 pr">
                <h4 class="title">Información de contacto</h4>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Nombre</span>
                        <input type="text" value="{$name}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Correo electrónico</span>
                        <input type="email" value="{$email}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Número telefónico</span>
                        <input type="text" value="{$phoneNumber}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Dirección</span>
                        <input type="text" value="{$address}" disabled>
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
                <h4 class="title">Información fiscal</h4>
                <fieldset class="input-group">
                    <label data-important>
                        <span>País</span>
                        <input type="text" value="{$fiscalCountry}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Razón Social</span>
                        <input type="text" value="{$fiscalName}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>RFC</span>
                        <input type="text"  value="{$fiscalCode}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Régimen fiscal</span>
                        <input type="text"  value="{$fiscalRegime}" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Dirección fiscal</span>
                        <input type="text"  value="{$fiscalAddress}" disabled>
                    </label>
                </fieldset>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</main>
