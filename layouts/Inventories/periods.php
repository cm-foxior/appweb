<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Inventories/periods.js']);

?>

%{header}%
<header class="modbar">
    <div class="buttons">
        <?php if (!empty($data['branches'])) : ?>
            <?php if ($data['render'] == 'create' OR $data['render'] == 'list') : ?>
                <fieldset class="fields-group">
                    <div class="compound st-4-left">
                        <span><i class="fas fa-store"></i></span>
                        <select data-action="switch_branch">
                            <?php foreach ($data['branches'] as $value) : ?>
                                <?php if (Permissions::branch($value['id']) == true) : ?>
                                    <option value="<?php echo $value['id']; ?>" <?php echo ((System::temporal('get', 'inventories', 'branch')['id'] == $value['id']) ? 'selected' : ''); ?>><?php echo $value['name']; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </fieldset>
            <?php endif; ?>
            <fieldset class="fields-group big">
                <div class="compound st-4-left">
                    <span><i class="fas fa-search"></i></span>
                    <input type="text" data-search="<?php echo (($data['render'] == 'create' OR $data['render'] == 'update' OR $data['render'] == 'results') ? 'inventories_existences' : 'inventories_periods') ?>" placeholder="{$lang.search}">
                </div>
            </fieldset>
            <?php if ($data['render'] == 'list' AND Permissions::user(['create_inventories_periods']) == true) : ?>
                <a href="/inventories/periods/create" class="success"><i class="fas fa-plus"></i><span>{$lang.create}</span></a>
            <?php endif; ?>
            <?php if ($data['render'] == 'create' OR $data['render'] == 'update' OR $data['render'] == 'results') : ?>
                <a href="/inventories/periods"><i class="fas fa-chevron-left"></i><span>{$lang.return}</span></a>
            <?php endif; ?>
            <?php if (($data['render'] == 'create' AND Permissions::user(['create_inventories_periods']) == true) OR ($data['render'] == 'update' AND Permissions::user(['update_inventories_periods']) == true)) : ?>
                <a data-action="upload_physical" class="<?php echo (($data['render'] == 'create') ? 'success' : 'warning'); ?>"><i class="fas fa-upload"></i><span>{$lang.upload_physical}</span></a>
                <a data-action="<?php echo (($data['render'] == 'create') ? 'create_inventory_period' : 'update_inventory_period'); ?>" class="<?php echo (($data['render'] == 'create') ? 'success' : 'warning'); ?>"><i class="fas fa-save"></i><span>{$lang.save}</span></a>
            <?php endif; ?>
            <?php if ($data['render'] == 'results' AND Permissions::user(['update_inventories_periods']) == true AND $data['period']['saved'] == 'draft') : ?>
                <a href="/inventories/periods/update/<?php echo $data['period']['id']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</header>
