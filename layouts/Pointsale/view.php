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
        '{$path.js}pages/pointsale.js',
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
            {$btnCancelSale}
            <a href="/pointsale"><i class="material-icons">arrow_back</i><span>Regresar</span></a>
            <div class="clear"></div>
        </div>
        <div class="table-responsive-vertical padding">
            <table id="itemsSaleTable" class="display" data-page-length="100">
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Descuento</th>
                        <th>Total</th>
                        <th>Garantía</th>
                    </tr>
                </thead>
                <tbody>
                    {$lstItemsSale}
                </tbody>
            </table>
        </div>
        <div class="span4 padding">
            <fieldset class="input-group">
                <label data-important>
                    <span>Folio de venta</span>
                    <input type="text" value="{$folio}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>Fecha y hora de venta</span>
                    <input type="text" value="{$date}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>Vendedor</span>
                    <input type="text" value="{$user}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>Cliente</span>
                    <input type="text" value="{$client}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>Sucursal</span>
                    <input type="text" value="{$branchOffice}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>Estado</span>
                    {$iptStatus}
                </label>
            </fieldset>
        </div>
        <div class="span4 padding">
            <fieldset class="input-group">
                <label data-important>
                    <span>Tipo de pago</span>
                    <input type="text" value="{$payment}" disabled>
                    {$deferred_payments_ipts}
                </label>
            </fieldset>

            <fieldset class="input-group">
                <label data-important>
                    <span>Tarífa de IVA</span>
                    <input type="text" value="{$ivaRate} %" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>Tarífa de dólar</span>
                    <input type="text" value="$ {$usdRate} MXN" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>Moneda de transacción</span>
                    <input type="text" value="{$mainCoin}" disabled>
                </label>
            </fieldset>
        </div>
        <div class="span4 padding">
            <fieldset class="input-group">
                <label data-important>
                    <span>Subtotal</span>
                    <input type="text" value="$ {$subtotal} {$mainCoin}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>IVA</span>
                    <input type="text" value="$ {$iva} {$mainCoin}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>Total</span>
                    <input type="text" value="$ {$total} {$mainCoin}" disabled>
                    <input type="text" value="$ {$mxnTotal} MXN" class="span6" disabled>
                    <input type="text" value="$ {$usdTotal} USD" class="span6" disabled>
                    <div class="clear"></div>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>Recibido</span>
                    <input type="text" value="$ {$totalReceived} {$mainCoin}" disabled>
                    <input type="text" value="$ {$mxnTotalReceived} MXN" class="span6" disabled>
                    <input type="text" value="$ {$usdTotalReceived} USD" class="span6" disabled>
                    <div class="clear"></div>
                </label>
            </fieldset>
            {$abone}
            <fieldset class="input-group">
                <label data-important>
                    <span>Cambio</span>
                    <input type="text" value="$ {$change} {$mainCoin}" disabled>
                </label>
            </fieldset>
        </div>
        <div class="clear"></div>
    </div>
</main>

{$mdlCancelSale}
