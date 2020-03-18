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
