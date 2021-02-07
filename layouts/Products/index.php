<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Products/index.js']);

?>

%{header}%
<header class="modbar">
    <div class="shortcuts">
        <?php if (Permissions::user(['products'], true) == true) : ?>
            <a href="/products/salemenu"><i class="fas fa-dollar-sign"></i><span>{$lang.sale_menu}</span></a>
            <a href="/products/recipes"><i class="fas fa-receipt"></i><span>{$lang.recipes}</span></a>
            <a href="/products/supplies"><i class="fas fa-layer-group"></i><span>{$lang.supplies}</span></a>
            <a href="/products/workmaterial"><i class="fas fa-mail-bulk"></i><span>{$lang.work_material}</span></a>
            <a href="/products/products"><i class="fas fa-city"></i><span>{$lang.work_material}</span></a>
        <?php endif; ?>
    </div>
    <div class="buttons">
        <fieldset class="fields-group big">
            <div class="compound st-4-left">
                <span><i class="fas fa-search"></i></span>
                <input type="text" data-search="products" placeholder="{$lang.search}">
            </div>
        </fieldset>
        <!-- <a data-action="filter_products" class="success"><i class="fas fa-filter"></i><span>{$lang.filter}</span></a> -->
        <?php if (Permissions::user(['create_products']) == true) : ?>
            <a data-action="create_product" class="success"><i class="fas fa-plus"></i><span>{$lang.create}</span></a>
        <?php endif; ?>
    </div>
</header>
<main class="workspace">
    <table class="tbl-st-1" data-table="products">
        <tbody>
            <?php foreach ($data['products'] as $value) : ?>
                <tr>
                    <?php if ($data['type'] == 'sale_menu') : ?>
                        <td class="avatar">
                            <figure>
                                <img src="<?php echo (!empty($value['avatar']) ? '{$path.uploads}' . $value['avatar'] : '{$path.images}product.png') ?>">
                            </figure>
                        </td>
                    <?php endif; ?>
                    <td class="mediumtag"><span><?php echo $value['token']; ?></span></td>
                    <td><?php echo $value['name']; ?></td>
                    <?php if ($data['type'] == 'sale_menu') : ?>
                        <td class="mediumtag"><span>{$lang.price}: <?php echo Currency::format($value['price'], Session::get_value('vkye_account')['currency']); ?></span></td>
                    <?php endif; ?>
                    <td class="mediumtag"><span>{$lang.cost}: <?php echo Currency::format($value['cost'], Session::get_value('vkye_account')['currency']); ?></span></td>
                    <td class="mediumtag">
                        <span><?php echo (($value['inventory'] == true) ? (($value['unity_system'] == true) ? $value['unity_name'][Session::get_value('vkye_account')['language']] : $value['unity_name']) : '{$lang.not_subject_to_inventory}'); ?></span>
                    </td>
                    <?php if (Permissions::user(['block_products','unblock_products']) == true) : ?>
                        <td class="button">
                            <?php if ($value['blocked'] == true) : ?>
                                <a data-action="unblock_product" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock"></i><span>{$lang.unblock}</span></a>
                            <?php else : ?>
                                <a data-action="block_product" data-id="<?php echo $value['id']; ?>"><i class="fas fa-unlock"></i><span>{$lang.block}</span></a>
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
<!-- <section class="modal" data-modal="filter_products">
    <div class="content">
        <main>
            <form>
                <fieldset class="fields-group">
                    <div class="compound st-4-left">
                        <span><i class="fas fa-search"></i></span>
                        <input type="text" data-search="categories" placeholder="{$lang.search}">
                    </div>
                    <div class="checkbox st-1" data-table="categories">
                        <?php foreach ($data['products_categories'] as $value) : ?>
                            <label>
                                <input type="checkbox" name="categories[]" value="<?php echo $value['id']; ?>" <?php echo ((in_array($value['id'], System::temporal('get', 'products', 'categories'))) ? 'checked' : '') ?>>
                                <span><?php echo $value['name']; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <div class="title">
                        <h6>{$lang.categories}</h6>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="button">
                        <a class="alert" button-close><i class="fas fa-times"></i></a>
                        <button type="submit" class="success"><i class="fas fa-check"></i></button>
                    </div>
                </fieldset>
            </form>
        </main>
    </div>
