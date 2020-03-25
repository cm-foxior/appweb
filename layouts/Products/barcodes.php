<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Products/barcodes.min.js']);

?>

%{header}%
<header class="modbar">
    <?php if (Permissions::user(['products'], true) == true) : ?>
    <a href="/products/salemenu" class="unfocus"><i class="fas fa-dollar-sign"></i><span>{$lang.sale_menu}</span></a>
    <a href="/products/supplies" class="unfocus"><i class="fas fa-layer-group"></i><span>{$lang.supplies}</span></a>
    <a href="/products/recipes" class="unfocus"><i class="fas fa-receipt"></i><span>{$lang.recipes}</span></a>
    <a href="/products/workmaterial" class="unfocus"><i class="fas fa-mail-bulk"></i><span>{$lang.work_material}</span></a>
    <span></span>
    <?php endif; ?>
    <?php if (Permissions::user(['products_categories'], true) == true) : ?>
    <a href="/products/categories" class="unfocus"><i class="fas fa-tag"></i><span>{$lang.categories}</span></a>
    <?php endif; ?>
    <?php if (Permissions::user(['products_unities'], true) == true) : ?>
    <a href="/products/unities" class="unfocus"><i class="fas fa-balance-scale-left"></i><span>{$lang.unities}</span></a>
    <?php endif; ?>
    <a href="/products/barcodes"><i class="fas fa-barcode"></i><span>{$lang.barcodes}</span></a>
</header>
<main>

</main>