<?php if (!empty($data['branches'])) : ?>
    <main class="workspace">
        <?php if ($data['render'] == 'create' OR $data['render'] == 'update' OR $data['render'] == 'results') : ?>
            <?php if ($data['render'] == 'update' OR $data['render'] == 'results') : ?>
                <div class="period_results_data">
                    <div>
                        <p><strong>{$lang.started_date}</strong>: <?php echo Dates::format_date($data['period']['started_date'], 'long'); ?></p>
                        <p><strong>{$lang.end_date}</strong>: <?php echo Dates::format_date($data['period']['end_date'], 'long'); ?></p>
                        <p><strong>{$lang.previous_period}</strong>: <?php echo (!empty($data['period']['previous']) ? Dates::format_date($data['period']['previous_started_date'], 'long') . ' - ' . Dates::format_date($data['period']['previous_end_date'], 'long') : '{$lang.not_available}'); ?></p>
                        <p><strong>{$lang.status}</strong>: {$lang.<?php echo $data['period']['saved']; ?>} <?php echo (($data['period']['last'] == true) ? '({$lang.last_period})' : ''); ?></p>
                        <p><strong><?php echo count($data['period']['products']); ?></strong> {$lang.products}</p>
                        <p><strong><?php echo count($data['period']['audits']); ?></strong> {$lang.audits}</p>
                    </div>
                    <div>
                        <div>
                            <figure>
                                <img src="<?php echo (!empty($data['period']['user_avatar']) ? '{$path.uploads}' . $data['period']['user_avatar'] : 'https://cdn.codemonkey.com.mx/monkeyboard/assets/images/user.png'); ?>">
                            </figure>
                            <p><?php echo $data['period']['user_firstname'] . ' ' . $data['period']['user_lastname']; ?></p>
                        </div>
                        <p><strong>{$lang.created_date}</strong>: <?php echo Dates::format_date($data['period']['created_date'], 'long'); ?></p>
                        <p><strong>{$lang.created_hour}</strong>: <?php echo Dates::format_hour($data['period']['created_hour'], '12-long'); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <table class="tbl-st-1" data-table="inventories_existences">
                <tbody>
                    <?php foreach ($data['inventories_existences'] as $value) : ?>
                        <tr>
                            <td class="smalltag"><span>{$lang.<?php echo $value['type']; ?>}</span></td>
                            <td><?php echo $value['name']; ?></td>
                            <td class="smalltag"><span class="<?php echo (array_key_exists($value['id'], System::temporal('get', 'inventories', 'aperiod')['physical']) ? (($value['existence']['theoretical'] <= $value['existence']['physical']) ? (($value['existence']['theoretical'] == $value['existence']['physical']) ? 'success' : 'busy') : 'alert') : ''); ?>">{$lang.theoretical}: <?php echo number_format($value['existence']['theoretical'], 2, '.', ''); ?></span></td>
                            <td class="smalltag"><span class="<?php echo (array_key_exists($value['id'], System::temporal('get', 'inventories', 'aperiod')['physical']) ? (($value['existence']['theoretical'] <= $value['existence']['physical']) ? (($value['existence']['theoretical'] == $value['existence']['physical']) ? 'success' : 'busy') : 'alert') : ''); ?>">{$lang.physical}: <?php echo number_format($value['existence']['physical'], 2, '.', ''); ?></span></td>
                            <td class="smalltag"><span class="<?php echo (array_key_exists($value['id'], System::temporal('get', 'inventories', 'aperiod')['physical']) ? (($value['existence']['theoretical'] <= $value['existence']['physical']) ? (($value['existence']['theoretical'] == $value['existence']['physical']) ? 'success' : 'busy') : 'alert') : ''); ?>">{$lang.variation}: <?php echo number_format($value['variation'], 2, '.', ''); ?></span></td>
                            <td class="smalltag"><span><?php echo (($value['unity_system'] == true) ? $value['unity_name'][Session::get_value('vkye_account')['language']] : $value['unity_name']); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($data['render'] == 'list') : ?>
            <table class="tbl-st-1" data-table="inventories_periods">
                <tbody>
                    <?php foreach ($data['inventories_periods'] as $value) : ?>
                        <tr>
                            <td class="smalltag"><span><?php echo Dates::format_date($value['started_date'], 'long_year'); ?></span></td>
                            <td class="smalltag"><span><?php echo Dates::format_date($value['end_date'], 'long_year'); ?></span></td>
                            <td></td>
                            <td class="smalltag"><span><?php echo count($value['products']); ?> {$lang.products}</span></td>
                            <td class="smalltag"><span><?php echo count($value['audits']); ?> {$lang.audits}</span></td>
                            <td class="smalltag"><span>{$lang.<?php echo $value['saved']; ?>}</span></td>
                            <?php if (Permissions::user(['delete_inventories_periods']) == true) : ?>
                                <td class="button">
                                    <?php if ($value['last'] == true OR $value['saved'] == 'draft') : ?>
                                        <a data-action="delete_inventory_period" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a></td>
                                    <?php endif; ?>
                            <?php endif; ?>
                            <?php if (Permissions::user(['update_inventories_periods']) == true) : ?>
                                <td class="button">
                                    <?php if ($value['last'] == true OR $value['saved'] == 'draft') : ?>
                                        <a href="/inventories/periods/update/<?php echo $value['id']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                            <td class="button"><a href="/inventories/periods/results/<?php echo $value['id']; ?>"><i class="fas fa-tasks"></i><span>{$lang.view_results}</span></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
    <?php if (($data['render'] == 'create' AND Permissions::user(['create_inventories_periods']) == true) OR ($data['render'] == 'update' AND Permissions::user(['update_inventories_periods']) == true) OR $data['render'] == 'results') : ?>
        <?php if (($data['render'] == 'create' AND Permissions::user(['create_inventories_periods']) == true) OR ($data['render'] == 'update' AND Permissions::user(['update_inventories_periods']) == true)) : ?>
            <section class="modal" data-modal="upload_physical">
                <div class="content">
                    <main>
                        <form>
                            <?php if (!empty($data['products'])) : ?>
                                <fieldset class="fields-group">
                                    <div class="compound st-6">
                                        <div data-preview>
                                            <div></div>
                                            <a data-cancel class="alert"><i class="fas fa-times"></i></a>
                                            <a data-success class="success"><i class="fas fa-check"></i></a>
                                            <input type="text" name="product_id">
                                        </div>
                                        <div data-search>
                                            <span><i class="fas fa-search"></i></span>
                                            <input type="text" name="product_token" placeholder="{$lang.search}">
                                        </div>
                                        <?php foreach ($data['products'] as $value) : ?>
                                            <div data-list class="hidden">
                                                <div>
                                                    <figure>
                                                        <img src="<?php echo (!empty($value['avatar']) ? '{$path.uploads}' . $value['avatar'] : '{$path.images}product.png'); ?>">
                                                    </figure>
                                                    <p><?php echo '<span class="hidden">' . $value['token'] . '</span>' . $value['name'] . ' <span>{$lang.' . $value['type'] . '}</span>'; ?></p>
                                                </div>
                                                <a data-success data-value="<?php echo $value['id']; ?>"></a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="title">
                                        <h6>{$lang.product}</h6>
                                    </div>
                                </fieldset>
                                <fieldset class="fields-group">
                                    <div class="row">
                                        <div class="span4">
                                            <div class="compound st-5-double">
                                                <div>
                                                    <label>
                                                        <input type="radio" name="saved" value="quantity" checked>
                                                        <span class="checked">{$lang.quantity}</span>
                                                    </label>
                                                </div>
                                                <div>
                                                    <label>
                                                        <input type="radio" name="saved" value="weight">
                                                        <span>{$lang.weight}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="title">
                                                <h6>{$lang.saved}</h6>
                                            </div>
                                        </div>
                                        <div class="span8">
                                            <div class="compound st-8">
                                                <span class="first">{$lang.unity}</span>
                                                <input type="text" name="quantity" placeholder="{$lang.type}">
                                                <input type="text" name="weight" class="hidden" placeholder="{$lang.type}">
                                                <select name="content" class="last">
                                                    <option value="">{$lang.not_content}</option>
                                                </select>
                                            </div>
                                            <div class="title">
                                                <h6>{$lang.quantity} / {$lang.weight}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="fields-group">
                                    <table class="tbl-st-1" data-table="physical">
                                        <tbody>
                                            <?php if (!empty(System::temporal('get', 'inventories', 'aperiod')['physical'])) : ?>
                                                <?php foreach (array_reverse(System::temporal('get', 'inventories', 'aperiod')['physical']) as $value) : ?>
                                                    <?php foreach ($value['list'] as $subkey => $subvalue) : ?>
                                                        <tr class="first">
                                                            <td><span><?php echo ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? '(' . $subvalue['quantity'][0] . ')' : '(' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')') . ' ' . $value['product']['name'] . ' ' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? $value['product']['contents'][$subvalue['content'][1]]['content']['amount'] . ' ' . (($value['product']['contents'][$subvalue['content'][1]]['content']['unity_system'] == true) ? $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name']) . ' (' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')' : ''); ?></span></td>
                                                            <td class="button"><a data-action="remove_product_to_physical_table" data-id="<?php echo $value['product']['id'] . '_' . $subkey; ?>" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <tr>
                                                    <td class="message">{$lang.not_records_in_the_table}</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <div class="title">
                                        <h6>{$lang.physical_list}</h6>
                                    </div>
                                </fieldset>
                            <?php else : ?>
                                <fieldset class="fields-group">
                                    <div class="button">
                                        <a href="/products/salemenu"><i class="fas fa-dollar-sign"></i><span>{$lang.sale_menu}</span></a>
                                        <a href="/products/supplies"><i class="fas fa-layer-group"></i><span>{$lang.supplies}</span></a>
                                        <a href="/products/workmaterial"><i class="fas fa-mail-bulk"></i><span>{$lang.work_material}</span></a>
                                    </div>
                                    <div class="message">
                                        <p>{$lang.to_select_products}</p>
                                    </div>
                                </fieldset>
                            <?php endif; ?>
                            <fieldset class="fields-group">
                                <div class="button">
                                    <?php if (!empty($data['products'])) : ?>
                                        <button type="submit" class="success"><i class="fas fa-check"></i></button>
                                    <?php endif; ?>
                                </div>
                            </fieldset>
                        </form>
                    </main>
                </div>
            </section>
            <section class="modal" data-modal="<?php echo (($data['render'] == 'create') ? 'create_inventory_period' : 'update_inventory_period'); ?>">
                <div class="content">
                    <main>
                        <form>
                            <fieldset class="fields-group">
                                <div class="row">
                                    <div class="span6">
                                        <div class="text">
                                            <input type="date" name="started_date" placeholder="{$lang.select}" value="<?php echo (($data['render'] == 'create') ? Dates::past_date(Dates::current_date(), '30', 'days') : $data['period']['started_date']); ?>"  max="<?php echo Dates::current_date(); ?>">
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.started_date}</h6>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="text">
                                            <input type="date" name="end_date" placeholder="{$lang.select}" value="<?php echo (($data['render'] == 'create') ? Dates::past_date(Dates::current_date(), '1', 'days') : $data['period']['end_date']); ?>"  max="<?php echo Dates::current_date(); ?>">
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.end_date}</h6>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="fields-group">
                                <div class="checkbox st-1">
                                    <label>
                                        <input type="radio" name="saved" value="closed" checked>
                                        <span>{$lang.saved_and_close}</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="saved" value="draft">
                                        <span>{$lang.saved_as_draft}</span>
                                    </label>
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
    <?php elseif ($data['render'] == 'list' AND Permissions::user(['delete_inventories_periods']) == true) : ?>
        <section class="modal alert" data-modal="delete_inventory_period">
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
<?php else : ?>
    <main class="workspace to-use">
        <a href="/branches" class="success"><i class="fas fa-plus"></i></a>
        <p>{$lang.to_use_inventories}</p>
    </main>
<?php endif; ?>
