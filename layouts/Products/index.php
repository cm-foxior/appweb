<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Products/index.min.js']);

?>

%{header}%
<header class="modbar">
    <?php if (Permissions::user(['products'], true) == true) : ?>
    <a href="/products/menu"><i class="fas fa-box-open"></i><span>{$lang.products}</span></a>
    <?php endif; ?>
    <?php if (Permissions::user(['products_categories'], true) == true) : ?>
    <a href="/products/categories" class="unfocus"><i class="fas fa-tags"></i><span>{$lang.categories}</span></a>
    <?php endif; ?>
    <?php if (Permissions::user(['products_unities'], true) == true) : ?>
    <a href="/products/unities" class="unfocus"><i class="fas fa-balance-scale-left"></i><span>{$lang.unities}</span></a>
    <?php endif; ?>
    <?php if (Permissions::user(['products_barcodes'], true) == true) : ?>
    <a href="/products/barcodes" class="unfocus"><i class="fas fa-barcode"></i><span>{$lang.barcodes}</span></a>
    <?php endif; ?>
    <span></span>
    <?php if (Permissions::user(['create_products']) == true) : ?>
    <a data-action="create_product" class="success"><i class="fas fa-plus"></i><span>{$lang.create}</span></a>
    <?php endif; ?>
    <fieldset>
        <span><i class="fas fa-search"></i></span>
        <input type="text" data-search="products">
    </fieldset>
    <span></span>
    <a href="/products/menu" <?php echo ($data['type'] != 'sale') ? 'class="unfocus"' : ''; ?>><i class="fas fa-dollar-sign"></i><span>{$lang.menu}</span></a>
    <a href="/products/supplies" <?php echo ($data['type'] != 'supply') ? 'class="unfocus"' : ''; ?>><i class="fas fa-layer-group"></i><span>{$lang.supplies}</span></a>
    <a href="/products/recipes" <?php echo ($data['type'] != 'recipe') ? 'class="unfocus"' : ''; ?>><i class="fas fa-receipt"></i><span>{$lang.recipes}</span></a>
    <a href="/products/workmaterials" <?php echo ($data['type'] != 'work_material') ? 'class="unfocus"' : ''; ?>><i class="fas fa-mail-bulk"></i><span>{$lang.work_materials}</span></a>
</header>
<main>
    <table class="tbl-st-1" data-table="products">
        <tbody>
            <?php foreach ($data['products'] as $value) : ?>
            <tr>
                <?php if ($data['type'] == 'sale') : ?>
                <td class="avatar">
                    <figure>
                        <?php if (!empty($value['avatar'])) : ?>
                        <img src="{$path.uploads}<?php echo $value['avatar']; ?>">
                        <?php else : ?>
                        <img src="{$path.images}empty.png">
                        <?php endif; ?>
                    </figure>
                </td>
                <?php endif; ?>
                <td><?php echo $value['token']; ?></td>
                <td><?php echo $value['name']; ?></td>
                <?php if ($data['type'] == 'sale') : ?>
                <td><?php echo Functions::get_format_currency($value['price'], Session::get_value('vkye_account')['currency']); ?></td>
                <?php endif; ?>
                <td class="smalltag">
                    <?php if ($value['blocked'] == true) : ?>
                    <span class="busy">{$lang.blocked}</span>
                    <?php elseif ($value['blocked'] == false) : ?>
                    <span>{$lang.unblocked}</span>
                    <?php endif; ?>
                </td>
                <?php if (Permissions::user(['block_products','unblock_products']) == true) : ?>
                <td class="button">
                    <?php if ($value['blocked'] == true) : ?>
                    <a data-action="unblock_product" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock-open"></i><span>{$lang.unblock}</span></a>
                    <?php elseif ($value['blocked'] == false) : ?>
                    <a data-action="block_product" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock"></i><span>{$lang.block}</span></a>
                    <?php endif; ?>
                </td>
                <?php endif; ?>
                <?php if (Permissions::user(['delete_products']) == true) : ?>
                <td class="button">
                    <?php if ($value['blocked'] == false) : ?>
                    <a data-action="delete_product" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
                    <?php endif; ?>
                </td>
                <?php endif; ?>
                <?php if (Permissions::user(['update_products']) == true) : ?>
                <td class="button">
                    <?php if ($value['blocked'] == false) : ?>
                    <a data-action="update_product" data-id="<?php echo $value['id']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
                    <?php endif; ?>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php if (Permissions::user(['create_products','update_products']) == true) : ?>
