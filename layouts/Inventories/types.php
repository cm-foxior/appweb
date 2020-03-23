<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Inventories/types.min.js']);

?>

%{header}%
<header class="modbar">
    <?php if (Permissions::user(['inventories'], true) == true) : ?>
    <a href="/inventories" class="unfocus"><i class="fas fa-box-open"></i><span>{$lang.inventories}</span></a>
    <span></span>
    <?php endif; ?>
    <a href="/inventories/types"><i class="fas fa-bookmark"></i><span>{$lang.types}</span></a>
    <?php if (Permissions::user(['inventories_locations'], true) == true) : ?>
    <a href="/inventories/locations" class="unfocus"><i class="fas fa-map-marker-alt"></i><span>{$lang.locations}</span></a>
    <?php endif; ?>
    <?php if (Permissions::user(['inventories_categories'], true) == true) : ?>
    <a href="/inventories/categories" class="unfocus"><i class="fas fa-tag"></i><span>{$lang.categories}</span></a>
    <?php endif; ?>
    <span></span>
    <?php if (Permissions::user(['create_inventories_types']) == true) : ?>
    <a data-action="create_inventory_type" class="success"><i class="fas fa-plus"></i><span>{$lang.create}</span></a>
    <?php endif; ?>
    <fieldset class="fields-group">
        <div class="compound st-4-left">
            <span><i class="fas fa-search"></i></span>
            <input type="text" data-search="inventories_types" placeholder="{$lang.search}">
        </div>
    </fieldset>
    <span></span>
    <fieldset class="fields-group">
        <div class="compound st-4-left">
            <span><i class="fas fa-bookmark"></i></span>
            <select data-search="inventories_types">
                <option value="" selected>{$lang.all_types}</option>
                <option value="{$lang.input}">{$lang.input}</option>
                <option value="{$lang.output}">{$lang.output}</option>
            </select>
        </div>
    </fieldset>
</header>
<main>
    <table class="tbl-st-1" data-table="inventories_types">
        <tbody>
            <?php foreach ($data['inventories_types'] as $value) : ?>
            <tr>
                <td><?php echo $value['name']; ?></td>
                <td class="smalltag"><span>{$lang.<?php echo $value['movement']; ?>}</span></td>
                <td class="smalltag">
                    <?php if ($value['system'] == false) : ?>
                        <?php if ($value['blocked'] == true) : ?>
                        <span class="busy">{$lang.blocked}</span>
                        <?php elseif ($value['blocked'] == false) : ?>
                        <span>{$lang.unblocked}</span>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <?php if (Permissions::user(['block_inventories_types','unblock_inventories_types']) == true) : ?>
                <td class="button">
                    <?php if ($value['system'] == false) : ?>
                        <?php if ($value['blocked'] == true) : ?>
                        <a data-action="unblock_inventory_type" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock-open"></i><span>{$lang.unblock}</span></a>
                        <?php elseif ($value['blocked'] == false) : ?>
                        <a data-action="block_inventory_type" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock"></i><span>{$lang.block}</span></a>
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
                                <input type="text" name="name" placeholder="{$lang.name}">
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <select name="movement">
                                    <option value="" selected hidden>{$lang.movement}</option>
                                    <option value="input">Entrada</option>
                                    <option value="output">Salida</option>
                                </select>
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
<?php if (Permissions::user(['delete_inventories_types']) == true) : ?>
<section class="modal alert" data-modal="delete_inventory_type">
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
