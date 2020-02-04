<?php defined('_EXEC') or die; ?>

<header class="topbar">
    <figure class="logotype">
        <img src="{$path.images}logotype-sofi-2.svg" alt="logotype">
    </figure>
    <div class="tools">
        <div class="widgets">
            <?php if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9) : ?>
            <div class="shortcut-menu">
                <a href="/search"><i class="material-icons">search</i>Buscar producto</a>
            </div>
            <div class="shortcut-menu">
                <a href="/pointsale/add"><i class="material-icons">add</i>Nueva venta</a>
            </div>
            <?php endif; ?>
            <div class="shortcut-menu">
                <a href="" class="user-logged lowercase"><i class="material-icons">fiber_manual_record</i><?php echo Session::getValue('username'); ?></a>
            </div>
            <div class="dropdown-menu">
                <button type="button"><i class="material-icons">more_vert</i></button>
                <div class="dropdown md--shadow">
                    <?php if (Session::getValue('level') == 10) : ?>
                    <a href="/settings/sales"><i class="material-icons">settings</i>Configuraciones</a>
                    <span class="space"></span>
                    <?php endif; ?>
                    <a href="?session=logout"><i class="material-icons">power_settings_new</i>Cerrar sesion</a>
                </div>
            </div>
        </div>
    </div>
    <figure class="avatar">
        <img <?php echo (!empty(Session::getValue('avatar')) ? 'src="{$path.images}users/' . Session::getValue('avatar') . '"' : 'src="{$path.images}users/avatar.png"') ?> alt="avatar">
    </figure>
</header>
<aside class="leftbar <?php if (Session::getValue('level') == 8) : echo 'droped'; endif; ?>">
    <ul>
        <?php if (Session::getValue('level') >= 8) : ?>
        <li data-target="inventories">
            <a href="/inventories"><i class="material-icons">assignment_turned_in</i>Inventarios</a>
        </li>
        <?php endif; ?>
        <?php if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9) : ?>
        <li data-target="sales">
            <a href="/pointsale"><i class="material-icons">shopping_cart</i>Punto de venta</a>
        </li>
        <?php endif; ?>
        <?php if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9) : ?>
        <li data-target="catalogs">
            <a href=""><i class="material-icons">view_module</i>Catálogos</a>
            <ul>
                <li>
                    <a href="/products"><i class="material-icons">keyboard_arrow_right</i>Productos</a>
                </li>
                <!-- <li>
                    <a href="/services"><i class="material-icons">keyboard_arrow_right</i>Servicios</a>
                </li> -->
                <?php if (Session::getValue('level') == 10) : ?>
                <li>
                    <a href="/providers"><i class="material-icons">keyboard_arrow_right</i>Proveedores</a>
                </li>
                <li>
                    <a href="/clients"><i class="material-icons">keyboard_arrow_right</i>Clientes</a>
                </li>
                <!-- <li>
                    <a href="/prospects"><i class="material-icons">keyboard_arrow_right</i>Prospectos</a>
                </li> -->
                <li data-target="branchoffices">
                    <a href="/branchoffices"><i class="material-icons">keyboard_arrow_right</i>Sucursales</a>
                </li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>

        <?php if (Session::getValue('level') >= 9) : ?>
        <li data-target="reports">
            <a href="/reports/inventories/existence"><i class="material-icons">crop_portrait</i>Reportes</a>
        </li>
        <?php endif; ?>

        <?php if (Session::getValue('level') >= 9) : ?>
        <li data-target="users">
            <a href="/users"><i class="material-icons">supervisor_account</i>Usuarios</a>
        </li>
        <?php endif; ?>
    </ul>
</aside>
