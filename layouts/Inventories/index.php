<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Inventories/index.min.js']);

?>

%{header}%
<header class="modbar">
    <a href="/inventories"><i class="fas fa-box-open"></i><span>{$lang.inventories}</span></a>
    <span></span>
    <?php if (Permissions::user(['inventories_types'], true) == true) : ?>
    <a href="/inventories/types" class="unfocus"><i class="fas fa-bookmark"></i><span>{$lang.types}</span></a>
    <?php endif; ?>
    <?php if (Permissions::user(['inventories_locations'], true) == true) : ?>
    <a href="/inventories/locations" class="unfocus"><i class="fas fa-map-marker-alt"></i><span>{$lang.locations}</span></a>
    <?php endif; ?>
    <?php if (Permissions::user(['inventories_categories'], true) == true) : ?>
    <a href="/inventories/categories" class="unfocus"><i class="fas fa-tag"></i><span>{$lang.categories}</span></a>
    <?php endif; ?>
    <?php if (!empty($data['branches'])) : ?>
    <span></span>
    <?php if (Permissions::user(['create_inventories_input']) == true) : ?>
    <a data-action="create_inventory_input" class="success"><i class="fas fa-arrow-up"></i><span>{$lang.input}</span></a>
    <?php endif; ?>
    <?php if (Permissions::user(['create_inventories_output']) == true) : ?>
    <a data-action="create_inventory_output" class="success"><i class="fas fa-arrow-down"></i><span>{$lang.output}</span></a>
    <?php endif; ?>
    <?php if (Permissions::user(['create_inventories_transfer']) == true) : ?>
    <a data-action="create_inventory_transfer" class="success"><i class="fas fa-arrow-left"></i><span>{$lang.transfer}</span></a>
    <?php endif; ?>
    <fieldset class="fields-group">
        <div class="compound st-4-left">
            <span><i class="fas fa-search"></i></span>
            <input type="text" data-search="inventories" placeholder="{$lang.search}">
        </div>
    </fieldset>
    <span></span>
    <fieldset class="fields-group">
        <div class="compound st-4-left">
            <span><i class="fas fa-store"></i></span>
            <select data-action="switch_branch">
                <?php foreach ($data['branches'] as $value) : ?>
                <option value="<?php echo $value['token']; ?>" <?php echo (($data['branch']['token'] == $value['token']) ? 'selected' : ''); ?>><?php echo $value['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </fieldset>
    <?php endif; ?>
</header>
<?php if (!empty($data['branches'])) : ?>
<main>
    <table class="tbl-st-1" data-table="inventories">
        <tbody>
            <?php foreach ($data['inventories'] as $value) : ?>
            <tr>
                <td class="bigtag"><span><?php echo Dates::format_date($value['date'], 'd M, Y') . ' ' . Dates::format_hour($value['hour'], '12'); ?></span></td>
                <td><?php echo $value['product_name']; ?></td>
                <td class="smalltag"><span><?php echo $value['quantity'] . ' ' . $value['product_unity']; ?></span></td>
                <td class="bigtag">
                    <?php if (!empty($value['location'])) : ?>
                    <span><?php echo $value['location']; ?></span>
                    <?php else : ?>
                    <span>{$lang.not_location}</span>
                    <?php endif; ?>
                </td>
                <td class="bigtag">
                    <?php if (!empty($value['bill'])) : ?>
                    <span>{$lang.bill} #<?php echo $value['bill']; ?></span>
                    <?php elseif (!empty($value['remission'])) : ?>
                    <span>{$lang.remission} #<?php echo $value['remission']; ?></span>
                    <?php else : ?>
                    <span>{$lang.not_bill_remission}</span>
                    <?php endif; ?>
                </td>
                <?php if ($value['movement'] == 'input') : ?>
                <td class="smalltag left">
                    <span class="success"><i class="fas fa-arrow-up"></i>{$lang.input}</span>
                </td>
                <?php elseif ($value['movement'] == 'output') : ?>
                <td class="smalltag right">
                    <span class="busy">{$lang.output}<i class="fas fa-arrow-down"></i></span>
                </td>
                <?php endif; ?>
                <td class="smalltag"><span><?php echo $value['type']; ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php if (Permissions::user(['create_inventories_input']) == true) : ?>
<section class="modal" data-modal="create_inventory_input">
    <div class="content">
        <main>
            <form>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span6">
                            <div class="compound st-5">
                                <div>
                                    <label>
                                        <input type="radio" name="control" value="unity" checked>
                                        <span class="checked">{$lang.unity}</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input type="radio" name="control" value="weight" disabled>
                                        <span class="disabled">{$lang.weight}</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input type="radio" name="control" value="scan" disabled>
                                        <span class="disabled">{$lang.scan}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="compound st-5">
                                <div>
                                    <label>
                                        <input type="radio" name="saved" value="normal">
                                        <span>{$lang.normal}</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input type="radio" name="saved" value="bill" checked>
                                        <span class="checked">{$lang.bill}</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input type="radio" name="saved" value="remission">
                                        <span>{$lang.remission}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="compound st-7">
                        <input type="text" name="bill" placeholder="{$lang.folio_bill}">
                        <span><?php echo Currency::format(Functions::summation('products', Session::get_value('products'), 'total'), Session::get_value('vkye_account')['currency']); ?></span>
                    </div>
                </fieldset>
                <fieldset class="fields-group hidden">
                    <div class="compound st-7">
                        <input type="text" name="remission" placeholder="{$lang.folio_remission}">
                        <span><?php echo Currency::format(Functions::summation('products', Session::get_value('products'), 'total'), Session::get_value('vkye_account')['currency']); ?></span>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span4">
                            <div class="text">
                                <select name="type">
                                    <option value="" selected hidden>{$lang.type}</option>
                                    <?php foreach ($data['inventories_types'] as $value) : ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="span8">
                            <div class="text">
                                <select name="provider">
                                    <option value="" selected hidden>{$lang.not_provider}</option>
                                    <?php foreach ($data['providers'] as $value) : ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span4">
                            <div class="text">
                                <input type="date" name="date" value="<?php echo Dates::current_date(); ?>">
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="time" name="hour" value="<?php echo Dates::current_hour(); ?>">
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <select name="location">
                                    <option value="" selected>{$lang.not_location}</option>
                                    <?php foreach ($data['inventories_locations'] as $value) : ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <?php if (!empty($data['inventories_categories'])) : ?>
                    <div class="title">
                        <h6>{$lang.categories}</h6>
                    </div>
                    <div class="compound st-4-left">
                        <span><i class="fas fa-search"></i></span>
                        <input type="text" data-search="categories" placeholder="{$lang.search_categories}">
                    </div>
                    <div class="checkbox st-1" data-cbx="categories">
                        <?php foreach ($data['inventories_categories'] as $key => $value) : ?>
                        <h6>{$lang.level} <?php echo $key; ?></h6>
                        <?php foreach ($value as $subkey => $subvalue) : ?>
                        <label>
                            <input type="checkbox" name="categories[]" value="<?php echo $subvalue['id']; ?>">
                            <span><?php echo $subvalue['name']; ?></span>
                        </label>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php else : ?>
                    <div class="button">
                        <a href="/inventories/categories"><i class="fas fa-tag"></i><span>{$lang.categories}</span></a>
                    </div>
                    <div class="message">
                        <p>{$lang.to_select_categories}</p>
                    </div>
                    <?php endif; ?>
                </fieldset>
                <div class="tbl-st-4" data-table="products">
                    <div>
                        <?php foreach (Session::get_value('products') as $value) : ?>
                        <div>
                            <figure>
                                <?php if (!empty($value['product']['avatar'])) : ?>
                                <img src="{$path.uploads}<?php echo $value['product']['avatar']; ?>">
                                <?php endif; ?>
                                <img src="{$path.images}empty.png">
                            </figure>
                            <div>
                                <h4><?php echo $value['product']['token'] . ' | ' . $value['product']['name'] . ' | {$lang.' . $value['product']['type'] . '}'; ?></h4>
                                <span><?php echo $value['quantity'] . ' ' . $value['product']['unity'] . ' x ' . Currency::format($value['price'], Session::get_value('vkye_account')['currency']) . ' = ' . Currency::format($value['total'], Session::get_value('vkye_account')['currency']); ?></span>
                            </div>
                            <a data-action="remove_product_to_table" data-id="<?php echo $value['product']['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <fieldset class="fields-group">
                    <?php if (!empty($data['products'])) : ?>
                    <div class="title">
                        <h6>{$lang.product}</h6>
                    </div>
                    <div class="compound st-6">
                        <div data-preview>
                            <input type="text" placeholder="{$lang.not_product}" data-preview-value>
                            <input type="text" name="product" readonly data-preview-selected>
                        </div>
                        <div data-search>
                            <span><i class="fas fa-search"></i></span>
                            <input type="text" placeholder="{$lang.search_product}" data-search-value>
                        </div>
                        <div data-list>
                            <?php foreach ($data['products'] as $value): ?>
                            <div class="hidden">
                                <figure>
                                    <?php if (!empty($value['avatar'])) : ?>
                                    <img src="{$path.uploads}<?php echo $value['avatar']; ?>">
                                    <?php else : ?>
                                    <img src="{$path.images}empty.png">
                                    <?php endif; ?>
                                </figure>
                                <p><?php echo $value['token'] . ' | ' .  $value['name'] . ' | {$lang.' . $value['type'] . '}'; ?></p>
                                <a data-list-value="<?php echo $value['id']; ?>"></a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php else : ?>
                    <div class="button">
                        <a href="/products/salemenu"><i class="fas fa-dollar-sign"></i><span>{$lang.sale_menu}</span></a>
                        <a href="/products/supplies"><i class="fas fa-layer-group"></i><span>{$lang.supplies}</span></a>
                        <a href="/products/workmaterial"><i class="fas fa-mail-bulk"></i><span>{$lang.work_material}</span></a>
                    </div>
                    <div class="message">
                        <p>{$lang.to_select_products}</p>
                    </div>
                    <?php endif; ?>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span6">
                            <div class="compound st-7">
                                <input type="text" name="quantity" placeholder="{$lang.quantity}">
                                <span>{$lang.unity}</span>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="compound st-3-left">
                                <span class="first"><i class="fas fa-dollar-sign"></i></span>
                                <input type="text" name="price" placeholder="{$lang.unitary_price}">
                                <span class="last"><?php echo Session::get_value('vkye_account')['currency']; ?></span>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="button">
                        <a data-action="add_product_to_table" class="success"><i class="fas fa-check"></i><span>{$lang.add_product_to_table}</span></a>
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
<?php else : ?>
<main class="to-use">
    <a href="/branches" class="success"><i class="fas fa-plus"></i></a>
    <p>{$lang.to_use_inventories_create_branch}</p>
</main>
<?php endif; ?>
