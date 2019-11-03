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
            <a data-button-modal="productsAndServices"><i class="material-icons">format_list_bulleted</i><span>Lista de productos y servicios</span></a>
            <a href="/pointsale"><i class="material-icons">arrow_back</i><span>Regresar</span></a>
            <div class="clear"></div>
        </div>
        <div class="padding">
            {$html}
        </div>
    </div>
</main>

<section class="modal" data-modal="productsAndServices">
    <div class="content">
        <header>
            <h6>Productos y servicios</h6>
        </header>
        <main>
            <table id="tblProductsAndServices" class="display" data-page-length="20">
				<thead>
					<tr>
						<th>Descripción</th>
						<th width="60px">Tipo</th>
						<th width="35px"></th>
					</tr>
				</thead>
				<tbody>
                    {$lstProductsAndServices}
                </tbody>
            </table>
        </main>
        <footer>
            <a button-cancel>Cancelar</a>
        </footer>
    </div>
</section>
