<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Products/contents.js?v=1.0']);

?>

%{header}%
<header class="modbar">
    <div class="buttons">
        <fieldset class="fields-group big">
            <div class="compound st-4-left">
                <span><i class="fas fa-search"></i></span>
                <input type="text" data-search="products_contents" placeholder="{$lang.search}">
            </div>
        </fieldset>
        <?php if (Permissions::user(['create_products_contents']) == true) : ?>
            <a data-action="create_product_content" class="success"><i class="fas fa-plus"></i><span>{$lang.create}</span></a>
        <?php endif; ?>
    </div>
</header>
<main class="workspace">
    <table class="tbl-st-1" data-table="products_contents">
        <tbody>
            <?php foreach ($data['products_contents'] as $value) : ?>
                <tr>
                    <td class="smalltag">
                        <span><?php echo $value['amount']; ?></span>
                    </td>
                    <td class="smalltag">
                        <span><?php echo (($value['unity_system'] == true) ? $value['unity_name'][Session::get_value('vkye_account')['language']] : $value['unity_name']); ?></span>
                    </td>
                    <td></td>
                    <?php if (Permissions::user(['block_products_contents','unblock_products_contents']) == true) : ?>
                        <td class="button">
                            <?php if ($value['blocked'] == true) : ?>
                                <a data-action="unblock_product_content" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock"></i><span>{$lang.unblock}</span></a>
                            <?php elseif ($value['blocked'] == false) : ?>
                                <a data-action="block_product_content" data-id="<?php echo $value['id']; ?>"><i class="fas fa-unlock"></i><span>{$lang.block}</span></a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if (Permissions::user(['delete_products_contents']) == true) : ?>
                        <td class="button">
                            <?php if ($value['blocked'] == false) : ?>
                                <a data-action="delete_product_content" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if (Permissions::user(['update_products_contents']) == true) : ?>
                        <td class="button">
                            <?php if ($value['blocked'] == false) : ?>
                                <a data-action="update_product_content" data-id="<?php echo $value['id']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php if (Permissions::user(['create_products_contents','update_products_contents']) == true) : ?>
    <section class="modal" data-modal="create_product_content">
        <div class="content">
            <main>
                <form>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span8">
                                <div class="text">
                                    <input type="text" name="amount" placeholder="{$lang.type}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.net_content}</h6>
                                </div>
                            </div>
                            <div class="span4">
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
<?php if (Permissions::user(['delete_products_contents']) == true) : ?>
    <section class="modal alert" data-modal="delete_product_content">
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
