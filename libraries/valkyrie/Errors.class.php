<?php
defined('_EXEC') or die;

class Errors
{
    public function __construct()
	{
	}

    static function system($error = false, $message = '')
    {
        switch($error)
        {
            case 'not_file_configuration':
                exit('{not_file_configuration}');
                break;

            case 'model_does_exists':
                echo Configuration::$debug === true ? '{model_does_exists}' . $message : '';
                break;

            case 'language_default_not_found':
                echo Configuration::$debug === true ? '{language_default_not_found}' . $message : '';
                break;

            case 'component_not_valid':
                echo Configuration::$debug === true ? '{component_not_valid}' . $message : '';
                break;

            case 'modules_not_valid':
                echo Configuration::$debug === true ? '{modules_not_valid}' . $message : '';
                break;

            default:
                echo Configuration::$debug === true ? '{system_error}' : '';
                break;
        }
    }

    static function http($error = false, $message = '')
    {
        switch($error)
        {
            case '404':
                header("HTTP/1.0 404 Not Found");
                echo Configuration::$debug === true ? $message : self::render('Errors','404');
                break;

            default:
                exit('Unknown http status code "' . htmlentities($error) . '"');
                break;
        }
    }

    private static function render($controller, $file)
    {
        $view = new View();
        return $view->render($controller, $file, false, false);
    }
}
