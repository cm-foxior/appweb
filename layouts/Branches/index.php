<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Branches/index.js?v=1.0']);

?>

%{header}%
<header class="modbar">
    <div class="buttons">
        <fieldset class="fields-group big">
            <div class="compound st-4-left">
                <span><i class="fas fa-search"></i></span>
                <input type="text" data-search="branches" placeholder="{$lang.search}">
            </div>
        </fieldset>
        <?php if (Permissions::user(['create_branches']) == true) : ?>
            <a data-action="create_branch" class="success"><i class="fas fa-plus"></i><span>{$lang.create}</span></a>
        <?php endif; ?>
    </div>
</header>
<main class="workspace">
    <div class="tbl-st-2" data-table="branches">
        <?php foreach ($data['branches'] as $value) : ?>
            <div>
                <figure>
                    <?php if (!empty($value['avatar'])) : ?>
                        <img src="{$path.uploads}<?php echo $value['avatar']; ?>">
                    <?php else : ?>
                        <img src="{$path.images}branch.png">
                    <?php endif; ?>
                </figure>
                <h4><?php echo $value['name']; ?></h4>
                <?php if (!empty($value['fiscal']['id'])) : ?>
                    <span><?php echo $value['fiscal']['id']; ?></span>
                <?php else : ?>
                    <span>{$lang.not_fiscal_id}</span>
                <?php endif; ?>
                <div class="button">
                    <?php if (Permissions::user(['block_branches','unblock_branches']) == true) : ?>
                        <?php if ($value['blocked'] == true) : ?>
                            <a data-action="unblock_branch" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock"></i><span>{$lang.unblock}</span></a>
                        <?php else : ?>
                            <a data-action="block_branch" data-id="<?php echo $value['id']; ?>"><i class="fas fa-unlock"></i><span>{$lang.block}</span></a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (Permissions::user(['delete_branches']) == true) : ?>
                        <?php if ($value['blocked'] == false) : ?>
                            <a data-action="delete_branch" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (Permissions::user(['update_branches']) == true) : ?>
                        <?php if ($value['blocked'] == false) : ?>
                            <a data-action="update_branch" data-id="<?php echo $value['id']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
<?php if (Permissions::user(['create_branches','update_branches']) == true) : ?>
    <section class="modal" data-modal="create_branch">
        <div class="content">
            <main>
                <form>
                    <fieldset class="fields-group">
                        <div class="uploader" data-low-uploader>
                            <figure data-preview>
                                <img src="{$path.images}branch.png">
                                <a data-select><i class="fas fa-pen"></i></a>
                            </figure>
                            <input type="file" name="avatar" accept="image/*" data-select>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="text">
                            <input type="text" name="name" placeholder="{$lang.type}">
                        </div>
                        <div class="title">
                            <h6>{$lang.name}</h6>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span6">
                                <div class="text">
                                    <input type="text" name="email" placeholder="{$lang.type}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.email}</h6>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="compound st-1-left">
                                    <select name="phone_country">
                                        <option value="">{$lang.select} ({$lang.empty})</option>
                                        <?php foreach (Functions::countries() as $value) : ?>
                                            <option value="<?php echo $value['lada']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')]; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="phone_number" placeholder="{$lang.type}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.phone}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span2">
                                <div class="text">
                                    <select name="fiscal_country">
                                        <option value="">{$lang.select} ({$lang.empty})</option>
                                        <?php foreach (Functions::countries() as $value) : ?>
                                            <option value="<?php echo $value['code']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')]; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.country}</h6>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="text">
                                    <select name="fiscal_state" disabled>
                                        <option value="">{$lang.select} ({$lang.empty})</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.state}</h6>
                                </div>
                            </div>
                            <div class="span8">
                                <div class="text">
                                    <input type="text" name="fiscal_address" placeholder="{$lang.type}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.address}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span8">
                                <div class="text">
                                    <input type="text" name="fiscal_name" placeholder="{$lang.type}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.fiscal_name}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <input type="text" name="fiscal_id" placeholder="{$lang.type}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.fiscal_id}</h6>
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
<?php if (Permissions::user(['delete_branches']) == true) : ?>
    <section class="modal alert" data-modal="delete_branch">
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
