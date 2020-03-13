<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Login/index.min.js']);

?>

<main class="login">
    <figure>
        <img src="{$path.images}isotype_color.svg">
    </figure>
    <form name="login">
        <fieldset class="fields-group">
            <div class="text">
                <input type="email" name="email" placeholder="{$lang.email}">
            </div>
        </fieldset>
        <fieldset class="fields-group">
            <div class="text">
                <input type="password" name="password" placeholder="{$lang.password}">
            </div>
        </fieldset>
        <fieldset class="fields-group">
            <div class="button">
                <button type="submit">{$lang.login}</button>
            </div>
        </fieldset>
        <fieldset class="fields-group">
            <div class="shurtcuts">
                <a>{$lang.have_you_forgotten_your_password}<i class="fas fa-user-lock"></i></a>
                <a>{$lang.signup_now}<i class="fas fa-user-plus"></i></a>
            </div>
        </fieldset>
    </form>
    <a>{$lang.development_by}<img src="{$path.images}code_monkey_logotype_black.svg"></a>
    <a>{$lang.all_rights_reserved}</a>
    <span>{$lang.with_love}</span>
</main>
