<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Products/categories.min.js']);

?>

%{header}%
<header class="modbar">
    <?php if (Permissions::user(['products'], true) == true) : ?>
    <a href="/products/menu" class="unfocus"><i class="fas fa-dollar-sign"></i><span>{$lang.menu}</span></a>
    <a href="/products/supplies" class="unfocus"><i class="fas fa-layer-group"></i><span>{$lang.supplies}</span></a>
    <a href="/products/recipes" class="unfocus"><i class="fas fa-receipt"></i><span>{$lang.recipes}</span></a>
    <a href="/products/workmaterials" class="unfocus"><i class="fas fa-mail-bulk"></i><span>{$lang.work_materials}</span></a>
    <span></span>
    <?php endif; ?>
    <?php if (Permissions::user(['products_categories'], true) == true) : ?>
    <a href="/products/categories"><i class="fas fa-tags"></i><span>{$lang.categories}</span></a>
    <?php endif; ?>
    <?php if (Permissions::user(['products_unities'], true) == true) : ?>
    <a href="/products/unities" class="unfocus"><i class="fas fa-balance-scale-left"></i><span>{$lang.unities}</span></a>
    <?php endif; ?>
    <?php if (Permissions::user(['products_barcodes'], true) == true) : ?>
    <a href="/products/barcodes" class="unfocus"><i class="fas fa-barcode"></i><span>{$lang.barcodes}</span></a>
    <?php endif; ?>
    <span></span>
    <?php if (Permissions::user(['create_products_categories']) == true) : ?>
    <a data-action="create_product_category" class="success"><i class="fas fa-plus"></i><span>{$lang.create}</span></a>
    <?php endif; ?>
    <fieldset>
        <span><i class="fas fa-search"></i></span>
        <input type="text" data-search="products_categories">
    </fieldset>
    <span></span>
    <fieldset>
        <span><i class="fas fa-angle-double-down"></i></span>
        <select name="level" data-search="products_categories">
            <option value="" selected>{$lang.all_levels}</option>
            <?php foreach ($data['products_categories_levels'] as $value) : ?>
            <option value="{$lang.level} <?php echo $value; ?>">{$lang.level} <?php echo $value; ?></option>
            <?php endforeach; ?>
        </select>
    </fieldset>
</header>
<main>
    <table class="tbl-st-1" data-table="products_categories">
        <tbody>
            <?php foreach ($data['products_categories'] as $value) : ?>
            <tr>
                <td class="avatar">
                    <figure>
                        <?php if (!empty($value['avatar'])) : ?>
                        <img src="{$path.uploads}<?php echo $value['avatar']; ?>">
                        <?php else : ?>
                        <img src="{$path.images}empty.png">
                        <?php endif; ?>
                    </figure>
                </td>
                <td><?php echo $value['name']; ?></td>
                <td class="smalltag"><span>{$lang.level} <?php echo $value['level']; ?></span></td>
                <td class="smalltag">
                    <?php if ($value['blocked'] == true) : ?>
                    <span class="busy">{$lang.blocked}</span>
                <?php elseif ($value['blocked'] == false) : ?>
                    <span>{$lang.unblocked}</span>
                    <?php endif; ?>
                </td>
                <?php if (Permissions::user(['block_products_categories','unblock_products_categories']) == true) : ?>
                <td class="button">
                    <?php if ($value['blocked'] == true) : ?>
                    <a data-action="unblock_product_category" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock-open"></i><span>{$lang.unblock}</span></a>
                    <?php elseif ($value['blocked'] == false) : ?>
                    <a data-action="block_product_category" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock"></i><span>{$lang.block}</span></a>
                    <?php endif; ?>
                </td>
                <?php endif; ?>
                <?php if (Permissions::user(['delete_products_categories']) == true) : ?>
                <td class="button">
                    <?php if ($value['blocked'] == false) : ?>
                    <a data-action="delete_product_category" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
                    <?php endif; ?>
                </td>
                <?php endif; ?>
                <?php if (Permissions::user(['update_products_categories']) == true) : ?>
                <td class="button">
                    <?php if ($value['blocked'] == false) : ?>
                    <a data-action="update_product_category" data-id="<?php echo $value['id']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
                    <?php endif; ?>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php if (Permissions::user(['create_products_categories','update_products_categories']) == true) : ?>
<section class="modal" data-modal="create_product_category">
    <div class="content">
        <main>
            <form>
                <fieldset class="fields-group">
                    <div class="uploader" data-low-uploader>
                        <figure data-preview>
                            <img src="{$path.images}empty.png">
                            <a data-select><i class="fas fa-pen"></i></a>
                        </figure>
                        <input type="file" name="avatar" accept="image/*" data-select>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span8">
                            <div class="text">
                                <input type="text" name="name" placeholder="{$lang.name}">
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="level" placeholder="{$lang.level}">
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="button">
                        <button type="submit" class="success"><i class="fas fa-plus"></i></button>
                        <a class="alert" button-close><i class="fas fa-times"></i></a>
                    </div>
                </fieldset>
            </form>
        </main>
    </div>
</section>
<?php endif; ?>
<?php if (Permissions::user(['delete_products_categories']) == true) : ?>
<section class="modal alert" data-modal="delete_product_category">
    <div class="content">
        <main>
            <i class="fas fa-trash"></i>
            <div class="button">
                <a button-success><i class="fas fa-check"></i></a>
                <a button-close><i class="fas fa-times"></i></a>
            </div>
        </main>
    </div>
</section>
<?php endif; ?>
