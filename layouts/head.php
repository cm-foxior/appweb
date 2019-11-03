<?php defined('_EXEC') or die; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$vkye_lang}">
	<head>
		<meta charset="UTF-8" />
		<meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type" />
		<meta name="robots" content="noindex">
		<meta name="robots" content="noimageindex">

		<base href="{$vkye_base}">

		<title>{$vkye_title}</title>

		<!--Adaptive Responsive-->
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
		<meta name="author" content="Code Monkey" />
		<meta name="description" content="Sofi ERP" />

		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		<link rel="stylesheet" href="{$path.css}valkyrie-material-design.css" type="text/css" media="all" />
		<link rel="stylesheet" href="{$path.css}md-indigo-theme.css" type="text/css" media="all" />
		<link rel="stylesheet" href="{$path.css}theme.css" type="text/css" media="all" />

		<!-- Roboto font y Material design icons local -->
		<link rel="stylesheet" href="{$path.css}material-icons.css" type="text/css" media="all" />
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

		<link rel="stylesheet" href="{$path.plugins}chosen-select/chosen.css" type="text/css" media="all" />
		{$dependencies.css}
	</head>
	<body>
