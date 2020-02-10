<?php

defined('_EXEC') or die;

$this->dependencies->getDependencies([
    'css' => [

    ],
    'js' => [
        '{$path.js}pages/search.js'
    ],
    'other' => [

    ]
]);
?>

%{header}%
<main class="body <?php if (Session::getValue('level') == 7) : echo 'droped'; endif; ?>">
    <div class="content padding">
        <div class="search">
            <select name="search" class="chosen-select">
                <option value="">Buscar producto</option>
                {$lstProducts}
            </select>
        </div>
        <div class="clear"></div>
        <div id="filter"></div>
    </div>
</main>
