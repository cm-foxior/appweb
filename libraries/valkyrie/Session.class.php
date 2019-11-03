<?php
defined('_EXEC') or die;

class Session
{
	public function __construct()
	{
	}

	static function init( $array = false )
	{
		@session_start($array);
	}

	static function destroy()
	{
		@session_destroy();
	}

	static function getValue($str)
	{
		return $_SESSION[$str];
	}

	static function setValue($str, $value)
	{
		$_SESSION[$str] = $value;
	}

	static function unsetValue($str)
	{
		if (isset($_SESSION[$str]))
			unset($_SESSION[$str]);
	}

	static function existsVar($str)
	{
		if (isset($_SESSION[$str]))
			return true;
		else
			return false;
	}

	static function exists()
	{
		if (sizeof($_SESSION) > 0)
			return true;
		else
			return false;
	}

}
