<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Inventories/audits.js']);

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
            <?php if ($data['render'] == 'list') : ?>
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
            <?php endif; ?>
            <fieldset class="fields-group big">
                <div class="compound st-4-left">
                    <span><i class="fas fa-search"></i></span>
                    <input type="text" data-search="<?php echo (($data['render'] == 'create' OR $data['render'] == 'update' OR $data['render'] == 'results') ? 'inventories_existences' : 'inventories_audits') ?>" placeholder="{$lang.search}">
                </div>
            </fieldset>
            <?php if ($data['render'] == 'list' AND Permissions::user(['create_inventories_audits']) == true) : ?>
                <a href="/inventories/audits/create" class="success"><i class="fas fa-plus"></i><span>{$lang.create}</span></a>
            <?php endif; ?>
            <?php if ($data['render'] == 'create' OR $data['render'] == 'update' OR $data['render'] == 'results') : ?>
                <a href="/inventories/audits"><i class="fas fa-chevron-left"></i><span>{$lang.return}</span></a>
            <?php endif; ?>
            <?php if (($data['render'] == 'create' AND Permissions::user(['create_inventories_audits']) == true) OR ($data['render'] == 'update' AND Permissions::user(['update_inventories_audits']) == true)) : ?>
                <a data-action="upload_physical" class="<?php echo (($data['render'] == 'create') ? 'success' : 'warning'); ?>"><i class="fas fa-upload"></i><span>{$lang.upload_physical}</span></a>
                <a data-action="<?php echo (($data['render'] == 'create') ? 'create_inventory_audit' : 'update_inventory_audit'); ?>" class="<?php echo (($data['render'] == 'create') ? 'success' : 'warning'); ?>"><i class="fas fa-save"></i><span>{$lang.save}</span></a>
            <?php endif; ?>
            <?php if ($data['render'] == 'results' AND Permissions::user(['update_inventories_audits']) == true AND $data['audit']['saved'] == 'draft') : ?>
                <a href="/inventories/audits/update/<?php echo $data['audit']['id']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</header>
