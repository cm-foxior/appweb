<?php
defined('_EXEC') or die;

class View
{
	private $dependencies;
	private $security;
	private $format;

	public function __construct()
	{
		$this->dependencies = new Dependencies();
		$this->security = new Security();
		$this->format = new Format();
	}

	public function render($controller, $layouts)
	{
		$pathLayouts = $this->security->directorySeparator($this->format->checkAdmin(PATH_ADMINISTRATOR_LAYOUTS, PATH_LAYOUTS));

		ob_start();

		if(is_array($layouts))
		{
			// Load Header
			if(isset($layouts['head']['path']) && !empty($layouts['head']['path']))
			{
				$pathHead = $this->security->directorySeparator($layouts['head']['path']);
				$file = $pathHead . $layouts['head']['file'] . '.php';

				if(file_exists($file))
					require_once $file;
				else
					require_once $pathLayouts . 'head.php';
			}
			else
				require_once $pathLayouts . 'head.php';

			// Load Main
			if(isset($layouts['main']['path']) && !empty($layouts['main']['path']))
			{
				$pathMain = $this->security->directorySeparator($layouts['main']['path']);
				$file = $pathMain . $layouts['main']['file'] . '.php';

				if(file_exists($file))
					require_once $file;
			}
			else
			{
				$controller = str_replace(CONTROLLER_PHP, '', get_class($controller));
				require_once $pathLayouts . $controller . '/' . $layouts['main']['file'] . '.php';
			}

			// Load Footer
			if(isset($layouts['footer']['path']) && !empty($layouts['footer']['path']))
			{
				$pathFooter = $this->security->directorySeparator($layouts['footer']['path']);
				$file = $pathFooter . $layouts['footer']['file'] . '.php';

				if(file_exists($file))
					require_once $file;
				else
					require_once $pathLayouts . 'footer.php';
			}
			else
				require_once $pathLayouts . 'footer.php';
		}
		else
		{
			require_once $pathLayouts . 'head.php';

			if(is_object($controller))
				$controller = str_replace(CONTROLLER_PHP, '', get_class($controller));

			require_once $pathLayouts . $controller . '/' . $layouts . '.php';
			require_once $pathLayouts . 'footer.php';
		}

        $buffer = ob_get_contents();

		// Code render
		$buffer = $this->dependencies->loadDependencies($buffer);

        ob_end_clean();

        return $buffer;
	}

}
