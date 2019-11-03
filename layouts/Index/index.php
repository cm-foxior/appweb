<?php

defined('_EXEC') or die;

$this->dependencies->getDependencies([
    'css' => [
        '{$path.plugins}OwlCarousel2-2.2.1/assets/owl.carousel.min.css',
        '{$path.plugins}OwlCarousel2-2.2.1/assets/owl.theme.default.min.css'
    ],
    'js' => [
        '{$path.js}pages/index.js',
        '{$path.plugins}OwlCarousel2-2.2.1/owl.carousel.min.js'
    ],
    'other' => [
        '<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>'
    ]
]);
?>

<header class="main-menu">
    <div class="container">
        <figure>
            <img src="{$path.images}logotype-sofi-2.svg" alt="" />
        </figure>
        <nav class="menu">
            <!-- <a href="#know-us" data-smooth-scroll>Conócenos</a> -->
            <a href="#modules" data-smooth-scroll>Módulos</a>
            <a href="#subscriptions" data-smooth-scroll>Precios</a>
            <a href="/login" class="login">Iniciar sesión</a>
            <a href="#contact-us" data-smooth-scroll>Contáctanos</a>
        </nav>
        <nav class="r-menu">
            <a href="" data-open-r-menu><i class="material-icons">menu</i></a>
        </nav>
    </div>
</header>

<section class="home">
    <video autoplay loop muted>
        <source src="{$path.images}home-background-video-1.mp4" type="video/mp4">
    </video>
    <!-- <div class="owl-carousel">
        <div class="item" data-image-src="{$path.images}home-slideshow-1.jpg"></div>
        <div class="item" data-image-src="{$path.images}home-slideshow-2.jpg"></div>
        <div class="item" data-image-src="{$path.images}home-slideshow-3.jpg"></div>
        <div class="item" data-image-src="{$path.images}home-slideshow-4.jpg"></div>
    </div> -->
    <div class="cover">
        <!-- <div class="container"> -->
            <figure>
                <img src="{$path.images}logotype-sofi-2.svg" alt="">
            </figure>
            <h1>SOFI ERP</h1>
            <p>Administrar tu negocio <strong>núnca</strong> fue tan fácil y rápido</p>
        <!-- </div> -->
    </div>
</section>

<!-- <section id="know-us" class="know-us">
    <figure>
        <img src="{$path.images}logotype-sofi-2.svg" alt="" />
    </figure>
    <div class="cover"></div>
    <div class="container">
        <div class="content">
            <p><strong>Sofi ERP</strong> <span>es un sistema para la planificación de recursos empresariales</span>, enfocado a los <strong>micros y pequeños negocios</strong> en <strong>México</strong>. Es una solución moderna, eficaz y fácil de utilizar, sin molestos técnisismos <span>¡Administrar tu negocio núnca fue tan fácil!</span></p>
        </div>
        <div class="video">
            <video id="know_us_video">
                <source src="{$path.images}know-us-video-2.mp4" type="video/mp4">
            </video>
            <div class="controls">
                <a class="view not-disappear" data-action="play_know_us_video"><i class="material-icons">play_arrow</i></a>
                <a data-action="pause_know_us_video"><i class="material-icons">pause</i></a>
                <a data-action="stop_know_us_video"><i class="material-icons">stop</i></a>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</section> -->

<section id="modules" class="modules">
    <div class="container">
        <h4 class="h-title">Nuestros <strong>módulos</strong> disponibles</h4>
        <div class="item">
            <i class="material-icons">assignment_turned_in</i>
            <h6>Inventarios</h6>
            <p>Controla tus entradas, salidas, stocks, transferencias, etc.</p>
        </div>
        <div class="item">
            <i class="material-icons">shopping_cart</i>
            <h6>Véntas</h6>
            <p>Gestiona tus ventas fácilmente, tus vendedores siempre sabrán que hacer</p>
        </div>
        <div class="item">
            <i class="material-icons">view_module</i>
            <h6>Catálogos</h6>
            <p>Categoriza tu negocio y todo lo que comercializas</p>
        </div>
        <div class="item">
            <i class="material-icons">crop_portrait</i>
            <h6>Reportes</h6>
            <p>Mantente siempre informado, conóce tus resultados y núnca pierdas el control</p>
        </div>
        <div class="item">
            <i class="material-icons">settings</i>
            <h6>Configuraciones</h6>
            <p>Configura tu negocio con los paramentros que tu decidas</p>
        </div>
        <div class="item">
            <i class="material-icons">person_add</i>
            <h6>Usuarios</h6>
            <p>Tú decides que personal entra a tu sistema y lo que hacen en el</p>
        </div>
    </div>