<?php if (!empty($data['branches'])) : ?>
    <main class="workspace">
        <?php if ($data['render'] == 'create' OR $data['render'] == 'update' OR $data['render'] == 'results') : ?>
            <?php if ($data['render'] == 'results') : ?>
                <div class="audit_results_data">
                    <div>
                        <p><strong>{$lang.started_date}</strong>: <?php echo Dates::format_date($data['audit']['started_date'], 'long'); ?></p>
                        <p><strong>{$lang.end_date}</strong>: <?php echo Dates::format_date($data['audit']['end_date'], 'long'); ?></p>
                        <p><strong>{$lang.comments}</strong>: <?php echo $data['audit']['comment']; ?></p>
                        <p><strong>{$lang.status}</strong>: {$lang.<?php echo $data['audit']['saved']; ?>}</p>
                        <p><strong><?php echo count($data['audit']['products']); ?></strong> {$lang.products}</p>
                        <p><strong><?php echo $data['audit']['success']; ?></strong> {$lang.success}</p>
                        <p><strong><?php echo $data['audit']['error']; ?></strong> {$lang.error}</p>
                    </div>
                    <div>
                        <div>
                            <figure>
                                <img src="<?php echo (!empty($data['audit']['user_avatar']) ? '{$path.uploads}' . $data['audit']['user_avatar'] : 'https://cdn.codemonkey.com.mx/monkeyboard/assets/images/user.png'); ?>">
                            </figure>
                            <p><?php echo $data['audit']['user_firstname'] . ' ' . $data['audit']['user_lastname']; ?></p>
                        </div>
                        <p><strong>{$lang.created_date}</strong>: <?php echo Dates::format_date($data['audit']['created_date'], 'long'); ?></p>
                        <p><strong>{$lang.created_hour}</strong>: <?php echo Dates::format_hour($data['audit']['created_hour'], '12-long'); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <table class="tbl-st-1" data-table="inventories_existences">
                <tbody>
                    <?php foreach ($data['inventories_existences'] as $value) : ?>
                        <tr>
                            <td class="smalltag"><span>{$lang.<?php echo $value['type']; ?>}</span></td>
                            <td><?php echo $value['name']; ?></td>
                            <td class="smalltag"><span class="<?php echo (array_key_exists($value['id'], System::temporal('get', 'inventories', 'audit')['physical']) ? (($value['existence']['theoretical'] <= $value['existence']['physical']) ? (($value['existence']['theoretical'] == $value['existence']['physical']) ? 'success' : 'busy') : 'alert') : ''); ?>">{$lang.theoretical}: <?php echo number_format($value['existence']['theoretical'], 2, '.', ''); ?></span></td>
                            <td class="smalltag"><span class="<?php echo (array_key_exists($value['id'], System::temporal('get', 'inventories', 'audit')['physical']) ? (($value['existence']['theoretical'] <= $value['existence']['physical']) ? (($value['existence']['theoretical'] == $value['existence']['physical']) ? 'success' : 'busy') : 'alert') : ''); ?>">{$lang.physical}: <?php echo number_format($value['existence']['physical'], 2, '.', ''); ?></span></td>
                            <td class="smalltag"><span class="<?php echo (array_key_exists($value['id'], System::temporal('get', 'inventories', 'audit')['physical']) ? (($value['existence']['theoretical'] <= $value['existence']['physical']) ? (($value['existence']['theoretical'] == $value['existence']['physical']) ? 'success' : 'busy') : 'alert') : ''); ?>">{$lang.variation}: <?php echo number_format($value['variation'], 2, '.', ''); ?></span></td>
                            <td class="smalltag"><span><?php echo (($value['unity_system'] == true) ? $value['unity_name'][Session::get_value('vkye_account')['language']] : $value['unity_name']); ?></span></td>
                            <?php if (($data['render'] == 'create' AND Permissions::user(['create_inventories_audits']) == true) OR ($data['render'] == 'update' AND Permissions::user(['update_inventories_audits']) == true) OR $data['render'] == 'results') : ?>
                                <td class="button"><a data-action="<?php echo (($data['render'] == 'create' OR $data['render'] == 'update') ? 'add_comment_to_inventory_audit' : 'read_comment_to_inventory_audit'); ?>" data-id="<?php echo $value['id']; ?>" class="<?php echo ((array_key_exists($value['id'], System::temporal('get', 'inventories', 'audit')['products']) AND !empty(System::temporal('get', 'inventories', 'audit')['products'][$value['id']]['comment'])) ? 'success' : ''); ?>"><i class="fas fa-comment-alt"></i><span>{$lang.comment}</span></a></td>
                                <td class="button"><a data-action="<?php echo (($data['render'] == 'create' OR $data['render'] == 'update') ? 'add_error_to_inventory_audit' : ''); ?>" data-id="<?php echo (($data['render'] == 'create' OR $data['render'] == 'update') ? $value['id'] : ''); ?>" class="<?php echo ((array_key_exists($value['id'], System::temporal('get', 'inventories', 'audit')['products']) AND System::temporal('get', 'inventories', 'audit')['products'][$value['id']]['status'] == 'error') ? 'alert' : ''); ?>"><i class="fas fa-times"></i><span>{$lang.error}</span></a></td>
                                <td class="button"><a data-action="<?php echo (($data['render'] == 'create' OR $data['render'] == 'update') ? 'add_success_to_inventory_audit' : ''); ?>" data-id="<?php echo (($data['render'] == 'create' OR $data['render'] == 'update') ? $value['id'] : ''); ?>" class="<?php echo ((array_key_exists($value['id'], System::temporal('get', 'inventories', 'audit')['products']) AND System::temporal('get', 'inventories', 'audit')['products'][$value['id']]['status'] == 'success') ? 'success' : ''); ?>"><i class="fas fa-check"></i><span>{$lang.success}</span></a></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($data['render'] == 'list') : ?>
            <table class="tbl-st-1" data-table="inventories_audits">
                <tbody>
                    <?php foreach ($data['inventories_audits'] as $value) : ?>
                        <tr>
                            <td class="smalltag"><span><?php echo Dates::format_date($value['started_date'], 'long_year'); ?></span></td>
                            <td class="smalltag"><span><?php echo Dates::format_date($value['end_date'], 'long_year'); ?></span></td>
                            <td><?php echo (!empty($value['comment']) ? $value['comment'] : '{$lang.not_comment}'); ?></td>
                            <td class="smalltag"><span><?php echo count($value['products']); ?> {$lang.products}</span></td>
                            <td class="smalltag"><span class="<?php echo (($value['success'] > 0) ? 'success' : ''); ?>"><?php echo $value['success']; ?> {$lang.success}</span></td>
                            <td class="smalltag"><span class="<?php echo (($value['error'] > 0) ? 'alert' : ''); ?>"><?php echo $value['error']; ?> {$lang.error}</span></td>
                            <td class="smalltag"><span>{$lang.<?php echo $value['saved']; ?>}</span></td>
                            <?php if (System::temporal('get', 'inventories', 'period') == 'current') : ?>
                                <?php if (Permissions::user(['delete_inventories_audits']) == true) : ?>
                                    <td class="button">
                                        <?php if ($value['last'] == true OR $value['saved'] == 'draft') : ?>
                                            <a data-action="delete_inventory_audit" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a></td>
                                        <?php endif; ?>
                                <?php endif; ?>
                                <?php if (Permissions::user(['update_inventories_audits']) == true) : ?>
                                    <td class="button">
                                        <?php if ($value['last'] == true OR $value['saved'] == 'draft') : ?>
                                            <a href="/inventories/audits/update/<?php echo $value['id']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            <?php endif; ?>
                            <td class="button"><a href="/inventories/audits/results/<?php echo $value['id']; ?>"><i class="fas fa-tasks"></i><span>{$lang.view_results}</span></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
    <?php if (($data['render'] == 'create' AND Permissions::user(['create_inventories_audits']) == true) OR ($data['render'] == 'update' AND Permissions::user(['update_inventories_audits']) == true) OR $data['render'] == 'results') : ?>
        <?php if (($data['render'] == 'create' AND Permissions::user(['create_inventories_audits']) == true) OR ($data['render'] == 'update' AND Permissions::user(['update_inventories_audits']) == true)) : ?>
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
                                            <?php if (!empty(System::temporal('get', 'inventories', 'audit')['physical'])) : ?>
                                                <?php foreach (array_reverse(System::temporal('get', 'inventories', 'audit')['physical']) as $value) : ?>
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
            <section class="modal" data-modal="<?php echo (($data['render'] == 'create') ? 'create_inventory_audit' : 'update_inventory_audit'); ?>">
                <div class="content">
                    <main>
                        <form>
                            <fieldset class="fields-group">
                                <div class="row">
                                    <div class="span6">
                                        <div class="text">
                                            <input type="date" name="started_date" placeholder="{$lang.select}" value="<?php echo (($data['render'] == 'create') ? Dates::past_date(Dates::current_date(), '7', 'days') : $data['audit']['started_date']); ?>"  max="<?php echo Dates::current_date(); ?>">
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.started_date}</h6>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="text">
                                            <input type="date" name="end_date" placeholder="{$lang.select}" value="<?php echo (($data['render'] == 'create') ? Dates::past_date(Dates::current_date(), '1', 'days') : $data['audit']['end_date']); ?>"  max="<?php echo Dates::current_date(); ?>">
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.end_date}</h6>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="fields-group">
                                <div class="text">
                                    <textarea name="comment"><?php echo (($data['render'] == 'create') ? '' : $data['audit']['comment']); ?></textarea>
                                </div>
                                <div class="title">
                                    <h6>{$lang.comment}</h6>
                                </div>
                            </fieldset>
                            <fieldset class="fields-group">
                                <div class="checkbox st-1">
                                    <label>
                                        <input type="radio" name="saved" value="adjust" checked>
                                        <span>{$lang.saved_and_adjust}</span>
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
        <section class="modal" data-modal="add_comment_to_inventory_audit">
            <div class="content">
                <main>
                    <form>
                        <?php if (($data['render'] == 'create' AND Permissions::user(['create_inventories_audits']) == true) OR ($data['render'] == 'update' AND Permissions::user(['update_inventories_audits']) == true)) : ?>
                            <fieldset class="fields-group">
                                <div class="row">
                                    <div class="span4">
                                        <div class="compound st-5-double">
                                            <div>
                                                <label>
                                                    <input type="radio" name="status" value="success">
                                                    <span>{$lang.success}</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label>
                                                    <input type="radio" name="status" value="error">
                                                    <span>{$lang.error}</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="title">
                                            <h6>{$lang.status}</h6>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <?php endif; ?>
                        <fieldset class="fields-group">
                            <div class="text">
                                <textarea name="comment" <?php echo ((($data['render'] == 'create' AND Permissions::user(['create_inventories_audits']) == true) OR ($data['render'] == 'update' AND Permissions::user(['update_inventories_audits']) == true)) ? '' : 'disabled'); ?>></textarea>
                            </div>
                            <?php if (($data['render'] == 'create' AND Permissions::user(['create_inventories_audits']) == true) OR ($data['render'] == 'update' AND Permissions::user(['update_inventories_audits']) == true)) : ?>
                                <div class="title">
                                    <h6>{$lang.comment}</h6>
                                </div>
                            <?php endif; ?>
                        </fieldset>
                        <fieldset class="fields-group">
                            <div class="button">
                                <?php if (($data['render'] == 'create' AND Permissions::user(['create_inventories_audits']) == true) OR ($data['render'] == 'update' AND Permissions::user(['update_inventories_audits']) == true)) : ?>
                                    <a class="alert" button-close><i class="fas fa-times"></i></a>
                                    <button type="submit" class="success"><i class="fas fa-check"></i></button>
                                <?php else : ?>
                                    <a class="success" button-close><i class="fas fa-check"></i></a>
                                <?php endif; ?>
                            </div>
                        </fieldset>
                    </form>
                </main>
            </div>
        </section>
    <?php elseif ($data['render'] == 'list' AND Permissions::user(['delete_inventories_audits']) == true) : ?>
        <section class="modal alert" data-modal="delete_inventory_audit">
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
