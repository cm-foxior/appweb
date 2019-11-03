<?php
defined('_EXEC') or die;

class Layout
{
    private $framework;
    private $security;
    private $render;
    private $language;
    private $format;
    private $controller;
    private $method;
    private $params;
    private $route;

    public function __construct()
    {
        Session::init(['cookie_lifetime' => 86400]);

        $this->framework = new Framework();
        $this->security = new Security();
        $this->render = new Render();
        $this->language = new Language();
        $this->format = new Format();
        $this->loadPage();

        Format::getTimeZone();
    }

    public function execute()
    {
        ob_start("ob_gzhandler");

		// Load template
		$this->loadController();

		if(!defined('_title'))
			define('_title', Configuration::$webPage . ' - Valkyrie Platform');

		if(!defined('_lang'))
			define('_lang', Configuration::$langDefault);

		$buffer = ob_get_contents();

		// Rendering
		$buffer = $this->render($buffer);

		ob_end_clean();
		return $buffer;
    }

    private function loadPage()
    {
        if(Configuration::$urlFriendly === true && isset($_SERVER['PATH_INFO']))
        {
            $url = str_replace('-', '_', $_SERVER['PATH_INFO']);
            $url = explode('/', $url);
            unset($url[0]);

            $this->controller = (isset($url[1]))? ucwords( $this->security->cleanUrl($url[1]) ) : 'Index';
            $this->method     = (isset($url[2]))? strtolower( $this->security->cleanUrl($url[2]) ) : 'index';

            unset($url[1]);
            unset($url[2]);

            foreach ($url as $param)
                $this->params .= $param . '/';

            $this->params = (isset($url[3]))? substr($this->params, 0, -1) : '';

            unset($url);
        }
        elseif(Configuration::$urlFriendly === false && isset($_GET['c']))
        {
            $this->controller = (isset($_GET['c']))? ucwords( $this->security->cleanUrl($_GET['c']) ) : 'Index';
            $this->method     = (isset($_GET['m']))? strtolower( $this->security->cleanUrl($_GET['m']) ) : 'index';
            $this->params     = (isset($_GET['p']))? $_GET['p'] : '';
        }

        if(empty($this->controller))
            $this->controller = 'Index';
        if(empty($this->method))
            $this->method = 'index';
    }

    private function loadController()
	{
        $path = $this->security->directorySeparator(PATH_CONTROLLERS . $this->controller . CONTROLLER_PHP . '.php');

		if(file_exists($path))
		{
			require_once $path;
            $controller = $this->controller . CONTROLLER_PHP;
			$controller = new $controller();

			if (isset($this->method))
			{
				if (method_exists($controller, $this->method))
				{
                    if (file_exists(PATH_MY_LIBRARIES . 'Route' . CLASS_PHP))
                    {
                        $this->route = new Route('/' . $this->controller . '/' . $this->method);

                        $this->route->onChangeStart();
                        $this->route->onChange();
                    }

					if (isset($this->params))
						$controller->{$this->method}($this->params);
					else
						$controller->{$this->method}();

                    if (file_exists(PATH_MY_LIBRARIES . 'Route' . CLASS_PHP))
                        $this->route->onChangeEnd();
				}
				else
                    Errors::http('404', '{method_does_exists}');
			}
			else
                $controller->index();
		}
		else
            Errors::http('404', '{controller_does_exists}');
	}

    private function render($buffer)
    {
        $buffer = Language::getLang($buffer);
        $buffer = $this->render->placeholders($buffer);
        $buffer = $this->render->paths($buffer);

        if(Configuration::$compressHtml === TRUE)
			$buffer = preg_replace(array('//Uis', "/[[:blank:]]+/"), array('', ' '), str_replace(array("\n", "\r", "\t"), '', $buffer));

		return $buffer;
    }
}