</section>

<section id="modules" class="modules">
    <div class="container">
        <h4 class="h-title">Nuestros <strong>próximos</strong> módulos</h4>
        <div class="item">
            <i class="material-icons">attach_money</i>
            <h6>Administración financiera</h6>
            <p>Cuida tus recursos financieros y minimíza el riesgo de perder dinero</p>
        </div>
        <div class="item">
            <i class="material-icons">insert_drive_file</i>
            <h6>Facturación y fiscal</h6>
            <p>Factura a tus clientes con un solo click, calcula impuestos, etc</p>
        </div>
        <div class="item">
            <i class="material-icons">people</i>
            <h6>Recursos Humános</h6>
            <p>Administra tu personal de manera sencilla y eficáz</p>
        </div>
        <div class="item">
            <i class="material-icons">insert_emoticon</i>
            <h6>CRM</h6>
            <p>Organiza y has el seguimiento de tus clientes</p>
        </div>
    </div>
</section>

<section id="modules" class="modules">
    <div class="container">
        <h4 class="h-title">Una solución <strong>moderna</strong> y eficáz</h4>
        <div class="item">
            <figure>
                <img src="{$path.images}modules-advantages-1.png" alt="" />
            </figure>
            <h6>Control total</h6>
            <p>Controla por completo el sistema, no dejes que nadie te engañe</p>
        </div>
        <div class="item">
            <figure>
                <img src="{$path.images}modules-advantages-2.png" alt="" />
            </figure>
            <h6>A tiempo real</h6>
            <p>Todo al instante, con los datos más actualizados</p>
        </div>
        <div class="item">
            <figure>
                <img src="{$path.images}modules-advantages-3.png" alt="" />
            </figure>
            <h6>Fácil e intuitivo</h6>
            <p>No requieres conocimientos avanzados, siempre sabrás que hacer</p>
        </div>
        <div class="item">
            <figure>
                <img src="{$path.images}modules-advantages-4.png" alt="" />
            </figure>
            <h6>Multi-dispositivo</h6>
            <p>Ahora podrás estár conectado a través de cualquier dispositivo</p>
        </div>
        <div class="item">
            <figure>
                <img src="{$path.images}modules-advantages-6.png" alt="" />
            </figure>
            <h6>Multi-sucursal</h6>
            <p>No importa cuantas sucursales tienes, todas en un solo lugar</p>
        </div>
        <div class="item">
            <figure>
                <img src="{$path.images}modules-advantages-10.png" alt="" />
            </figure>
            <h6>Multi-moneda</h6>
            <p>Has tus transacciones en pesos o dollares y siempre controla el tipo de cambio</p>
        </div>
        <div class="item">
            <figure>
                <img src="{$path.images}modules-advantages-7.png" alt="" />
            </figure>
            <h6>Autoadministrable</h6>
            <p>Que nadie cargue tu información, házlo tu mismo y cuando quieras</p>
        </div>
        <div class="item">
            <figure>
                <img src="{$path.images}modules-advantages-8.png" alt="" />
            </figure>
            <h6>Trabaja donde quieras</h6>
            <p>No importa en que parte del mundo te encuentres, siempre tendrás acceso</p>
        </div>
        <div class="item">
            <figure>
                <img src="{$path.images}modules-advantages-9.png" alt="" />
            </figure>
            <h6>Sin riesgos</h6>
            <p>Olvidate del miedo a perder información, totalmente seguro</p>
        </div>
        <div class="item">
            <figure>
                <img src="{$path.images}modules-advantages-11.png" alt="" />
            </figure>
            <h6>Soporte técnico</h6>
            <p>Si lo necesitas, nosotros te ayudamos en lo que requieras</p>
        </div>
        <div class="item">
            <figure>
                <img src="{$path.images}modules-advantages-12.png" alt="" />
            </figure>
            <h6>Actualizaciones constantes</h6>
            <p>Siempre estamos trabajando para mejorar tu experiencia</p>
        </div>
        <div class="item">
            <figure>
                <img src="{$path.images}modules-advantages-5.png" alt="" />
            </figure>
            <h6>En la nube</h6>
            <p>Toda tu información protegida en servidores con los más altos estandares</p>
        </div>
        <div class="clear"></div>
        <div class="item-left-right">
            <figure>
                <img src="{$path.images}modules-clients-using-1.png" alt="">
            </figure>
            <div class="content">
                <p><strong>Muchos</strong> negocios en México ya están utilizando <strong>Sofi ERP</strong><span>¿Que esperas? ¡Únete a esta gran comunidad!</span></p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="item-center">
            <figure>
                <img src="{$path.images}modules-clients-using-3.png" alt="">
            </figure>
            <div class="clear"></div>
        </div>
    </div>
