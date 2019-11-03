<?php

defined('_EXEC') or die;

class Index_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Landing page y envio de correo electrónico de contacto
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Format::existAjaxRequest() == true)
		{
			$name 		= (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
			$email 		= (isset($_POST['email']) AND !empty($_POST['email'])) ? $_POST['email'] : null;
			$phone 		= (isset($_POST['phone']) AND !empty($_POST['phone'])) ? $_POST['phone'] : null;
			$interested	= (isset($_POST['interested']) AND !empty($_POST['interested'])) ? $_POST['interested'] : null;
			$message 	= (isset($_POST['message']) AND !empty($_POST['message'])) ? $_POST['message'] : null;

			$errors = [];

			if (!isset($name))
				array_push($errors, ['name', 'No deje este cámpo vacío']);

			if (!isset($email))
				array_push($errors, ['email', 'No deje este cámpo vacío']);
			else if (Security::checkMail($email) == false)
                array_push($errors, ['email', 'Formato incorrecto']);

			if (!isset($phone))
				array_push($errors, ['phone', 'No deje este campo vacío']);
			else if (!is_numeric($phone))
				array_push($errors, ['phone', 'Ingrese únicamente números']);
			else if ($phone < 0)
				array_push($errors, ['phone', 'No ingrese números negativos']);
			else if (strlen($phone) != 10)
				array_push($errors, ['phone', 'Ingrese su número telefónico a 10 digitos']);
			else if (Security::checkIsFloat($phone) == true)
				array_push($errors, ['phone', 'No ingrese números decimales']);
			else if (Security::checkIfExistSpaces($phone) == true)
				array_push($errors, ['phone', 'No ingrese espacios']);

			if (!isset($interested))
				array_push($errors, ['interested', 'Seleccione una opción']);

			if (!isset($message) AND $interested == 'Otro')
				array_push($errors, ['message', 'No deje este campo vacío']);

			if (empty($errors))
			{
				$message = '
				<strong>Nombre</strong>: ' . $name . '<br>
				<strong>Email</strong>: ' . $email . '<br>
				<strong>Telefono</strong>: ' . $phone . '<br>
				<strong>Interes</strong>: ' . $interested . '<br>
				<strong>Mensaje</strong>: ' . (isset($message) ? $message : 'Mensaje no establecido');

				$query = $this->model->sendEmail('contacto@sofierp.com', 'Sofi ERP', $name . ' - ' . $email, $message);

				echo json_encode([
					'status' => 'success',
					'data' => [
						'name' => $name,
						'email' => $email,
						'phone' => $phone
					]
				]);
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

			$template = $this->view->render($this, 'index');
			
			$replace = [

			];

			$template = $this->format->replace($replace, $template);

			echo $template;
		}
	}
}
