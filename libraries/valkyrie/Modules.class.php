<?php
defined('_EXEC') or die;

class Modules
{
    private $security;
    private $format;

    public function __construct()
    {
        $this->security = new Security();
        $this->format = new Format();
    }

    public function loadModule($module = false)
    {
        $module = 'mod_' . $module;

        $route = $this->security->directorySeparator($this->format->checkAdmin(PATH_ADMINISTRATOR_MODULES, PATH_MODULES) . $module . '/handler.php');

        if(!file_exists($route))
            Errors::system('modules_not_valid', ' - ' . $module);
        else
            return $this->format->getFile( $route );
    }

}
