<?php
defined('_EXEC') or die;

class Controller
{
    public $view;
    public $security;
    public $format;
    public $system;
    public $model;

    public function __construct()
	{
        $this->view     = new View();
        $this->security = new Security();
        $this->format   = new Format();

        if($this->format->adminPath() === true)
            $this->system = new System();

        $this->loadModel();
	}

    public function loadModel($class = false, $type = false, $load = false)
	{
        $class = $class === false ? $this : $class;

		$model = str_replace(CONTROLLER_PHP, '', get_class($class)) . MODEL_PHP;

        switch ($type)
        {
            case 'component':
                $path = $this->format->checkAdmin(PATH_ADMINISTRATOR_COMPONENTS, PATH_COMPONENTS) . 'com_' . $load . '/models/' . $model . '.php';
                break;

            case 'module':
                $path = $this->format->checkAdmin(PATH_ADMINISTRATOR_MODULES, PATH_MODULES) . 'mod_' . $load . '/models/' . $model . '.php';
                break;

            default:
                $path = $this->format->checkAdmin(PATH_ADMINISTRATOR_MODELS, PATH_MODELS) . $model . '.php';
                break;
        }

        $path = $this->security->directorySeparator($path);

		if (file_exists($path))
		{
			require_once $path;
			$this->model = new $model();
		}
        else
        {
            if(Configuration::$debug === true && $type === 'component' || $type === 'module')
                Errors::system('model_does_exists', $path);
        }
	}
}
