<?php

defined('_EXEC') or die;

class Configuration
{
	/**
	*
	* @static string 		$domain 			"localhost"
	* @static string 		$webPage			"Valkyrie"
	* @static string(2)		$langDefault		"es|en|fr|ru.."
	* @static int|string	$error_reporting	default|-1, none|0, simple, maximum, development
	* @static boolean 		$debug 				true|false
	* @static boolean 		$debugLang 			true|false
	* @static boolean 		$urlFriendly 		true|false
	* @static boolean 		$compressHtml 		true|false
	* @static string 		$timeZone 			GMT ZONE
	* @static string 		$secret 			key secure
	* @static string 		$helpurl 			Help from master
	*/

	public static $domain 			= 'dev.sofierp.com';
	public static $webPage 			= 'Sofi ERP';
	public static $langDefault 		= 'es';
	public static $error_reporting 	= 'development';
	public static $debug 			= false;
	public static $debugLang 		= false;
	public static $urlFriendly 		= true;
	public static $compressHtml 	= false;
	public static $timeZone 		= 'America/Mexico_City';
	public static $secret 			= '6aGh=a>O:NnDmAvR';
	public static $helpurl 			= 'https://help.codemonkey.com.mx/index.php';

	/**
	*
	* @tutorial http://medoo.in/doc
	* @static string 	$db_state 		state use database
	* @static string 	$db_type 		type database MySQL, MariaDB, MSSQL, PostgreSQL, Oracle, Sybase
	* @static string 	$db_host 		server host database
	* @static string 	$db_name 		name of your database
	* @static string 	$db_user 		username use of your database
	* @static string 	$db_pass 		password use of your database
	* @static string 	$db_charset 	OPTIONAL charset of database utf8...
	* @static string 	$db_prefix 		prefix use of tables
	* @static int 		$db_port 		port stablish on connect
	* @static string 	$db_file 		Only for SQLite 'my/database/path/database.db'
	* @static array 	$db_option 		OPTIONAL, for connection, read more from
	* 									http://www.php.net/manual/en/pdo.setattribute.php
	*/

	public static $db_state			= true;
	public static $db_type 			= 'mysql';
	public static $db_host 			= 'sofierp.com';
	public static $db_name 			= 'sf-web-platform-dev';
	public static $db_user 			= 'sofierp';
	public static $db_pass 			= 'npSr&231';
	public static $db_charset		= 'utf8';
	public static $db_prefix 		= '';
	public static $db_port 			= 3306;
	public static $db_file 			= '';
	public static $db_option 		= [];

	/**
	*
	* @static bolean 	$smtp_auth 		true|false
	* @static string 	$smtp_host 		host of your smtp
	* @static string 	$smtp_user 		user use of your smtp
	* @static string 	$smtp_pass 		password use of your smtp
	* @static string 	$smtp_secure 	tls|ssl
	* @static int 		$smtp_port 		use port of your smtp
	*/

	public static $smtp_auth 		= true;
	public static $smtp_host 		= 'mail.codemonkey.com.mx';
	public static $smtp_user 		= 'noreply@codemonkey.com.mx';
	public static $smtp_pass 		= '&27lS6mv';
	public static $smtp_secure 		= 'ssl';
	public static $smtp_port 		= 465;
}
