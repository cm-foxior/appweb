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
            <span class="online"><i class="fas fa-circle"></i><?php echo Session::get_value('vkye_account')['name']; ?></span>
            <?php if (Session::get_value('vkye_account')['type'] == 'business') : ?>
            <span>{$lang.business}</span>
            <?php elseif (Session::get_value('vkye_account')['type'] == 'personal') : ?>
            <span>{$lang.personal}</span>
            <?php endif; ?>
            <?php else : ?>
            <span class="offline"><i class="fas fa-circle"></i><?php echo Session::get_value('vkye_account')['name']; ?></span>
            <span>{$lang.suspended}</span>
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
            <span><?php echo Session::get_value('vkye_user')['firstname'] . ' ' . Session::get_value('vkye_user')['lastname']; ?><i class="fas fa-circle"></i></span>
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
            <li><a><i class="fas fa-star"></i><span>{$lang.last_updates}</span></a></li>
            <li><a><i class="fas fa-bell"></i><span>{$lang.last_notifications}</span></a></li>
            <li><a><i class="fas fa-user-circle"></i><span>{$lang.my_profile}</span></a></li>
        </ul>
    </nav>
</header>
<header class="leftbar">
    <nav>
        <ul>
            <li><a><i class="fas fa-user-astronaut"></i></a></li>
            <li><a href="/dashboard"><i class="fas fa-igloo"></i><span>{$lang.dashboard}</span></a></li>
        </ul>
    </nav>
    <?php if (!empty(Session::get_value('vkye_account'))) : ?>
    <?php if (Permissions::account(['inventories','sales']) == true) : ?>
    <nav>
        <ul>
            <li><a><i class="fas fa-search"></i><span>{$lang.search}</span></a></li>
        </ul>
    </nav>
    <?php endif; ?>
    <?php if (Permissions::account(['accounting']) == true) : ?>
    <nav>
        <ul>
            <li><a><i class="fas fa-calculator"></i><span>{$lang.accounting}</span></a></li>
        </ul>
    </nav>
    <?php endif; ?>
    <?php if (Permissions::account(['billing']) == true) : ?>
    <nav>
        <ul>
            <li><a><i class="fas fa-file-invoice"></i><span>{$lang.electronic_billing}</span></a></li>
        </ul>
    </nav>
    <?php endif; ?>
    <?php if (Permissions::account(['inventories']) == true) : ?>
    <nav>
        <ul>
            <li><a><i class="fas fa-boxes"></i><span>{$lang.inventories}</span></a></li>
        </ul>
    </nav>
    <?php endif; ?>
    <?php if (Permissions::account(['sales']) == true) : ?>
    <nav>
        <ul>
            <li><a><i class="fas fa-shopping-cart"></i><span>{$lang.selling_point}</span></a></li>
        </ul>
    </nav>
    <?php endif; ?>
    <?php if (Permissions::account(['ecommerce']) == true) : ?>
    <nav>
        <ul>
            <li><a><i class="fas fa-laptop"></i><span>{$lang.online_shop}</span></a></li>
        </ul>
    </nav>
    <?php endif; ?>
    <?php endif; ?>
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
                    <span><?php echo $value['name']; ?></span>
                    <?php if ($value['status'] == true) : ?>
                    <?php if (Session::get_value('vkye_account')['id'] == $value['id']) : ?>
                    <span class="online"><i class="fas fa-circle"></i>{$lang.online}</span>
                    <?php else : ?>
                    <span class="offline"><i class="fas fa-circle"></i>{$lang.offline}</span>
                    <?php endif; ?>
                    <?php else : ?>
                    <span class="suspended"><i class="fas fa-circle"></i>{$lang.suspended}</span>
                    <?php endif; ?>
                </div>
                <a data-action="switch_account" data-id="<?php echo $value['id']; ?>"></a>
            </div>
            <?php endforeach; ?>
            <?php else : ?>
            <div class="empty">
                <i class="far fa-sad-tear"></i>
                <p>{$lang.sorry_not_been_invited_account}</p>
            </div>
            <?php endif; ?>
        </div>
        <?php if (!empty(Session::get_value('vkye_account'))) : ?>
        <?php if (Permissions::account(['inventories']) == true AND Permissions::user(['products','products_categories','products_barcodes','providers'], true) == true) : ?>
        <nav>
            <span>{$lang.catalogs}</span>
            <ul>
                <?php if (Permissions::user(['products'], true) == true) : ?>
                <li><a href="/products/menu"><i class="fas fa-box-open"></i>{$lang.products}</a></li>
                <?php endif; ?>
                <?php if (Permissions::user(['products_categories'], true) == true) : ?>
                <li><a href="/products/categories" class="indented"><i class="fas fa-tags"></i>{$lang.categories}</a></li>
                <?php endif; ?>
                <?php if (Permissions::user(['products_unities'], true) == true) : ?>
                <li><a href="/products/unities" class="indented"><i class="fas fa-balance-scale-left"></i>{$lang.unities}</a></li>
                <?php endif; ?>
                <?php if (Permissions::user(['products_barcodes'], true) == true) : ?>
                <li><a href="/products/barcodes" class="indented"><i class="fas fa-barcode"></i>{$lang.barcodes}</a></li>
                <?php endif; ?>
                <?php if (Permissions::user(['providers'], true) == true) : ?>
                <li><a href="/providers"><i class="fas fa-people-carry"></i>{$lang.providers}</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <?php if (Permissions::account(['inventories']) == true AND Permissions::user(['branches'], true) == true) : ?>
        <nav>
            <span>{$lang.administration}</span>
            <ul>
                <?php if (Permissions::user(['branches'], true) == true) : ?>
                <li><a href="/branches"><i class="fas fa-store"></i>{$lang.branches}</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <?php if (Permissions::user(['account'], true) == true) : ?>
        <nav>
            <span>{$lang.online_account}</span>
            <ul>
                <?php if (Permissions::user(['update_account'])) : ?>
                <li><a><i class="fas fa-user-circle"></i>{$lang.profile}</a></li>
                <li><a><i class="fas fa-cog"></i>{$lang.settings}</a></li>
                <?php endif; ?>
                <?php if (Permissions::user(['payment_account'])) : ?>
                <li><a><i class="fas fa-parachute-box"></i>{$lang.payment_center}</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <?php endif; ?>
        <nav>
            <span>{$lang.my_user}</span>
            <ul>
                <li><a><i class="fas fa-user-circle"></i>{$lang.my_profile}</a></li>
                <li><a><i class="fas fa-bell"></i>{$lang.my_notifications}</a></li>
                <li><a><i class="fas fa-award"></i>{$lang.my_accounts}</a></li>
            </ul>
        </nav>
        <nav>
            <ul>
                <li><a>{$lang.technical_support}</a></li>
                <li><a>{$lang.foxior_updates}</a></li>
                <li><a>{$lang.about}</a></li>
                <li><a>{$lang.contact}</a></li>
                <li><a>{$lang.copyright}</a></li>
                <li><a>{$lang.terms_and_conditions}</a></li>
                <li><a data-action="logout">{$lang.logout}</a></li>
            </ul>
        </nav>
    </div>
</header>
