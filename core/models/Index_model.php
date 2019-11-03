<?php

defined('_EXEC') or die;

class Index_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Envio de correo electrónico
	--------------------------------------------------------------------------- */
	public function sendEmail($email, $name, $subject, $message)
	{
		$this->component->loadComponent('phpmailer');

		send_email(
			[
				$email => $name
			],
			FALSE,
			FALSE,
			FALSE,
			FALSE,
			$subject,
			$message,
			''
		);
	}
}
