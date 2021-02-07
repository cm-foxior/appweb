<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Products/categories.js']);

?>

%{header}%
<header class="modbar">
    <div class="buttons">
        <fieldset class="fields-group big">
            <div class="compound st-4-left">
                <span><i class="fas fa-search"></i></span>
                <input type="text" data-search="products_categories" placeholder="{$lang.search}">
            </div>
        </fieldset>
        <?php if (Permissions::user(['create_products_categories']) == true) : ?>
            <a data-action="create_product_category" class="success"><i class="fas fa-plus"></i><span>{$lang.create}</span></a>
        <?php endif; ?>
    </div>
</header>
<main class="workspace">
    <table class="tbl-st-1" data-table="products_categories">
        <tbody>
            <?php foreach ($data['products_categories'] as $value) : ?>
                <tr>
                    <td class="smalltag"><span>{$lang.level} <?php echo $value['level']; ?></span></td>
                    <td><?php echo $value['name']; ?></td>
                    <td class="autotag">
                        <?php if ($value['sale_menu'] == true) : ?>
                            <span>{$lang.sale_menu}</span>
                        <?php endif; ?>
                        <?php if ($value['supply'] == true) : ?>
                            <span>{$lang.supply}</span>
                        <?php endif; ?>
                        <?php if ($value['recipe'] == true) : ?>
                            <span>{$lang.recipe}</span>
                        <?php endif; ?>
                        <?php if ($value['work_material'] == true) : ?>
                            <span>{$lang.work_material}</span>
                        <?php endif; ?>
                    </td>
                    <?php if (Permissions::user(['block_products_categories','unblock_products_categories']) == true) : ?>
                        <td class="button">
                            <?php if ($value['blocked'] == true) : ?>
                                <a data-action="unblock_product_category" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock"></i><span>{$lang.unblock}</span></a>
                            <?php elseif ($value['blocked'] == false) : ?>
                                <a data-action="block_product_category" data-id="<?php echo $value['id']; ?>"><i class="fas fa-unlock"></i><span>{$lang.block}</span></a>
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
                        <div class="row">
                            <div class="span8">
                                <div class="text">
                                    <input type="text" name="name" placeholder="{$lang.type}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.name}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <input type="text" name="level" placeholder="{$lang.type}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.level}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="checkbox st-2">
                            <label>
                                <input type="checkbox" name="sale_menu">
                                <span>{$lang.sale_menu}</span>
                            </label>
                            <label>
                                <input type="checkbox" name="supply">
                                <span>{$lang.supplies}</span>
                            </label>
                            <label>
                                <input type="checkbox" name="recipe">
                                <span>{$lang.recipes}</span>
                            </label>
                            <label>
                                <input type="checkbox" name="work_material">
                                <span>{$lang.work_material}</span>
                            </label>
                        </div>
                        <div class="title">
                            <h6>{$lang.available_for}</h6>
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
<?php if (Permissions::user(['delete_products_categories']) == true) : ?>
    <section class="modal alert" data-modal="delete_product_category">
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
