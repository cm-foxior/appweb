<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Dashboard/index.min.js']);

?>

%{header}%
<?php if (!empty(Session::get_value('vkye_account'))) : ?>
    <?php if (Session::get_value('vkye_account')['status'] == true) : ?>
    <main class="dashboard">

    </main>
    <?php else : ?>
    <main class="empty">
        <i class="far fa-sad-tear"></i>
        <p>{$lang.account_suspended}</p>
    </main>
    <?php endif; ?>
<?php else : ?>
<main class="empty">
    <i class="far fa-sad-tear"></i>
    <p>{$lang.not_accounts}</p>
</main>
<?php endif; ?>
