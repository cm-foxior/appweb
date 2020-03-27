<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Products/index.min.js']);

?>

%{header}%
<header class="modbar">
    <a href="/products/salemenu" <?php echo ($data['type'] != 'sale_menu') ? 'class="unfocus"' : ''; ?>><i class="fas fa-dollar-sign"></i><span>{$lang.sale_menu}</span></a>
    <a href="/products/supplies" <?php echo ($data['type'] != 'supply') ? 'class="unfocus"' : ''; ?>><i class="fas fa-layer-group"></i><span>{$lang.supplies}</span></a>
    <a href="/products/recipes" <?php echo ($data['type'] != 'recipe') ? 'class="unfocus"' : ''; ?>><i class="fas fa-receipt"></i><span>{$lang.recipes}</span></a>
    <a href="/products/workmaterial" <?php echo ($data['type'] != 'work_material') ? 'class="unfocus"' : ''; ?>><i class="fas fa-mail-bulk"></i><span>{$lang.work_material}</span></a>
    <span></span>
    <?php if (Permissions::user(['products_categories'], true) == true) : ?>
    <a href="/products/categories" class="unfocus"><i class="fas fa-tag"></i><span>{$lang.categories}</span></a>
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
    <fieldset class="fields-group">
        <div class="compound st-4-left">
            <span><i class="fas fa-search"></i></span>
            <input type="text" data-search="products" placeholder="{$lang.search}">
        </div>
    </fieldset>
</header>
<main>
    <table class="tbl-st-1" data-table="products">
        <tbody>
            <?php foreach ($data['products'] as $value) : ?>
            <tr>
                <?php if ($data['type'] == 'sale_menu') : ?>
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
                <?php if ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') : ?>
                <td class="smalltag"><span><?php echo $value['token']; ?></span></td>
                <?php endif; ?>
                <td><?php echo $value['name']; ?></td>
                <?php if ($data['type'] == 'sale_menu') : ?>
                <td class="bigtag"><span><?php echo Currency::format($value['price'], Session::get_value('vkye_account')['currency']); ?></span></td>
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
                <?php if ($data['type'] == 'sale_menu') : ?>
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
                    <div class="row">
                        <?php if ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') : ?>
                        <div class="span8">
                        <?php else: ?>
                        <div class="span12">
                        <?php endif; ?>
                            <fieldset class="fields-group">
                                <div class="text">
                                    <input type="text" name="name" placeholder="{$lang.name}">
                                </div>
                                <?php if ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') : ?>
                                <div class="checkbox st-3">
                                    <label>
                                        <input type="checkbox" name="inventory" checked>
                                        <span>{$lang.subject_to_inventory}</span>
                                    </label>
                                </div>
                                <?php endif; ?>
                            </fieldset>
                        </div>
                        <?php if ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') : ?>
                        <div class="span4">
                            <div class="compound st-2-left">
                                <a data-action="generate_random_token"><i class="fas fa-redo"></i></a>
                                <input type="text" name="token" placeholder="{$lang.folio}">
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </fieldset>
                <?php if ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') : ?>
                <fieldset class="fields-group">
                    <div class="row">
                        <?php if ($data['type'] == 'sale_menu') : ?>
                        <div class="span4">
                        <?php else: ?>
                        <div class="span12">
                        <?php endif; ?>
                            <div class="text">
                                <select name="unity">
                                    <option value="" selected hidden>{$lang.unity}</option>
                                    <?php foreach ($data['products_unities'] as $value) : ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php if ($data['type'] == 'sale_menu') : ?>
                        <div class="span8">
                            <div class="compound st-3-left">
                                <span class="first"><i class="fas fa-dollar-sign"></i></span>
                                <input type="text" name="price" placeholder="{$lang.unitary_price}">
                                <span class="last"><?php echo Session::get_value('vkye_account')['currency']; ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </fieldset>
                <?php endif; ?>
                <?php if ($data['type'] == 'sale_menu' OR $data['type'] == 'supply') : ?>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span6">
                            <div class="text">
                                <input type="text" name="weight_full" placeholder="{$lang.weight_full}">
                            </div>
                        </div>
                        <div class="span6">
                            <div class="text">
                                <input type="text" name="weight_empty" placeholder="{$lang.weight_empty}">
                            </div>
                        </div>
                    </div>
                </fieldset>
                <?php endif; ?>
                <?php if (!empty($data['products_categories'])) : ?>
                <fieldset class="fields-group">
                    <div class="title">
                        <h6>{$lang.categories}</h6>
                    </div>
                    <?php if (Functions::summation('count', $data['products_categories'], true) > 20) : ?>
                    <div class="compound st-4-left">
                        <span><i class="fas fa-search"></i></span>
                        <input type="text" data-search="categories" placeholder="{$lang.search}">
                    </div>
                    <?php endif; ?>
                    <div class="checkbox st-1" data-table="categories">
                        <?php foreach ($data['products_categories'] as $value) : ?>
                            <?php foreach ($value as $subvalue) : ?>
                            <label>
                                <input type="checkbox" name="categories[]" value="<?php echo $subvalue['id']; ?>">
                                <span><?php echo $subvalue['name']; ?></span>
                            </label>
                            <?php endforeach; ?>
                            <i class="fas fa-circle"></i>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <?php endif; ?>
                <?php if ($data['type'] == 'sale_menu' OR $data['type'] == 'recipe') : ?>
                    <?php if (!empty($data['products_supplies'])) : ?>
                    <fieldset class="fields-group">
                        <div class="title">
                            <h6>{$lang.supplies}</h6>
                        </div>
                        <div class="compound st-4-left">
                            <span><i class="fas fa-search"></i></span>
                            <input type="text" data-search="supplies" placeholder="{$lang.search}">
                        </div>
                        <div class="checkbox st-1" data-table="supplies">
                            <?php foreach ($data['products_supplies'] as $value) : ?>
                            <label class="hidden">
                                <input type="checkbox" name="supplies[]" value="<?php echo $value['id']; ?>">
                                <span><?php echo $value['name']; ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($data['type'] == 'sale_menu') : ?>
                    <?php if (!empty($data['products_recipes'])) : ?>
                    <fieldset class="fields-group">
                        <div class="title">
                            <h6>{$lang.recipes}</h6>
                        </div>
                        <div class="compound st-4-left">
                            <span><i class="fas fa-search"></i></span>
                            <input type="text" data-search="recipes" placeholder="{$lang.search}">
                        </div>
                        <div class="checkbox st-2" data-table="recipes">
                            <?php foreach ($data['products_recipes'] as $value) : ?>
                            <label class="hidden">
                                <input type="checkbox" name="recipes[]" value="<?php echo $value['id']; ?>">
                                <span><?php echo $value['name']; ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                    <?php endif; ?>
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
            <div>
                <a button-success><i class="fas fa-check"></i></a>
                <a button-close><i class="fas fa-times"></i></a>
            </div>
        </main>
    </div>
</section>
<?php endif; ?>
