<?php
defined('_EXEC') or die;

class Framework
{
	// Product name.
	const PRODUCT = 'Valkyrie Platform';
	// Release version.
	const RELEASE = '1.1.2';
	// Maintenance version.
	const MAINTENANCE = '1';
	// Development STATUS.
	const STATUS = 'Development';
	// Build number.
	const BUILD = 1;
	// Version name.
	const VERSION_NAME = 'Gladheim';
	// Release date.
	const RELEASE_DATE = '18 May 16';
	// Release time.
	const RELEASE_TIME = '12:15';
	// Release timezone.
	const RELEASE_TIME_ZONE = 'GMT';
	// Copyright Notice.
	const COPYRIGHT = 'Copyright (C) CodeMonkey - Platform. All Rights Reserved.';

	public function __construct()
	{
		$this->fileConfiguration();
		$this->errorReporting(Configuration::$error_reporting);
	}

	private function fileConfiguration()
	{
		if(!file_exists(PATH_CONFIGURATION) || (filesize(PATH_CONFIGURATION) < 10))
			Errors::system('not_file_configuration');
		else
			require_once PATH_CONFIGURATION;
	}

	private function errorReporting($str)
	{
		$case = array();
		switch ($str)
		{
			case 'default':
			case '-1':
				$case['error'] = '';
				$case['ini'] = '0';
				break;

			case 'none':
			case '0':
				$case['error'] = '0';
				$case['ini'] = '0';
				break;

			case 'simple':
				$case['error'] = 'E_ERROR | E_WARNING | E_PARSE';
				$case['ini'] = '0';
				break;

			case 'maximum':
				$case['error'] = 'E_ALL';
				$case['ini'] = '1';
				break;

			case 'development':
				$case['error'] = '-1';
				$case['ini'] = '1';
				break;

			default:
				$case['error'] = Config::$general['error_reporting'];
				$case['ini'] = '1';
				break;
		}

		return error_reporting($case['error']) . ini_set('display_errors', $case['ini']);
	}

	public static function getShortVersion()
	{
		return self::RELEASE . '.' . self::MAINTENANCE;
	}

	public static function getLongVersion()
	{
		return self::PRODUCT . ' ' . self::RELEASE . '.' . self::MAINTENANCE . ' ' . self::STATUS . ' [ ' . self::VERSION_NAME . ' ] '
			 . self::RELEASE_DATE . ' ' . self::RELEASE_TIME . ' ' . self::RELEASE_TIME_ZONE;
	}

}