<section class="modal" data-modal="create_product">
    <div class="content">
        <main>
            <form>
                <?php if ($data['type'] == 'sale') : ?>
                <fieldset class="fields-group">
                    <div class="uploader" data-low-uploader>
                        <figure data-preview>
                            <img src="{$path.images}empty.png">
                            <a data-select><i class="fas fa-pen"></i></a>
                        </figure>
                        <input type="file" name="avatar" accept="image/*" data-select>
                    </div>
                </fieldset>
                <?php endif; ?>
                <fieldset class="fields-group">
                    <div class="text">
                        <input type="text" name="name" placeholder="{$lang.name}">
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="compound action">
                        <a data-random="token"><i class="fas fa-random"></i></a>
                        <input type="text" name="token" placeholder="{$lang.token}">
                    </div>
                </fieldset>
                <?php if ($data['type'] == 'sale') : ?>
                <fieldset class="fields-group">
                    <div class="compound span">
                        <span class="first"><i class="fas fa-dollar-sign"></i></span>
                        <input type="text" name="price" placeholder="{$lang.price}">
                        <span class="last"><?php echo Session::get_value('vkye_account')['currency']; ?></span>
                    </div>
                </fieldset>
                <?php endif; ?>
                <fieldset class="fields-group">
                    <div class="row">
                        <?php if ($data['type'] == 'sale' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') : ?>
                        <div class="span4">
                            <div class="text">
                                <select name="unity">
                                    <option value="" selected hidden>{$lang.unity}</option>
                                    <?php foreach ($data['products_unities'] as $value) : ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($data['type'] == 'sale' OR $data['type'] == 'supply') : ?>
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="weight_empty" placeholder="{$lang.weight_empty}">
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="weight_full" placeholder="{$lang.weight_full}">
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </fieldset>
                <?php if ($data['type'] == 'recipe') : ?>
                <fieldset class="fields-group">
                    <div class="checkbox">
                        <h6>{$lang.supplies}</h6>
                        <?php foreach ($data['products_supplies'] as $value) : ?>
                        <label>
                            <input type="checkbox" name="supplies[]" value="<?php echo $value['id']; ?>">
                            <span><?php echo $value['name']; ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <?php endif; ?>
                <fieldset class="fields-group">
                    <div class="checkbox">
                        <?php foreach ($data['products_categories'] as $key => $value) : ?>
                        <h6>{$lang.categories} {$lang.level} <?php echo $key; ?></h6>
                        <?php foreach ($value as $subkey => $subvalue) : ?>
                        <label>
                            <input type="checkbox" name="categories[]" value="<?php echo $subvalue['id']; ?>">
                            <span><?php echo $subvalue['name']; ?></span>
                        </label>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <?php if ($data['type'] == 'sale') : ?>
                <fieldset class="fields-group">
                    <div class="checkbox list">
                        <h6>{$lang.recipes}</h6>
                        <?php foreach ($data['products_recipes'] as $value) : ?>
                        <label>
                            <input type="checkbox" name="recipes[]" value="<?php echo $value['id']; ?>">
                            <span><?php echo $value['name']; ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <?php endif; ?>
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
<?php if (Permissions::user(['delete_products']) == true) : ?>
<section class="modal alert" data-modal="delete_product">
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
