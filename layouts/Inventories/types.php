<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Inventories/types.js?v=1.0']);

?>

%{header}%
<header class="modbar">
    <div class="buttons">
        <fieldset class="fields-group big">
            <div class="compound st-4-left">
                <span><i class="fas fa-search"></i></span>
                <input type="text" data-search="inventories_types" placeholder="{$lang.search}">
            </div>
        </fieldset>
        <!-- <?php if (Permissions::user(['create_inventories_types']) == true) : ?>
            <a data-action="create_inventory_type" class="success"><i class="fas fa-plus"></i><span>{$lang.create}</span></a>
        <?php endif; ?> -->
    </div>
</header>
<main class="workspace">
    <table class="tbl-st-1" data-table="inventories_types">
        <tbody>
            <?php foreach ($data['inventories_types'] as $value) : ?>
                <tr>
                    <?php if ($value['movement'] == 'input') : ?>
                        <td class="smalltag left">
                            <span class="success"><i class="fas fa-arrow-up"></i>{$lang.input}</span>
                        </td>
                    <?php elseif ($value['movement'] == 'output') : ?>
                        <td class="smalltag right">
                            <span class="busy">{$lang.output}<i class="fas fa-arrow-down"></i></span>
                        </td>
                    <?php else: ?>
                        <td class="smalltag left right">
                            <span class="warning"><i class="fas fa-arrow-up"></i>{$lang.all}<i class="fas fa-arrow-down"></i></span>
                        </td>
                    <?php endif; ?>
                    <td class="mediumtag">
                        <span><?php echo (($value['system'] == true) ? $value['name'][Session::get_value('vkye_account')['language']] : $value['name']); ?></span>
                    </td>
                    <td></td>
                    <?php if (Permissions::user(['block_inventories_types','unblock_inventories_types']) == true) : ?>
                        <td class="button">
                            <?php if ($value['system'] == false) : ?>
                                <?php if ($value['blocked'] == true) : ?>
                                    <a data-action="unblock_inventory_type" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock"></i><span>{$lang.unblock}</span></a>
                                <?php elseif ($value['blocked'] == false) : ?>
                                    <a data-action="block_inventory_type" data-id="<?php echo $value['id']; ?>"><i class="fas fa-unlock"></i><span>{$lang.block}</span></a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if (Permissions::user(['delete_inventories_types']) == true) : ?>
                        <td class="button">
                            <?php if ($value['system'] == false) : ?>
                                <?php if ($value['blocked'] == false) : ?>
                                    <a data-action="delete_inventory_type" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if (Permissions::user(['update_inventories_types']) == true) : ?>
                        <td class="button">
                            <?php if ($value['system'] == false) : ?>
                                <?php if ($value['blocked'] == false) : ?>
                                    <a data-action="update_inventory_type" data-id="<?php echo $value['id']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php if (Permissions::user(['create_inventories_types','update_inventories_types']) == true) : ?>
    <section class="modal" data-modal="create_inventory_type">
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
                                    <select name="movement">
                                        <option value="" hidden>{$lang.select}</option>
                                        <option value="input">Entrada</option>
                                        <option value="output">Salida</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.movement_type}</h6>
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
<?php if (Permissions::user(['delete_inventories_types']) == true) : ?>
    <section class="modal alert" data-modal="delete_inventory_type">
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