</section> -->
<?php if (Permissions::user(['create_products','update_products']) == true) : ?>
    <section class="modal" data-modal="create_product">
        <div class="content">
            <main>
                <form>
                    <?php if ($data['type'] == 'sale_menu') : ?>
                        <fieldset class="fields-group">
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}product.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="avatar" accept="image/*" data-select>
                            </div>
                        </fieldset>
                    <?php endif; ?>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span6">
                                <div class="text">
                                    <input type="text" name="name" placeholder="{$lang.type}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.name}</h6>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="compound st-2-left">
                                    <a data-action="generate_random_token"><i class="fas fa-redo"></i></a>
                                    <input type="text" name="token" placeholder="{$lang.type}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.folio}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="<?php echo (($data['type'] == 'sale_menu' OR $data['type'] == 'recipe') ? 'span3' : 'span4'); ?>">
                                <div class="text">
                                    <select name="inventory" <?php echo (($data['type'] == 'supply' OR $data['type'] == 'recipe' OR $data['type'] == 'work_material') ? 'disabled' : ''); ?>>
                                        <?php echo (($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') ? '<option value="yes">{$lang.yes_subject_to_inventory}</option>' : ''); ?>
                                        <?php echo (($data['type'] == 'sale_menu' OR $data['type'] == 'recipe') ? '<option value="not" >{$lang.not_subject_to_inventory}</option>' : ''); ?>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.inventory}</h6>
                                </div>
                            </div>
                            <div class="<?php echo (($data['type'] == 'sale_menu' OR $data['type'] == 'recipe') ? 'span3' : 'span4'); ?>">
                                <div class="text">
                                    <select name="unity">
                                        <option value="" hidden>{$lang.select}</option>
                                        <?php foreach ($data['products_unities'] as $value) : ?>
                                            <option value="<?php echo $value['id']; ?>"><?php echo (($value['system'] == true) ? $value['name'][Session::get_value('vkye_account')['language']] : $value['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.storage_unity}</h6>
                                </div>
                            </div>
                            <div class="<?php echo (($data['type'] == 'sale_menu' OR $data['type'] == 'recipe') ? 'span3' : 'span4'); ?>">
                                <div class="compound st-3-left">
                                    <span class="first"><i class="fas fa-dollar-sign"></i></span>
                                    <input type="text" name="cost" placeholder="Auto" disabled>
                                    <span class="last"><?php echo Session::get_value('vkye_account')['currency']; ?></span>
                                </div>
                                <div class="title">
                                    <h6>{$lang.cost}</h6>
                                </div>
                            </div>
                            <?php if ($data['type'] == 'sale_menu') : ?>
                                <div class="span3">
                                    <div class="compound st-3-left">
                                        <span class="first"><i class="fas fa-dollar-sign"></i></span>
                                        <input type="text" name="price" placeholder="{$lang.type}">
                                        <span class="last"><?php echo Session::get_value('vkye_account')['currency']; ?></span>
                                    </div>
                                    <div class="title">
                                        <h6>{$lang.price}</h6>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($data['type'] == 'recipe') : ?>
                                <div class="span3">
                                    <div class="compound st-7-right">
                                        <input type="text" name="portion" placeholder="{$lang.type}">
                                        <span>{$lang.unity}</span>
                                    </div>
                                    <div class="title">
                                        <h6>{$lang.portion}</h6>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </fieldset>
                    <?php if ($data['type'] == 'sale_menu') : ?>
                        <fieldset class="fields-group">
                            <div class="row">
                                <div class="span4">
                                    <div class="text">
                                        <select name="formula_code" disabled>
                                            <option value="">{$lang.not_formula}</option>
                                            <?php foreach (Functions::formulas() as $value) : ?>
                                                <option value="<?php echo $value['code']; ?>"><?php echo $value['name'][Session::get_value('vkye_account')['language']]; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="title">
                                        <h6>{$lang.formula_code}</h6>
                                    </div>
                                </div>
                                <div class="span4">
                                    <div class="text">
                                        <select name="formula_parent" disabled>
                                            <option value="" hidden>{$lang.select}</option>
                                            <?php foreach ($data['products_parents'] as $value) : ?>
                                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="title">
                                        <h6>{$lang.formula_parent}</h6>
                                    </div>
                                </div>
                                <div class="span4">
                                    <div class="compound st-7-right">
                                        <input type="text" name="formula_quantity" placeholder="{$lang.type}" disabled>
                                        <span>{$lang.unity}</span>
                                    </div>
                                    <div class="title">
                                        <h6>{$lang.formula_quantity}</h6>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    <?php endif; ?>
                    <?php if ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material') : ?>
                        <fieldset class="fields-group">
                            <div class="compound st-4-left">
                                <span><i class="fas fa-search"></i></span>
                                <input type="text" data-search="contents" placeholder="{$lang.search}">
                            </div>
                            <div class="checkbox st-4" data-table="contents">
                                <?php foreach ($data['products_contents'] as $value) : ?>
                                    <div class="hidden" data-target>
                                        <label>
                                            <input type="checkbox" name="contents[]" value="<?php echo $value['id']; ?>">
                                            <span><?php echo $value['amount'] . ' ' . (($value['unity_system'] == true) ? $value['unity_name'][Session::get_value('vkye_account')['language']] : $value['unity_name']); ?></span>
                                        </label>
                                        <input type="text" name="<?php echo $value['id']; ?>[]" placeholder="{$lang.weight}">
                                        <select name="<?php echo $value['id']; ?>[]">
                                            <option value="" hidden>{$lang.unity}</option>
                                            <?php foreach ($data['products_unities'] as $value) : ?>
                                                <option value="<?php echo $value['id']; ?>"><?php echo (($value['system'] == true) ? $value['name'][Session::get_value('vkye_account')['language']] : $value['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="title">
                                <h6>{$lang.net_contents}</h6>
                            </div>
                        </fieldset>
                    <?php endif; ?>
                    <?php if ($data['type'] == 'sale_menu' OR $data['type'] == 'recipe') : ?>
                        <fieldset class="fields-group">
                            <div class="compound st-4-left">
                                <span><i class="fas fa-search"></i></span>
                                <input type="text" data-search="supplies" placeholder="{$lang.search}">
                            </div>
                            <div class="checkbox st-4" data-table="supplies">
                                <?php foreach ($data['products_supplies'] as $value) : ?>
                                    <div class="hidden" data-target="required">
                                        <label>
                                            <input type="checkbox" name="supplies[]" value="<?php echo $value['id']; ?>">
                                            <span><?php echo $value['name']; ?></span>
                                        </label>
                                        <span>{$lang.cost}: <?php echo Currency::format($value['cost'], Session::get_value('vkye_account')['currency']); ?></span>
                                        <input type="text" name="<?php echo $value['id']; ?>[]" placeholder="{$lang.quantity}">
                                        <select name="<?php echo $value['id']; ?>[]">
                                            <option value="" hidden>{$lang.unity}</option>
                                            <?php foreach ($data['products_unities'] as $value) : ?>
                                                <option value="<?php echo $value['id']; ?>"><?php echo (($value['system'] == true) ? $value['name'][Session::get_value('vkye_account')['language']] : $value['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="title">
                                <h6>{$lang.supplies}</h6>
                            </div>
                        </fieldset>
                    <?php endif; ?>
                    <fieldset class="fields-group">
                        <div class="compound st-4-left">
                            <span><i class="fas fa-search"></i></span>
                            <input type="text" data-search="categories" placeholder="{$lang.search}">
                        </div>
                        <div class="checkbox st-1" data-table="categories">
                            <?php foreach ($data['products_categories'] as $value) : ?>
                                <label>
                                    <input type="checkbox" name="categories[]" value="<?php echo $value['id']; ?>">
                                    <span><?php echo $value['name']; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <div class="title">
                            <h6>{$lang.categories}</h6>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="button">
                            <a class="alert" button-close><i class="fas fa-times"></i></a>
                            <button type="submit" class="success"><i class="fas fa-check"></i></button>
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
                    <a button-close><i class="fas fa-times"></i></a>
                    <a button-success><i class="fas fa-check"></i></a>
                </div>
            </main>
        </div>
    </section>
<?php endif; ?>
