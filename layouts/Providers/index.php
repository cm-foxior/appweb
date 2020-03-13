<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Providers/index.min.js']);

?>

%{header}%
<header class="modbar">
    <a href="/providers"><i class="fas fa-truck"></i><span>{$lang.providers}</span></a>
    <span></span>
    <?php if (Permissions::user(['create_providers']) == true) : ?>
    <a data-action="create_provider" class="success"><i class="fas fa-plus"></i><span>{$lang.create}</span></a>
    <?php endif; ?>
    <fieldset>
        <span><i class="fas fa-search"></i></span>
        <input type="text" data-search="providers">
    </fieldset>
</header>
<main>
    <div class="tbl-st-3" data-table="providers">
        <?php foreach ($data['providers'] as $value) : ?>
        <div>
            <figure>
                <?php if (!empty($value['avatar'])) : ?>
                <img src="{$path.uploads}<?php echo $value['avatar']; ?>">
                <?php else : ?>
                <img src="{$path.images}provider.png">
                <?php endif; ?>
            </figure>
            <h4><?php echo $value['name']; ?></h4>
            <?php if (!empty($value['fiscal']['id'])) : ?>
            <span><?php echo $value['fiscal']['id']; ?></span>
            <?php else : ?>
            <span>{$lang.not_fiscal_id}</span>
            <?php endif; ?>
            <div class="button">
                <?php if (Permissions::user(['block_providers','unblock_providers']) == true) : ?>
                <?php if ($value['blocked'] == true) : ?>
                <a data-action="unblock_provider" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock-open"></i><span>{$lang.unblock}</span></a>
                <?php elseif ($value['blocked'] == false) : ?>
                <a data-action="block_provider" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock"></i><span>{$lang.block}</span></a>
                <?php endif; ?>
                <?php endif; ?>
                <?php if (Permissions::user(['delete_providers']) == true) : ?>
                <?php if ($value['blocked'] == false) : ?>
                <a data-action="delete_provider" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
                <?php endif; ?>
                <?php endif; ?>
                <?php if (Permissions::user(['update_providers']) == true) : ?>
                <?php if ($value['blocked'] == false) : ?>
                <a data-action="update_provider" data-id="<?php echo $value['id']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
                <a data-action="update_provider" data-id="<?php echo $value['id']; ?>"><i class="fas fa-info-circle"></i><span>{$lang.details}</span></a>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</main>
<?php if (Permissions::user(['create_providers','update_providers']) == true) : ?>
<section class="modal" data-modal="create_provider">
    <div class="content">
        <main>
            <form>
                <fieldset class="fields-group">
                    <div class="uploader" data-low-uploader>
                        <figure data-preview>
                            <img src="{$path.images}provider.png">
                            <a data-select><i class="fas fa-pen"></i></a>
                        </figure>
                        <input type="file" name="avatar" accept="image/*" data-select>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="text">
                        <input type="text" name="name" placeholder="{$lang.name}">
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span6">
                            <div class="text">
                                <input type="text" name="email" placeholder="{$lang.email}">
                            </div>
                        </div>
                        <div class="span6">
                            <div class="compound select">
                                <input type="text" name="phone_number" placeholder="{$lang.phone}">
                                <select name="phone_country">
                                    <option value="" selected hidden>{$lang.country}</option>
                                    <?php foreach (System::countries() as $value) : ?>
                                    <option value="<?php echo $value['lada']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="compound select">
                        <input type="text" name="address" placeholder="{$lang.address}">
                        <select name="country">
                            <option value="" selected hidden>{$lang.country}</option>
                            <?php foreach (System::countries() as $value) : ?>
                            <option value="<?php echo $value['lada']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="fiscal_id" placeholder="{$lang.fiscal_id}">
                            </div>
                        </div>
                        <div class="span8">
                            <div class="text">
                                <input type="text" name="fiscal_name" placeholder="{$lang.fiscal_name}">
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="compound select">
                        <input type="text" name="fiscal_address" placeholder="{$lang.fiscal_address}">
                        <select name="fiscal_country">
                            <option value="" selected hidden>{$lang.country}</option>
                            <?php foreach (System::countries() as $value) : ?>
                            <option value="<?php echo $value['code']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')]; ?></option>
                            <?php endforeach; ?>
                        </select>
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
<?php if (Permissions::user(['delete_providers']) == true) : ?>
<section class="modal alert" data-modal="delete_provider">
    <div class="content">
        <main>
            <i class="fas fa-trash"></i>
            <div class="button">
                <a button-success><i class="fas fa-check"></i></a>
                <a button-close><i class="fas fa-times"></i></a>
            </div>
        </main>
    </div>
</section>
<?php endif; ?>
