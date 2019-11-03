<?php

defined('_EXEC') or die;

class Login_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if ( Format::existAjaxRequest() == true )
		{
			$username = ( isset($_POST['username']) && !empty($_POST['username']) ) ? $_POST['username'] : null;
			$password = ( isset($_POST['password']) && !empty($_POST['password']) ) ? $_POST['password'] : null;

			$user = $this->model->findUser($username);

			if ( !empty($user) )
			{
				$crypto = explode(':', $user['password']);
				$checkPassword = ( $this->security->createHash('sha1', $password . $crypto[1]) === $crypto[0] ) ? true : false;

				if ( $checkPassword == true )
				{
					Session::init(['cookie_lifetime' => 86400]);

					if (!empty($user['phone_number']))
					{
						$phone = json_decode($user['phone_number'], true);
						$phone = $phone['type'] . '. + ' . $phone['country_code'] . $phone['number'];
					}
					else
						$phone = '';

					if ($user['level'] == 10)
						$branchOffice = '';
					else
						$branchOffice = $user['id_branch_office'];

					Session::setValue('session', true);
					Session::setValue('id_user', $user['id']);
					Session::setValue('name', $user['name']);
					Session::setValue('email', $user['email']);
					Session::setValue('phone_number', $phone);
					Session::setValue('username', $user['username']);
					Session::setValue('level', $user['level']);
					Session::setValue('registration_date', $user['registration_date']);
					Session::setValue('avatar', $user['avatar']);
					Session::setValue('id_branch_office', $branchOffice);
					Session::setValue('id_subscription', $user['id_subscription']);

					echo json_encode([
						'status' => 'success'
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'labels' => [
							[
								'password',
								'La contraseña no coincide'
							]
						]
					]);
				}
			}
			else
			{
				echo json_encode([
					'status' => 'error',
					'labels' => [
						[
							'username',
							'el usuario no existe'
						]
					]
				]);
			}
		}
		else
		{
			define('_title', '{$lang.title}');
			$template = $this->view->render($this, 'index');

			echo $template;
		}
	}

}
