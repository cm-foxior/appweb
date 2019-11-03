<?php
defined('_EXEC') or die;

class Language
{
    private $security;
    private $render;
    private $format;
    private static $generalPath;

    public function __construct()
	{
        $this->security = new Security();
        $this->render = new Render();
        $this->format = new Format();
        self::$generalPath = $this->format->checkAdmin(PATH_ADMINISTRATOR_LANGUAGE, PATH_LANGUAGE);
        $this->checkLang();
        $this->changeLang();
	}

    public function checkLang()
    {
        $langDefault = self::$generalPath . Configuration::$langDefault . '.ini';

        if (!file_exists($langDefault))
            Errors::system('language_default_not_found', 'File: ' . $langDefault);

		if (!isset($_SESSION['lang'])
		    || empty($_SESSION['lang'])
		    || !file_exists(self::$generalPath . $_SESSION['lang'] . '.ini'))
		{
            Session::setValue('lang', Configuration::$langDefault);
			setcookie('lang', Configuration::$langDefault, time() + (86400 * 30), "/");
		}
    }

    public static function getLang($string, $array = false, $path = false)
	{
        if($array == false)
            $array = 'General';

        $format = new Format();

        if($path != false)
            $ini = $format->fileInclude($path, $_SESSION['lang'], 'ini');
        else
            $ini = $format->fileInclude(self::$generalPath, $_SESSION['lang'], 'ini');

		foreach ($ini[$array] as $key => $value)
		{
			if (Configuration::$debugLang === FALSE)
				$string = str_replace('{$lang.' . $key . '}', $value, $string);
			else
				$string = str_replace('{$lang.' . $key . '}', '{$lang.' . $key . '}', $string);
		}

		return $string;
	}

    private function changeLang()
	{
		if (isset($_GET['lang']) && !empty($_GET['lang']))
		{
			unset($_SESSION['lang']);
            Session::setValue('lang', $_GET['lang']);
			setcookie('lang', $_GET['lang'], time() + (86400 * 30), "/");

			$ref = isset ($_GET['ref']) ? $_GET['ref'] : '';

            if($this->security->checkEncoder('base64', $ref) === true)
                $ref = base64_decode($ref);

            if(empty($ref))
            {
                $base = $_SERVER['REQUEST_URI'];
                $base = $this->render->replace(['?lang=' . $_GET['lang'] => ''], $base);
            }
            else
                $base = $ref;

			header("Location: " . $this->security->protocol() . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $base);
		}
	}

    public static function getLanUrl($lang)
	{
		return 'lang=' . $lang . '&ref=' . base64_encode($_SERVER['REQUEST_URI']);
	}
}
