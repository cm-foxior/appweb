<?php

defined('_EXEC') or die;

class Route_vkye
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function on_change_start()
    {
        global $_vkye_path;
        $_vkye_path = $this->path;

        global $_vkye_module;
        $_vkye_module = strtolower(explode('/', $_vkye_path)[1]);

        $paths = [
            '/Index/index',
            '/Login/index'
        ];

        if (Session::exists_var('session') == true AND in_array($this->path, $paths))
            header('Location: ' . Permissions::redirection());
        else if (Session::exists_var('session') == true AND Permissions::urls($this->path, 'account') == false)
            header('Location: ' . Permissions::redirection());
        else if (Session::exists_var('session') == true AND Permissions::urls($this->path, 'user') == false)
            header('Location: ' . Permissions::redirection());
        else if (Session::exists_var('session') == false AND !in_array($this->path, $paths))
            header('Location: /login');
    }

    public function on_change_end()
    {

    }
}
