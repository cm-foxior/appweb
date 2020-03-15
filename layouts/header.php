<?php defined('_EXEC') or die; ?>

<header class="topbar">
    <?php if (!empty(Session::get_value('vkye_account'))) : ?>
    <div class="account">
        <figure>
            <?php if (!empty(Session::get_value('vkye_account')['avatar'])) : ?>
            <img src="{$path.uploads}<?php echo Session::get_value('vkye_account')['avatar']; ?>">
            <?php else : ?>
            <img src="{$path.images}account.png">
            <?php endif; ?>
        </figure>
        <div>
            <?php if (Session::get_value('vkye_account')['status'] == true) : ?>
            <h4 class="online">
            <?php else : ?>
            <h4 class="offline">
            <?php endif; ?>
                <i class="fas fa-circle"></i><?php echo Session::get_value('vkye_account')['name']; ?>
            </h4>
            <?php if (Session::get_value('vkye_account')['type'] == 'business') : ?>
            <span>{$lang.business}</span>
            <?php elseif (Session::get_value('vkye_account')['type'] == 'personal') : ?>
            <span>{$lang.personal}</span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    <nav>
        <ul>
            <li><a data-action="open_rightbar"><i class="fas fa-ellipsis-v"></i></a></li>
        </ul>
    </nav>
    <div class="user">
        <div>
            <h4><?php echo Session::get_value('vkye_user')['firstname'] . ' ' . Session::get_value('vkye_user')['lastname']; ?><i class="fas fa-circle"></i></h4>
            <span><?php echo Session::get_value('vkye_user')['email']; ?></span>
        </div>
        <figure>
            <?php if (!empty(Session::get_value('vkye_user')['avatar'])) : ?>
            <img src="{$path.uploads}<?php echo Session::get_value('vkye_user')['avatar']; ?>">
            <?php else : ?>
            <img src="{$path.images}user.png">
            <?php endif; ?>
        </figure>
    </div>
    <nav>
        <ul>
            <li><a><i class="fas fa-rocket"></i></a></li>
            <li><a><i class="fas fa-meteor"></i></a></li>
            <li><a><i class="fas fa-user-astronaut"></i></a></li>
            <li><a><i class="fas fa-bell"></i></a></li>
        </ul>
    </nav>
</header>
<header class="leftbar">
    <nav>
        <ul>
            <li><a href="/dashboard"><i class="fas fa-home"></i><span>{$lang.dashboard}</span></a></li>
        </ul>
        <?php if (!empty(Session::get_value('vkye_account'))) : ?>
            <?php if (Permissions::account(['inventories','sales']) == true) : ?>
            <ul>
                <li><a><i class="fas fa-search"></i><span>{$lang.search}</span></a></li>
            </ul>
            <?php endif; ?>
            <?php if (Permissions::account(['accounting']) == true) : ?>
            <ul>
                <li><a><i class="fas fa-calculator"></i><span>{$lang.accounting}</span></a></li>
            </ul>
            <?php endif; ?>
            <?php if (Permissions::account(['billing']) == true) : ?>
            <ul>
                <li><a><i class="fas fa-file-invoice"></i><span>{$lang.electronic_billing}</span></a></li>
            </ul>
            <?php endif; ?>
            <?php if (Permissions::account(['inventories']) == true) : ?>
            <ul>
                <li><a href="/inventories"><i class="fas fa-box-open"></i><span>{$lang.inventories}</span></a></li>
            </ul>
            <?php endif; ?>
            <?php if (Permissions::account(['sales']) == true) : ?>
            <ul>
                <li><a><i class="fas fa-cash-register"></i><span>{$lang.selling_point}</span></a></li>
            </ul>
            <?php endif; ?>
            <?php if (Permissions::account(['ecommerce']) == true) : ?>
            <ul>
                <li><a><i class="fas fa-laptop"></i><span>{$lang.online_shop}</span></a></li>
            </ul>
            <?php endif; ?>
        <?php endif; ?>
    </nav>