</section>

<section class="subscriptions">
    <div id="subscriptions" class="container">
        <div class="item">
            <article>
                <h6>$ 499 MXN</h6>
                <h2>Plan ventas</h2>
                <h6>Mensual</h6>
                <p>Obten accceso ilimitado <strong>¡ por 1 mes !</strong> a los módulos de inventarios, ventas, catálogos, reportes, configuraciones y usuarios.</p>
                <!-- <a href="/subscriptions/signin/basic">Comprar</a> -->
                <a href="#contact-us" data-smooth-scroll>Contáctanos</a>
            </article>
        </div>
        <div class="item main-view">
            <article>
                <h6>$ 3,999 MXN</h6>
                <h2>Plan ventas</h2>
                <h6>Anual</h6>
                <p>Ten accceso ilimitado <strong>¡ por 1 año !</strong> a los módulos de inventarios, ventas, catálogos, reportes, configuraciones y usuarios.</p>
                <!-- <a href="/subscriptions/signin/standard">Comprar</a> -->
                <a href="#contact-us" data-smooth-scroll>Contáctanos</a>
            </article>
        </div>
        <div class="item">
            <article>
                <h6>$ 35,000 MXN</h6>
                <h2>Plan ventas</h2>
                <h6>10 Plus</h6>
                <p>Obten accceso ilimitado <strong>¡ por 10 años !</strong> a los módulos de inventarios, ventas, catálogos, reportes, configuraciones y usuarios.</p>
                <!-- <a href="/subscriptions/signin/premium">Comprar</a> -->
                <a href="#contact-us" data-smooth-scroll>Contáctanos</a>
            </article>
        </div>
        <!-- <div class="item">
            <article>
                <h6>A cotizar</h6>
                <h2>Plan ventas</h2>
                <h6>De por vida</h6>
                <p>Obtem accceso ilimitado <strong>¡ de por vida !</strong> a los módulos de inventarios, ventas, catálogos, reportes, configuraciones y usuarios.</p>
                <a href="/subscriptions/signin/test">Comprar</a>
                <a href="#contact-us" data-smooth-scroll>Contáctanos</a>
            </article>
        </div> -->
        <div class="clear"></div>
    </div>
</section>

