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
        '{$path.js}pages/settings.js',
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
        <div class="box-tab-buttons">
            <a href="/settings/business" button-tab>Cuenta</a>
            <a href="/settings/sales" class="view" button-tab>Ventas</a>
            <div class="clear"></div>
        </div>
        <div class="padding">
            {$htmlSalesSettings}
            <div class="span6 pl">
                <table id="tblPdisSettings" class="display" data-page-length="20">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Inventario</th>
                            <th>Sucursal</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$tblPdisSettings}
                    </tbody>
                </table>
                <fieldset class="input-group" style="margin-top:20px;">
                    <a data-button-modal="updatePdisSettings">Actualizar</a>
                </fieldset>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</main>
<section class="modal" data-modal="editSalesSettings">
    <div class="content">
        <header>
            <h6>Editar</h6>
        </header>
        <main>
            <form name="editSalesSettings">
                <fieldset class="input-group">
                    <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Moneda principal</span>
                        <select name="mainCoin">
                            <option value="MXN">MXN</option>
                            <option value="USD">USD</option>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Impresión de ticket de venta</span>
                        <select name="saleTicketPrint">
                            <option value="true">Activado</option>
                            <option value="false">Desactivado</option>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Desglose de totales en ticket de venta</span>
                        <select name="saleTicketTotalsBreakdown">
                            <option value="true">Activado</option>
                            <option value="false">Desactivado</option>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Aplicar descuentos en ventas</span>
                        <select name="applyDiscounds">
                            <option value="true">Activado</option>
                            <option value="false">Desactivado</option>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Permitir pagos diferidos</span>
                        <select name="deferred_payments">
                            <option value="true">Activado</option>
                            <option value="false">Desactivado</option>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Sincronizar Punto de venta con Invetarios</span>
                        <select name="sync_point_sale_with_inventories">
                            <option value="true">Activado</option>
                            <option value="false">Desactivado</option>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Sincronizar Cotizaciones con Invetarios</span>
                        <select name="sync_quotations_with_inventories">
                            <option value="true">Activado</option>
                            <option value="false">Desactivado</option>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Tarífa de IVA</span>
                        <input type="number" name="ivaRate">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span><span class="required-field">*</span>Tarífa de cambio de USD</span>
                        <input type="number" name="usdRate">
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Leyenda de ticket de venta</span>
                        <textarea name="saleTicketLegend"></textarea>
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
<section class="modal" data-modal="updatePdisSettings">
    <div class="content">
        <header>
            <h6>Actualizar configuraciones PDIS</h6>
        </header>
        <main class="hidden">
            <p></p>
        </main>
        <footer>
            <a button-cancel>Cancelar</a>
            <a data-action="updatePdisSettings">Aceptar</a>
        </footer>
    </div>
</section>
