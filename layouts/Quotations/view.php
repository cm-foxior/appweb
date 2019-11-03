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
        '{$path.js}pages/quotations.js',
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
            <a href="/quotations"><i class="material-icons">arrow_back</i><span>Regresar</span></a>
            <div class="clear"></div>
        </div>
        <div class="table-responsive-vertical padding">
            <table id="itemsSaleTable" class="display" data-page-length="100">
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Folio</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Descuento</th>
                        <th>Total</th>
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
                    <span>Fecha y hora de cotización</span>
                    <input type="text" value="{$date_time}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>Fecha de expiración</span>
                    <input type="text" value="{$expiration}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>Estado</span>
                    <input type="text" value="{$status}" disabled>
                </label>
            </fieldset>
        </div>
        <div class="span4 padding">
            <fieldset class="input-group">
                <label data-important>
                    <span>Cliente</span>
                    <input type="text" value="{$client}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>Vendedor</span>
                    <input type="text" value="{$seller}" disabled>
                </label>
            </fieldset>
            {$branchOffice}
        </div>
        <div class="span4 padding">
            {$deferred_payments}
            <fieldset class="input-group">
                <label data-important>
                    <span>Subtotal</span>
                    <input type="text" value="$ {$subtotal} {$coin}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>IVA</span>
                    <input type="text" value="$ {$iva} {$coin}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group">
                <label data-important>
                    <span>Total</span>
                    <input type="text" value="$ {$total} {$coin}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group span6 pr">
                <label data-important>
                    <input type="text" value="$ {$mxnTotal} {$coin}" disabled>
                </label>
            </fieldset>
            <fieldset class="input-group span6">
                <label data-important>
                    <input type="text" value="$ {$usdTotal} {$coin}" disabled>
                </label>
                <a href="" data-action="resend_email">Reenviar por email</a>
            </fieldset>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</main>
