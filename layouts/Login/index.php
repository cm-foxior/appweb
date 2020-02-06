<?php
defined('_EXEC') or die;

$this->dependencies->getDependencies([
    'css' => [
        '{$path.plugins}OwlCarousel2-2.2.1/assets/owl.carousel.min.css',
        '{$path.plugins}OwlCarousel2-2.2.1/assets/owl.theme.default.min.css'
    ],
    'js' => [
        '{$path.plugins}OwlCarousel2-2.2.1/owl.carousel.min.js',
        '{$path.js}pages/login.js'
    ]
]);
?>

<section class="login">
    <div class="owl-carousel">
        <div class="item" data-image-src="{$path.images}login-slideshow-1.jpg"></div>
        <div class="item" data-image-src="{$path.images}login-slideshow-2.jpg"></div>
        <div class="item" data-image-src="{$path.images}login-slideshow-3.jpg"></div>
        <div class="item" data-image-src="{$path.images}login-slideshow-4.jpg"></div>
    </div>
    <div class="content">
        <!-- <a href="/" class="return">Regresar</a> -->

        <form name="login">
            <figure class="logotype">
                <img src="{$path.images}isotype.svg" alt="logotipo">
            </figure>

            <!-- Inicie sesion en su cuenta -->
            <h2>Foxior</h2>

            <fieldset class="input-group">
                <label data-important>
                    <!-- Nombre de usuario -->
                    <span>Usuario o Correo electrónico</span>
                    <input type="text" name="username" value="">
                </label>
                <!-- Por favor, escriba su nombre de usuario. -->
                <p class="pre-error">Por favor, escriba un nombre de usuario válido</p>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <!-- Contraseña -->
                    <span>Contraseña</span>
                    <input type="password" name="password" value="">
                </label>
                <!-- Por favor, escriba una contraseña válida. -->
                <p class="pre-error">Por favor, escriba una contraseña válida</p>
            </fieldset>

            <!-- Iniciar sesion -->
            <button type="submit">Iniciar sesión</button>
        </form>
    </div>
</section>