</header>
<header class="rightbar">
    <div>
        <div class="accounts">
            <?php if (!empty(Session::get_value('vkye_user')['accounts'])) : ?>
                <?php foreach (Session::get_value('vkye_user')['accounts'] as $key => $value) : ?>
                <div class="item">
                    <figure>
                        <?php if (!empty($value['avatar'])) : ?>
                        <img src="{$path.uploads}<?php echo $value['avatar']; ?>">
                        <?php else : ?>
                        <img src="{$path.images}account.png">
                        <?php endif; ?>
                    </figure>
                    <div>
                        <h4><?php echo $value['name']; ?></h4>
                        <?php if ($value['status'] == true) : ?>
                            <?php if (Session::get_value('vkye_account')['id'] == $value['id']) : ?>
                            <span class="online"><i class="fas fa-circle"></i>{$lang.online}</span>
                            <?php else : ?>
                            <span class="onhold"><i class="fas fa-circle"></i>{$lang.onhold}</span>
                            <?php endif; ?>
                        <?php else : ?>
                        <span class="offline"><i class="fas fa-circle"></i>{$lang.offline}</span>
                        <?php endif; ?>
                    </div>
                    <a data-action="switch_account" data-id="<?php echo $value['id']; ?>"></a>
                </div>
                <?php endforeach; ?>
            <?php else : ?>
            <div class="empty">
                <i class="far fa-sad-tear"></i>
                <p>{$lang.not_accounts}</p>
            </div>
            <?php endif; ?>
        </div>
        <nav>
            <?php if (!empty(Session::get_value('vkye_account'))) : ?>
                <?php if (Permissions::account(['inventories']) == true AND Permissions::user(['products','products_categories','products_unities','products_barcodes'], true) == true) : ?>
                <ul>
                    <li><h4>{$lang.products}</h4></li>
                    <?php if (Permissions::user(['products'], true) == true) : ?>
                    <li><a href="/products/salemenu"><i class="fas fa-dollar-sign"></i>{$lang.sale_menu}</a></li>
                    <li><a href="/products/supplies"><i class="fas fa-layer-group"></i>{$lang.supplies}</a></li>
                    <li><a href="/products/recipes"><i class="fas fa-receipt"></i>{$lang.recipes}</a></li>
                    <li><a href="/products/workmaterials"><i class="fas fa-mail-bulk"></i>{$lang.work_materials}</a></li>
                    <?php endif; ?>
                    <?php if (Permissions::user(['products_categories'], true) == true) : ?>
                    <li><a href="/products/categories"><i class="fas fa-tag"></i>{$lang.categories}</a></li>
                    <?php endif; ?>
                    <?php if (Permissions::user(['products_unities'], true) == true) : ?>
                    <li><a href="/products/unities"><i class="fas fa-balance-scale-left"></i>{$lang.unities}</a></li>
                    <?php endif; ?>
                    <?php if (Permissions::user(['products_barcodes'], true) == true) : ?>
                    <li><a href="/products/barcodes"><i class="fas fa-barcode"></i>{$lang.barcodes}</a></li>
                    <?php endif; ?>
                </ul>
                <?php endif; ?>
                <?php if (Permissions::account(['inventories']) == true AND Permissions::user(['providers','branches'], true) == true) : ?>
                <ul>
                    <li><h4>{$lang.administration}</h4></li>
                    <?php if (Permissions::user(['providers'], true) == true) : ?>
                    <li><a href="/providers"><i class="fas fa-truck"></i>{$lang.providers}</a></li>
                    <?php endif; ?>
                    <?php if (Permissions::user(['branches'], true) == true) : ?>
                    <li><a href="/branches"><i class="fas fa-store"></i>{$lang.branches}</a></li>
                    <?php endif; ?>
                </ul>
                <?php endif; ?>
                <?php if (Permissions::user(['account'], true) == true) : ?>
                <ul>
                    <li><h4>{$lang.online_account}</h4></li>
                    <?php if (Permissions::user(['update_account'])) : ?>
                    <li><a><i class="fas fa-user-circle"></i>{$lang.profile}</a></li>
                    <li><a><i class="fas fa-cog"></i>{$lang.settings}</a></li>
                    <?php endif; ?>
                    <?php if (Permissions::user(['payment_account'])) : ?>
                    <li><a><i class="fas fa-parachute-box"></i>{$lang.payment_center}</a></li>
                    <?php endif; ?>
                </ul>
                <?php endif; ?>
            <?php endif; ?>
            <ul>
                <li><h4>{$lang.my_user}</h4></li>
                <li><a><i class="fas fa-user-circle"></i>{$lang.my_profile}</a></li>
                <li><a><i class="fas fa-bell"></i>{$lang.my_notifications}</a></li>
                <li><a><i class="fas fa-award"></i>{$lang.my_accounts}</a></li>
            </ul>
            <ul>
                <li><a>{$lang.technical_support}</a></li>
                <li><a>{$lang.foxior_updates}</a></li>
                <li><a>{$lang.about}</a></li>
                <li><a>{$lang.contact}</a></li>
                <li><a>{$lang.copyright}</a></li>
                <li><a>{$lang.terms_and_conditions}</a></li>
                <li><a data-action="logout">{$lang.logout}</a></li>
            </ul>
            <ul>
                <li><a>Foxior 2.0</a></li>
                <li><a>{$lang.development_by} Code Monkey</a></li>
                <li><a>Power by Valkyrie</a></li>
            </ul>
        </nav>
    </div>
</header>
