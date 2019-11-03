<?php

defined('_EXEC') or die;

class Subscriptions_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Lista de suscripciones, nuevo-editar suscripción
	--------------------------------------------------------------------------- */
	public function index()
	{
		header('Location: /');
	}

	/* Suscribirse a la plataforma
	--------------------------------------------------------------------------- */
	public function signin($type)
	{
		if (isset($type) AND !empty($type) AND $type == 'basic'
			OR isset($type) AND !empty($type) AND $type == 'standard'
			OR isset($type) AND !empty($type) AND $type == 'premium'
			OR isset($type) AND !empty($type) AND $type == 'test')
		{
			if (Format::existAjaxRequest() == true)
			{
				$business 	= (isset($_POST['business']) AND !empty($_POST['business'])) ? $_POST['business'] : null;
				$email 		= (isset($_POST['email']) AND !empty($_POST['email'])) ? $_POST['email'] : null;
				$name 		= (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
				$username	= (isset($_POST['username']) AND !empty($_POST['username'])) ? $_POST['username'] : null;
				$password 	= (isset($_POST['password']) AND !empty($_POST['password'])) ? $_POST['password'] : null;

				$errors = [];

				if (!isset($business))
					array_push($errors, ['business', 'No deje vacío este cámpo']);

				if (!isset($email))
					array_push($errors, ['email', 'No deje vacío este cámpo']);
				else if (Security::checkMail($email) == false)
	                array_push($errors, ['email', 'Formato incorrecto']);

				if (!isset($name))
					array_push($errors, ['name', 'No deje vacío este cámpo']);

				if (!isset($username))
					array_push($errors, ['username', 'No deje este campo vacío']);
				else if (strlen($username) < 4)
					array_push($errors, ['username', 'Ingrese mínimo 4 caracteres']);
				else if (strlen($username) > 20)
					array_push($errors, ['username', 'Ingrese máximo 20 caracteres']);
				else if (Security::checkIfExistSpaces($username) == true)
	                array_push($errors, ['username', 'No ingrese espacios']);

				if (!isset($password))
					array_push($errors, ['password', 'No deje este campo vacío']);
				else if (strlen($password) < 8)
					array_push($errors, ['password', 'Ingrese mínimo 8 caracteres']);
				else if (Security::checkIfExistSpaces($password) == true)
	                array_push($errors, ['password', 'No ingrese espacios']);

				if (empty($errors))
				{
					$email = strtolower($email);
					$username = strtolower($username);
					$password = $this->security->createpassword($password);

					$exist = $this->model->checkExistSubscriptionAndUser($email, $username);

					if ($exist['status'] == true)
					{
						if ($exist['errors']['errorEmail'] == true)
						{
							echo json_encode([
								'status' => 'existing',
								'message' => 'Lo sentimos, esta suscripción ya está registrada. Si cree que es un error le invitamos que se ponga en contácto con nuestro soporte técnico.'
							]);
						}
						else if ($exist['errors']['errorUsername'] == true)
						{
							array_push($errors, ['username', 'Este registro ya existe. Porfavor elíga otro nombre de usuario']);

							echo json_encode([
								'status' => 'error',
								'labels' => $errors
							]);
						}
					}
					else
					{
						$subscription = $this->model->newSubscription($business, $email, $type);

						if (!empty($subscription))
						{
							$user = $this->model->newUser($name, $username, $password, $type, $subscription);

							if (!empty($user))
							{
								if ($type == 'basic' OR $type == 'standard' OR $type == 'premium')
									$path = '/subscriptions/payment/' . $type;
								else if ($type == 'test')
									$path = '/login';

								echo json_encode([
									'status' => 'success',
									'path' => $path
								]);
							}
							else
							{
								$deleteSubscription = $this->model->deleteSubscription($subscription);

								echo json_encode([
									'status' => 'error',
									'message' => 'Error en la operación a la base de datos'
								]);
							}
						}
						else
						{
							echo json_encode([
								'status' => 'error',
								'message' => 'Error en la operación a la base de datos'
							]);
						}
					}
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'labels' => $errors
					]);
				}
			}
			else
			{
				define('_title', '{$lang.title}');

				$template = $this->view->render($this, 'signin');

				header('Location: /');

				if ($type == 'basic')
				{
					$price = '$ 199.00 MXN';
					$type = 'Básico';
					$time = 'Mensual';
					$description = 'Por solo 99 pesos al mes ten acceso a los módulos de inventarios, ventas, catálogos, reportes y usuarios';
					$btnSignInTitle = 'Pagar y suscribirse';
				}
				else if ($type == 'standard')
				{
					$price = '$ 299.00 MXN';
					$type = 'Estándar';
					$time = 'Mensual';
					$description = '¡Este es el paquete ideal! Ten acceso ilimitado a todos los módulos del sistema, lleva tu negocio al siguiente nivel';
					$btnSignInTitle = 'Pagar y suscribirse';
				}
				else if ($type == 'premium')
				{
					$price = '$ 399.00 MXN';
					$type = 'Premium';
					$time = 'Mensual';
					$description = '¿Tienes más de un negocio? Controla hasta cuatro negocios con acceso ilimitado a todos los módulos del sistema';
					$btnSignInTitle = 'Pagar y suscribirse';
				}
				else if ($type == 'test')
				{
					$price = 'Gratuito';
					$type = 'Prueba';
					$time = '30 días';
					$description = '¡Espera! ¿No te convencimos? danos la oportunidad de mostrarte nuestro producto totalmente gratuito';
					$btnSignInTitle = 'Inícia tu prueba';
				}

				$replace = [
					'{$price}' => $price,
					'{$type}' => $type,
					'{$time}' => $time,
					'{$description}' => $description,
					'{$btnSignInTitle}' => $btnSignInTitle
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /');
	}
}
