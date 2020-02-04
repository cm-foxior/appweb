<?php

defined('_EXEC') or die;

$this->dependencies->getDependencies([
    'css' => [
        '{$path.plugins}DataTables/css/jquery.dataTables.min.css',
        '{$path.plugins}DataTables/css/dataTables.material.min.css',
        '{$path.plugins}DataTables/css/responsive.dataTables.min.css',
        '{$path.plugins}DataTables/css/buttons.dataTables.min.css',
        '{$path.plugins}fancybox/source/jquery.fancybox.css'
    ],
    'js' => [
        '{$path.js}pages/products.js',
        '{$path.plugins}DataTables/js/jquery.dataTables.min.js',
        '{$path.plugins}DataTables/js/dataTables.material.min.js',
        '{$path.plugins}DataTables/js/dataTables.responsive.min.js',
        '{$path.plugins}DataTables/js/dataTables.buttons.min.js',
        '{$path.plugins}DataTables/js/pdfmake.min.js',
        '{$path.plugins}DataTables/js/vfs_fonts.js',
        '{$path.plugins}DataTables/js/buttons.html5.min.js',
        '{$path.plugins}fancybox/source/jquery.fancybox.pack.js',
        '{$path.plugins}fancybox/source/jquery.fancybox.js'
    ],
    'other' => [
        '<script type="text/javascript">
            $(document).ready(function()
            {
                $(".fancybox-thumb").fancybox({
                    openEffect  : "elastic",
                    closeEffect : "elastic",
                    prevEffect	: "none",
    		        nextEffect	: "none",
                    padding     : "0",
                    helpers	:
                    {
                    	thumbs	:
                        {
                    		width	: 50,
                    		height	: 50
                    	}
                    }
	           });
            });
        </script>'
    ]
]);
?>

%{header}%

<main class="body">
    <div class="content">
        {$buttons}
        <div class="table-responsive-vertical padding">
            {$tblProducts}
        </div>
    </div>
</main>

{$mdlProducts}
{$mdlImportFromExcel}
{$mdlActivateProducts}
{$mdlDectivateProducts}
{$mdlDeleteProducts}
{$mdlPostProductsToEcommerce}
{$mdlUnpostProductsToEcommerce}
