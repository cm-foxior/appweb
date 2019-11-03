<?php

defined('_EXEC') or die;

$this->dependencies->getDependencies([
    'css' => [

    ],
    'js' => [
        '{$path.js}pages/subscriptions.js'
    ],
    'other' => [

    ]
]);
?>

<section class="signin">
    <div class="container">
        <div class="selected-subscription">
            <article>
                <h6>{$price}</h6>
                <h2>{$type}</h2>
                <h6>{$time}</h6>
                <p>{$description}</p>
                <div class="items-1">

                </div>
                <div class="items-2">
                    <figure>
                        <img src="{$path.images}main-footer-6.png" alt="" />
                    </figure>
                    <figure>
                        <img src="{$path.images}main-footer-7.png" alt="" />
                    </figure>
                    <figure>
                        <img src="{$path.images}main-footer-8.png" alt="" />
                    </figure>
                    <figure>
                        <img src="{$path.images}main-footer-9.png" alt="" />
                    </figure>
                    <figure>
                        <img src="{$path.images}main-footer-10.png" alt="" />
                    </figure>
                </div>
                <div class="items-2">
                    <figure>
                        <img src="{$path.images}main-footer-11.png" alt="" />
                    </figure>
                </div>
            </article>
        </div>
        <div class="subscription-form">
            <form name="signin" autocomplete="off">
                <fieldset class="input-group">
                    <label data-important>
                        <span>Negocio</span>
                        <input type="text" name="business" autofocus>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Email</span>
                        <input type="email" name="email">
                    </label>
                </fieldset>
                <h4 class="title margin-top-10 margin-bottom-25"></h4>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Nombre completo</span>
                        <input type="text" name="name">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Usuario</span>
                        <input type="text" name="username" class="lowercase">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Contraseña</span>
                        <input type="password" name="password">
                    </label>
                </fieldset>
                <!-- <h4 class="title margin-top-10 margin-bottom-25"></h4>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Tipo de págo</span>
                        <select name="payment">
                            <option value="">Seleccione una opción</option>
                            <option value="1">PayPal</option>
                            <option value="2">Targeta de crédito/débito Master Card</option>
                            <option value="3">Targeta de crédito/débito VISA</option>
                            <option value="4">Oxxo Pay</option>
                            <option value="5">SPEI</option>
                            <option value="6">Suscripción de prueba</option>
                        </select>
                    </label>
                </fieldset> -->
                <fieldset class="input-group">
                    <a data-action="signin">{$btnSignInTitle}</a>
                    <a href="/">Regresar</a>
                </fieldset>
            </form>
        </div>
        <div class="clear"></div>
    </div>
</section>
