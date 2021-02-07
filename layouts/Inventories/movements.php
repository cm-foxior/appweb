<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Inventories/movements.js']);

?>

%{header}%
<header class="modbar">
    <div class="buttons">
        <?php if (!empty($data['branches'])) : ?>
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
            <fieldset class="fields-group">
                <div class="compound st-4-left">
                    <span><i class="fas fa-box"></i></span>
                    <select data-action="switch_inventory_period">
                        <option value="current" <?php echo ((System::temporal('get', 'inventories', 'period') == 'current') ? 'selected' : ''); ?>>{$lang.current_period}</option>
                        <?php foreach ($data['inventories_periods'] as $value) : ?>
                            <option value="<?php echo $value['id']; ?>" <?php echo ((System::temporal('get', 'inventories', 'period') == $value['id']) ? 'selected' : ''); ?>><?php echo Dates::format_date($value['started_date'], 'long_year') . ' - ' . Dates::format_date($value['end_date'], 'long_year'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </fieldset>
            <fieldset class="fields-group big">
                <div class="compound st-4-left">
                    <span><i class="fas fa-search"></i></span>
                    <input type="text" data-search="inventories_movements" placeholder="{$lang.search}">
                </div>
            </fieldset>
            <!-- <?php if (Permissions::user(['create_inventories_transfers']) == true) : ?>
                <a data-action="create_inventory_transfer" class="success"><i class="fas fa-share"></i><span>{$lang.transfer}</span></a>
            <?php endif; ?> -->
            <?php if (Permissions::user(['create_inventories_outputs']) == true) : ?>
                <a data-action="create_inventory_output" class="success"><i class="fas fa-minus"></i><span>{$lang.output}</span></a>
            <?php endif; ?>
            <?php if (Permissions::user(['create_inventories_inputs']) == true) : ?>
                <a data-action="create_inventory_input" class="success"><i class="fas fa-plus"></i><span>{$lang.input}</span></a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</header>
<?php if (!empty($data['branches'])) : ?>
    <main class="workspace">
        <?php if (!empty($data['inventories_transfers']['inputs'])) : ?>
            <table class="tbl-st-1">
                <thead>
                    <tr>
                        <th>{$lang.inputs_transfer}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['inventories_transfers']['inputs'] as $value) : ?>
                        <tr>
                            <td class="mediumtag"><span><?php echo Dates::format_date($value['created_date'], 'long'); ?></span></td>
                            <td>{$lang.since} <?php echo $value['branch']; ?></td>
                            <td class="smalltag"><span><?php echo count($value['products']) ?> {$lang.products}</span></td>
                            <td class="smalltag"><span>{$lang.<?php echo $value['status']; ?>}</span></td>
                            <td class="button"><a data-action="accept_inventory_transfer" data-id="<?php echo $value['id']; ?>" class="success"><i class="fas fa-check"></i><span>{$lang.accept}</span></a></td>
                            <td class="button"><a data-action="reject_inventory_transfer" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-times"></i><span>{$lang.reject}</span></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <?php if (!empty($data['inventories_transfers']['outputs'])) : ?>
            <table class="tbl-st-1">
                <thead>
                    <tr>
                        <th>{$lang.outputs_transfer}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['inventories_transfers']['outputs'] as $value) : ?>
                        <tr>
                            <td class="mediumtag"><span><?php echo Dates::format_date($value['created_date'], 'long'); ?></span></td>
                            <td>{$lang.toward} <?php echo $value['branch']; ?></td>
                            <td class="smalltag"><span><?php echo count($value['products']) ?> {$lang.products}</span></td>
                            <td class="smalltag"><span>{$lang.<?php echo $value['status']; ?>}</span></td>
                            <td class="button"><a data-action="cancel_inventory_transfer" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-times"></i><span>{$lang.cancel}</span></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <table class="tbl-st-1" data-table="inventories_movements">
            <tbody>
                <?php foreach ($data['inventories_movements'] as $value) : ?>
                    <tr>
                        <td class="mediumtag"><span><?php echo Dates::format_date($value['date'], 'short') . ' ' . Dates::format_hour($value['hour'], '12-short'); ?></span></td>
                        <?php if ($value['movement'] == 'input') : ?>
                            <td class="smalltag left"><span class="success"><i class="fas fa-arrow-up"></i>{$lang.input}</span></td>
                        <?php elseif ($value['movement'] == 'output') : ?>
                            <td class="smalltag right"><span class="busy">{$lang.output}<i class="fas fa-arrow-down"></i></span></td>
                        <?php endif; ?>
                        <td class="smalltag"><span><?php echo (($value['type_system'] == true) ? $value['type_name'][Session::get_value('vkye_account')['language']] : $value['type_name']); ?></span></td>
                        <td class="smalltag"><span><?php echo number_format($value['quantity'], 2, '.', '') . ' ' . (($value['product_unity_system'] == true) ? $value['product_unity_name'][Session::get_value('vkye_account')['language']] : $value['product_unity_name']); ?></span></td>
                        <td class="hidden"><?php echo $value['product_token']; ?></td>
                        <td>
                            <strong><?php echo $value['product_name']; ?></strong>
                            <?php if (!empty($value['origin'])) : ?>
                                <?php if ($value['origin']['type'] == 'cnt') : ?>
                                    <?php echo ' (' . $value['origin']['quantity'] . ') ' . $value['origin']['content']['amount'] . ' ' . (($value['origin']['content']['unity_system'] == true) ? $value['origin']['content']['unity_name'][Session::get_value('vkye_account')['language']] : $value['origin']['content']['unity_name']); ?>
                                <?php elseif ($value['origin']['type'] == 'chd') : ?>
                                    <?php echo ' (' . $value['origin']['quantity'] . ') ' . $value['origin']['child']['name']; ?>
                                <?php elseif ($value['origin']['type'] == 'prt') : ?>
                                    <?php echo ' (' . $value['origin']['quantity'] . (($value['origin']['parent']['inventory'] == true) ? (($value['origin']['parent']['unity_system'] == true) ? ' ' . $value['origin']['parent']['unity_name'][Session::get_value('vkye_account')['language']] : $value['origin']['parent']['unity_name']) : '') . ') ' . $value['origin']['parent']['name']; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class="smalltag"><span>{$lang.<?php echo $value['product_type']; ?>}</span></td>
                        <?php if (Permissions::user(['delete_inventories_inputs','delete_inventories_outputs']) == true) : ?>
                            <td class="button">
                                <?php if ($value['closed'] == false AND $value['type_id'] != '4' AND $value['type_id'] != '7') : ?>
                                    <a data-action="delete_inventory_movement" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <!-- <?php if (Permissions::user(['update_inventories_inputs','update_inventories_outputs']) == true) : ?>
                            <td class="button">
                                <?php if ($value['closed'] == false) : ?>
                                    <a data-action="update_inventory_movement" data-id="<?php echo $value['id']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?> -->
                        <!-- <td class="button"><a data-action="read_inventory_movement" data-id="<?php echo $value['id']; ?>"><i class="fas fa-info-circle"></i><span>{$lang.more_information}</span></a></td> -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <?php if (Permissions::user(['create_inventories_inputs']) == true) : ?>
        <section class="modal" data-modal="create_inventory_input">
            <div class="content">
                <main>
                    <form>
                        <?php if (!empty($data['products_inputs'])) : ?>
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
                                    <?php foreach ($data['products_inputs'] as $value) : ?>
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
                                <div class="row">
                                    <div class="span6">
                                        <div class="compound st-3-left">
                                            <span class="first"><i class="fas fa-dollar-sign"></i></span>
                                            <input type="text" name="cost" placeholder="{$lang.type}">
                                            <span class="last"><?php echo Session::get_value('vkye_account')['currency']; ?></span>
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.cost}</h6>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="text">
                                            <select name="location">
                                                <option value="">{$lang.not_established}</option>
                                                <?php foreach ($data['inventories_locations'] as $value) : ?>
                                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.location}</h6>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="fields-group">
                                <div class="compound st-4-left">
                                    <span><i class="fas fa-search"></i></span>
                                    <input type="text" data-search="categories" placeholder="{$lang.search}">
                                </div>
                                <div class="checkbox st-1" data-table="categories">
                                    <?php foreach ($data['inventories_categories'] as $value) : ?>
                                        <label class="hidden">
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
                                <table class="tbl-st-1" data-table="inputs">
                                    <tbody>
                                        <?php if (!empty(System::temporal('get', 'inventories', 'inputs'))) : ?>
                                            <?php foreach (array_reverse(System::temporal('get', 'inventories', 'inputs')) as $value) : ?>
                                                <tr class="first">
                                                    <td><span><strong><?php echo $value['product']['name']; ?></strong></span></td>
                                                    <td></td>
                                                </tr>
                                                <?php foreach ($value['list'] as $subkey => $subvalue): ?>
                                                    <tr class="half">
                                                        <td>
                                                            <span>{$lang.quantity}: <?php echo ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? '(' . $subvalue['quantity'][0] . ') ' . $value['product']['contents'][$subvalue['content'][1]]['content']['amount'] . ' ' . (($value['product']['contents'][$subvalue['content'][1]]['content']['unity_system'] == true) ? $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name']) . ' (' : '') . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? ')' : ''); ?></span>
                                                            <span>{$lang.unitary_cost}: <?php echo Currency::format($subvalue['cost'], Session::get_value('vkye_account')['currency']); ?></span>
                                                            <span>{$lang.total_cost}: <?php echo Currency::format($subvalue['total'], Session::get_value('vkye_account')['currency']); ?></span>
                                                            <span><?php echo (!empty($subvalue['location']) ? '{$lang.location}: ' . $subvalue['location']['name'] : '{$lang.not_location}'); ?></span>
                                                            <span><?php echo (!empty($subvalue['categories']) ? '{$lang.categories}: ' . System::summation('string', $subvalue['categories'], 'name') : '{$lang.not_categories}'); ?></span>
                                                        </td>
                                                        <td class="button"><a data-action="remove_product_to_inputs_table" data-id="<?php echo $value['product']['id'] . '_' . $subkey; ?>" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <tr class="last">
                                                    <td><span><?php echo Currency::format(System::summation('math', $value['list'], 'total'), Session::get_value('vkye_account')['currency']); ?></span></td>
                                                    <td></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="last">
                                                <td><span><strong><?php echo Currency::format(System::summation('math', System::temporal('get', 'inventories', 'inputs'), 'total', 'list'), Session::get_value('vkye_account')['currency']); ?></strong></span></td>
                                                <td></td>
                                            </tr>
                                        <?php else : ?>
                                            <tr>
                                                <td class="message">{$lang.not_records_in_the_table}</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <div class="title">
                                    <h6>{$lang.inputs_list}</h6>
                                </div>
                            </fieldset>
                            <fieldset class="fields-group">
                                <div class="row">
                                    <div class="span3">
                                        <div class="text">
                                            <input type="date" name="date" value="<?php echo Dates::current_date(); ?>" max="<?php echo Dates::current_date(); ?>" placeholder="{$lang.select}">
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.date}</h6>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="text">
                                            <input type="time" name="hour" value="<?php echo Dates::current_hour(); ?>" placeholder="{$lang.select}">
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.hour}</h6>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="text">
                                            <select name="type">
                                                <?php foreach ($data['inventories_types_inputs'] as $value) : ?>
                                                    <option value="<?php echo $value['id']; ?>"><?php echo (($value['system'] == true) ? $value['name'][Session::get_value('vkye_account')['language']] : $value['name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.movement_type}</h6>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="text">
                                            <select name="provider">
                                                <option value="">{$lang.not_established}</option>
                                                <?php foreach ($data['providers'] as $value) : ?>
                                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.provider}</h6>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="fields-group">
                                <div class="row">
                                    <div class="span4">
                                        <div class="text">
                                            <select name="bill_type">
                                                <option value="">{$lang.not_established}</option>
                                                <option value="bill">{$lang.bill}</option>
                                                <option value="ticket">{$lang.ticket}</option>
                                            </select>
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.bill_type}</h6>
                                        </div>
                                    </div>
                                    <div class="span8">
                                        <div class="compound st-2-left">
                                            <a data-action="generate_random_bill_token"><i class="fas fa-redo" aria-hidden="true"></i></a>
                                            <input type="text" name="bill_token" placeholder="{$lang.type}" disabled>
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.bill_token}</h6>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="fields-group">
                                <div class="row">
                                    <div class="span4">
                                        <div class="text">
                                            <select name="bill_payment_way" disabled>
                                                <?php foreach (Functions::payments_ways() as $value): ?>
                                                    <option value="<?php echo $value['code']; ?>"><?php echo $value['name'][Session::get_value('vkye_account')['language']]; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.bill_payment_way}</h6>
                                        </div>
                                    </div>
                                    <div class="span4">
                                        <div class="text">
                                            <input type="text" name="bill_iva" placeholder="{$lang.type}" disabled>
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.bill_iva}</h6>
                                        </div>
                                    </div>
                                    <div class="span4">
                                        <div class="compound st-1-left">
                                            <select name="bill_discount_type">
                                                <option value="$">$</option>
                                                <option value="%">%</option>
                                            </select>
                                            <input type="text" name="bill_discount_amount" placeholder="{$lang.type}">
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.bill_discount}</h6>
                                        </div>
                                    </div>
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
                                <a class="alert" button-close><i class="fas fa-times"></i></a>
                                <?php if (!empty($data['products_inputs'])) : ?>
                                    <button type="submit" class="success"><i class="fas fa-check"></i></button>
                                <?php endif; ?>
                            </div>
                        </fieldset>
                    </form>
                </main>
            </div>
        </section>
    <?php endif; ?>
    <?php if (Permissions::user(['create_inventories_outputs']) == true) : ?>
        <section class="modal" data-modal="create_inventory_output">
            <div class="content">
                <main>
                    <form>
                        <?php if (!empty($data['products_outputs'])) : ?>
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
                                    <?php foreach ($data['products_outputs'] as $value) : ?>
                                        <div data-list class="hidden">
                                            <div>
                                                <figure>
                                                    <img src="<?php echo (!empty($value['avatar']) ? '{$path.uploads}' . $value['avatar'] : '{$path.images}product.png'); ?>">
                                                </figure>
                                                <p><?php echo '<span class="hidden">' . $value['token'] . '</span>' . $value['name'] . ' <span>{$lang.' . $value['type'] . '}' . (($value['inventory'] == false) ? ' | {$lang.not_subject_to_inventory}' : '') . '</span>'; ?></p>
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
                                <table class="tbl-st-1" data-table="outputs">
                                    <tbody>
                                        <?php if (!empty(System::temporal('get', 'inventories', 'outputs'))) : ?>
                                            <?php foreach (array_reverse(System::temporal('get', 'inventories', 'outputs')) as $key => $value): ?>
                                                <?php if ($value['product']['inventory'] == true) : ?>
                                                    <?php foreach ($value['list'] as $subkey => $subvalue) : ?>
                                                        <tr class="first">
                                                            <td><span><strong><?php echo '(' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? $subvalue['quantity'][0] : number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name'])) . ') ' . $value['product']['name'] . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? ' ' . $value['product']['contents'][$subvalue['content'][1]]['content']['amount'] . ' ' . (($value['product']['contents'][$subvalue['content'][1]]['content']['unity_system'] == true) ? $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name']) . ' (' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')' : ''); ?></strong></span></td>
                                                            <td class="button"><a data-action="remove_product_to_outputs_table" data-id="<?php echo $value['product']['id'] . '_' . $subkey; ?>" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr class="first">
                                                        <td><span><strong><?php echo ((!empty($value['product']['formula']) AND !empty($value['product']['formula']['parent'])) ? '(' . $value['list'][0]['quantity'][0] . ') ' . $value['product']['name'] . ' (' . number_format($value['list'][0]['quantity'][1], 2, '.', '') . ' ' . (($value['product']['formula']['parent']['unity_system'] == true) ? $value['product']['formula']['parent']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['formula']['parent']['unity_name']) . ' / ' . $value['product']['formula']['parent']['name'] . ')' : '(' . $value['list'][0]['quantity'][1] . ') ' . $value['product']['name']); ?></strong></span></td>
                                                        <td class="button"><a data-action="remove_product_to_outputs_table" data-id="<?php echo $value['product']['id']; ?>_0" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
                                                    </tr>
                                                    <?php if (!empty($value['product']['formula']) AND !empty($value['product']['formula']['parent'])) : ?>
                                                        <?php if (!empty($value['list'][0]['supplies'])) : ?>
                                                            <?php foreach ($value['list'][0]['supplies'] as $subkey => $subvalue) : ?>
                                                                <tr class="half">
                                                                    <td><span><?php echo '(' . number_format($subvalue['quantity'], 2, '.', '') . ' ' . (($subvalue['product']['unity_system'] == true) ? $subvalue['product']['unity_name'][Session::get_value('vkye_account')['language']] : $subvalue['product']['unity_name']) . ') ' . $subvalue['product']['name']; ?></span></td>
                                                                    <td></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if (!empty($value['supplies'])) : ?>
                                                    <?php foreach ($value['supplies'] as $subkey => $subvalue) : ?>
                                                        <tr class="half">
                                                            <td><span><?php echo '(' . number_format($subvalue['quantity'], 2, '.', '') . ' ' . (($subvalue['product']['unity_system'] == true) ? $subvalue['product']['unity_name'][Session::get_value('vkye_account')['language']] : $subvalue['product']['unity_name']) . ') ' . $subvalue['product']['name']; ?></span></td>
                                                            <td></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td class="message">{$lang.not_records_in_the_table}</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <div class="title">
                                    <h6>{$lang.outputs_list}</h6>
                                </div>
                            </fieldset>
                            <fieldset class="fields-group">
                                <div class="row">
                                    <div class="span4">
                                        <div class="text">
                                            <input type="date" name="date" value="<?php echo Dates::current_date(); ?>" max="<?php echo Dates::current_date(); ?>" placeholder="{$lang.select}">
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.date}</h6>
                                        </div>
                                    </div>
                                    <div class="span4">
                                        <div class="text">
                                            <input type="time" name="hour" value="<?php echo Dates::current_hour(); ?>" placeholder="{$lang.select}">
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.hour}</h6>
                                        </div>
                                    </div>
                                    <div class="span4">
                                        <div class="text">
                                            <select name="type">
                                                <?php foreach ($data['inventories_types_outputs'] as $value) : ?>
                                                    <option value="<?php echo $value['id']; ?>"><?php echo (($value['system'] == true) ? $value['name'][Session::get_value('vkye_account')['language']] : $value['name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.movement_type}</h6>
                                        </div>
                                    </div>
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
                                <a class="alert" button-close><i class="fas fa-times"></i></a>
                                <?php if (!empty($data['products_outputs'])) : ?>
                                    <button type="submit" class="success"><i class="fas fa-check"></i></button>
                                <?php endif; ?>
                            </div>
                        </fieldset>
                    </form>
                </main>
            </div>
        </section>
    <?php endif; ?>
    <?php if (Permissions::user(['create_inventories_transfers']) == true) : ?>
        <section class="modal" data-modal="create_inventory_transfer">
            <div class="content">
                <main>
                    <form>
                        <?php if (!empty($data['products_inputs'])) : ?>
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
                                    <?php foreach ($data['products_outputs'] as $value) : ?>
                                        <div data-list class="hidden">
                                            <div>
                                                <figure>
                                                    <img src="<?php echo (!empty($value['avatar']) ? '{$path.uploads}' . $value['avatar'] : '{$path.images}product.png'); ?>">
                                                </figure>
                                                <p><?php echo '<span class="hidden">' . $value['token'] . '</span>' . $value['name'] . ' <span>{$lang.' . $value['type'] . '}' . (($value['inventory'] == false) ? ' | {$lang.not_subject_to_inventory}' : '') . '</span>'; ?></p>
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
                                <table class="tbl-st-1" data-table="transfers">
                                    <tbody>
                                        <?php if (!empty(System::temporal('get', 'inventories', 'transfers'))) : ?>
                                            <?php foreach (array_reverse(System::temporal('get', 'inventories', 'transfers')) as $key => $value): ?>
                                                <?php if ($value['product']['inventory'] == true) : ?>
                                                    <?php foreach ($value['list'] as $subkey => $subvalue) : ?>
                                                        <tr class="first">
                                                            <td><span><strong><?php echo '(' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? $subvalue['quantity'][0] : number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name'])) . ') ' . $value['product']['name'] . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? ' ' . $value['product']['contents'][$subvalue['content'][1]]['content']['amount'] . ' ' . (($value['product']['contents'][$subvalue['content'][1]]['content']['unity_system'] == true) ? $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name']) . ' (' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')' : ''); ?></strong></span></td>
                                                            <td class="button"><a data-action="remove_product_to_outputs_table" data-id="<?php echo $value['product']['id'] . '_' . $subkey; ?>" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr class="first">
                                                        <td><span><strong><?php echo ((!empty($value['product']['formula']) AND !empty($value['product']['formula']['parent'])) ? '(' . $value['list'][0]['quantity'][0] . ') ' . $value['product']['name'] . ' (' . number_format($value['list'][0]['quantity'][1], 2, '.', '') . ' ' . (($value['product']['formula']['parent']['unity_system'] == true) ? $value['product']['formula']['parent']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['formula']['parent']['unity_name']) . ' / ' . $value['product']['formula']['parent']['name'] . ')' : '(' . $value['list'][0]['quantity'][1] . ') ' . $value['product']['name']); ?></strong></span></td>
                                                        <td class="button"><a data-action="remove_product_to_outputs_table" data-id="<?php echo $value['product']['id']; ?>_0" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
                                                    </tr>
                                                    <?php if (!empty($value['product']['formula']) AND !empty($value['product']['formula']['parent'])) : ?>
                                                        <?php if (!empty($value['list'][0]['supplies'])) : ?>
                                                            <?php foreach ($value['list'][0]['supplies'] as $subkey => $subvalue) : ?>
                                                                <tr class="half">
                                                                    <td><span><?php echo '(' . number_format($subvalue['quantity'], 2, '.', '') . ' ' . (($subvalue['product']['unity_system'] == true) ? $subvalue['product']['unity_name'][Session::get_value('vkye_account')['language']] : $subvalue['product']['unity_name']) . ') ' . $subvalue['product']['name']; ?></span></td>
                                                                    <td></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if (!empty($value['supplies'])) : ?>
                                                    <?php foreach ($value['supplies'] as $subkey => $subvalue) : ?>
                                                        <tr class="half">
                                                            <td><span><?php echo '(' . number_format($subvalue['quantity'], 2, '.', '') . ' ' . (($subvalue['product']['unity_system'] == true) ? $subvalue['product']['unity_name'][Session::get_value('vkye_account')['language']] : $subvalue['product']['unity_name']) . ') ' . $subvalue['product']['name']; ?></span></td>
                                                            <td></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td class="message">{$lang.not_records_in_the_table}</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <div class="title">
                                    <h6>{$lang.transfers_list}</h6>
                                </div>
                            </fieldset>
                            <fieldset class="fields-group">
                                <div class="text">
                                    <select name="branch">
                                        <?php foreach ($data['branches'] as $value) : ?>
                                            <option value="" hidden>{$lang.select}</option>
                                            <?php if (Permissions::branch($value['id']) == true AND System::temporal('get', 'inventories', 'branch')['id'] != $value['id']) : ?>
                                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.branch}</h6>
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
                                <a class="alert" button-close><i class="fas fa-times"></i></a>
                                <?php if (!empty($data['products_inputs'])) : ?>
                                    <button type="submit" class="success"><i class="fas fa-check"></i></button>
                                <?php endif; ?>
                            </div>
                        </fieldset>
                    </form>
                </main>
            </div>
        </section>
    <?php endif; ?>
    <!-- <section class="modal" data-modal="read_inventory_movement">
        <div class="content">
            <main>
                <div class="preview-stl-2"></div>
                <fieldset class="fields-group">
                    <div class="button">
                        <a class="success" button-close><i class="fas fa-check"></i></a>
                    </div>
                </fieldset>
            </main>
        </div>
    </section> -->
    <?php if (Permissions::user(['delete_inventories_inputs','delete_inventories_outputs']) == true) : ?>
        <section class="modal alert" data-modal="delete_inventory_movement">
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