<section class="contact-us">
    <form id="contact-us" name="contact" autocomplete="off">
        <fieldset class="input-group">
            <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
        </fieldset>
        <fieldset class="input-group">
            <label data-important>
                <span><span class="required-field">*</span>Nombre completo</span>
                <input type="text" name="name">
            </label>
        </fieldset>
        <fieldset class="input-group">
            <label data-important>
                <span><span class="required-field">*</span>Email</span>
                <input type="text" name="email">
            </label>
        </fieldset>
        <fieldset class="input-group">
            <label data-important>
                <span><span class="required-field">*</span>Teléfono</span>
                <input type="number" name="phone">
            </label>
        </fieldset>
        <fieldset class="input-group">
            <label data-important>
                <span><span class="required-field">*</span>Me interesa</span>
                <select name="interested">
                    <option value="Plan ventas Mensual">Plan Ventas Mensual</option>
                    <option value="Plan ventas Anual" selected>Plan Ventas Anual</option>
                    <option value="Plan ventas Trianual">Plan Ventas Trianual</option>
                    <option value="Conocer más acerca de Sofi ERP">Conocer más acerca de Sofi ERP</option>
                    <option value="Soporte Técnico">Soporte Técnico</option>
                    <option value="Otro">Otro</option>
                </select>
            </label>
        </fieldset>
        <fieldset class="input-group">
            <label data-important>
                <span>Mensaje</span>
                <textarea name="message"></textarea>
            </label>
            <a data-action="sendContactEmail">Enviar email</a>
        </fieldset>
    </form>
    <div class="schedules">
        <h6>Horarios de atención:</h6>
        <p>Lunes a Viernes de 11:00 am a 05:00 pm</p>
        <p>Sabados de 10:00 am a 04:00 pm</p>
        <p>Hora del pacifico</p>
        <h6>Información de contacto:</h6>
        <p>(998) 132 51 46</p>
        <p>contacto@sofierp.com</p>
    </div>
    <div class="clear"></div>
</section>

<section class="copyright">
    <figure>
        <img class="cm-lt-1" src="{$path.images}logotype-codemonkey-3.png" alt="" />
    </figure>
    <figure>
        <img class="cm-lt-2" src="{$path.images}logotype-codemonkey-4.png" alt="" />
    </figure>
    <p>Este sistema es desarrollado, publicado y distribuido por Code Monkey</p>
    <p>Copyright (C) Todos los derechos reservados</p>
    <a href="https://codemonkey.com.mx/" target="_blank">www.codemonkey.com.mx</a>
</section>

<!-- <section class="development-team">
    <div class="item">
        <figure>
            <img src="{$path.images}development-team-1.png" alt="" />
        </figure>
        <h4>Líder<br>general de proyecto</h4>
    </div>
    <div class="item">
        <figure>
            <img src="{$path.images}development-team-2.png" alt="" />
        </figure>
        <h4>Líder de<br>programación</h4>
    </div>
    <div class="item">
        <figure>
            <img src="{$path.images}development-team-3.png" alt="" />
        </figure>
        <h4>Líder de<br>programación</h4>
    </div>
    <div class="item">
        <figure>
            <img src="{$path.images}development-team-4.png" alt="" />
        </figure>
        <h4>Líder de<br>diseño gráfico</h4>
    </div>
    <div class="item">
        <figure>
            <img src="{$path.images}development-team-5.png" alt="" />
        </figure>
        <h4>Líder de<br>ingeniería de software</h4>
    </div>
</section> -->

<!-- <footer class="main-footer">
    <div class="container">
        <figure>
            <img src="{$path.images}main-footer-1.png" alt="" />
        </figure>
        <figure>
            <img src="{$path.images}main-footer-2.png" alt="" />
        </figure>
        <figure>
            <img src="{$path.images}main-footer-3.png" alt="" />
        </figure>
        <figure>
            <img src="{$path.images}main-footer-4.png" alt="" />
        </figure>
        <figure>
            <img src="{$path.images}main-footer-5.png" alt="" />
        </figure>
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
        <figure>
            <img src="{$path.images}main-footer-11.png" alt="" />
        </figure>
        <nav>
            <a href=""><i class="fab fa-facebook-f"></i></a>
            <a href=""><i class="fab fa-instagram"></i></a>
            <a href=""><i class="fab fa-twitter"></i></a>
        </nav>
    </div>
</footer> -->

<section class="modal" data-modal="emailHasBeenSent">
    <div class="content">
        <header>
            <h6>Tu email se ha enviádo correctamente</h6>
        </header>
        <main>

        </main>
        <footer>
            <a button-cancel>Aceptar</a>
        </footer>
    </div>
</section>
