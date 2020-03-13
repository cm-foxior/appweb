<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Dashboard/index.min.js']);

?>

%{header}%
<main class="dashboard">
    <?php if (!empty(Session::get_value('vkye_account'))) : ?>
    <?php if (Session::get_value('vkye_account')['status'] == true) : ?>
        
    <?php else : ?>
    <div class="empty">
        <i class="far fa-sad-tear"></i>
        <p>{$lang.sorry_account_suspended}</p>
    </div>
    <?php endif; ?>
    <?php else : ?>
    <div class="empty">
        <i class="far fa-sad-tear"></i>
        <p>{$lang.sorry_not_been_invited_account}</p>
    </div>
    <?php endif; ?>
</main>
