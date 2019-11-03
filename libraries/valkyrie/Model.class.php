<?php
defined('_EXEC') or die;

class Model
{
	public $database;
	public $security;
	public $format;
	public $module;
	public $component;

	public function __construct()
	{
		$this->database  = new Medoo;
		$this->security  = new Security;
		$this->format  	 = new Format;
		$this->module 	 = new Modules;
		$this->component = new Components;
	}

}
