<?php

defined('_EXEC') or die;

class Prospects_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Lista de prospectos, crear y editar prospecto
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action	= $_POST['action'];
				$id		= ($action == 'edit') ? $_POST['id'] : null;

				$name              	= (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
				$email              = (isset($_POST['email']) AND !empty($_POST['email'])) ? $_POST['email'] : null;
				$phoneCountryCode	= (isset($_POST['phoneCountryCode']) AND !empty($_POST['phoneCountryCode'])) ? $_POST['phoneCountryCode'] : null;
	            $phoneNumber        = (isset($_POST['phoneNumber']) AND !empty($_POST['phoneNumber'])) ? $_POST['phoneNumber'] : null;
	            $phoneType          = (isset($_POST['phoneType']) AND !empty($_POST['phoneType'])) ? $_POST['phoneType'] : null;
				$address	        = (isset($_POST['address']) AND !empty($_POST['address'])) ? $_POST['address'] : null;

				$errors = [];

	            if (!isset($name))
	                array_push($errors, ['name', 'No deje este campo vacío']);

	            if (!isset($email))
	                array_push($errors, ['email', 'No deje este campo vacío']);
	            else if (Security::checkMail($email) == false)
	                array_push($errors, ['email', 'Formato incorrecto']);

				if (!isset($phoneCountryCode))
	                array_push($errors, ['phoneCountryCode', 'Seleccione una opción']);
	            else if (!is_numeric($phoneCountryCode))
	                array_push($errors, ['phoneCountryCode', 'Ingrese únicamente números']);
	            else if ($phoneCountryCode < 0)
	                array_push($errors, ['phoneCountryCode', 'No ingrese números negativos']);
	            else if (Security::checkIsFloat($phoneCountryCode) == true)
	                array_push($errors, ['phoneCountryCode', 'No ingrese números decimales']);
				else if (Security::checkIfExistSpaces($phoneCountryCode) == true)
					array_push($errors, ['phoneNumber', 'No ingrese espacios']);

				if (!isset($phoneNumber))
	                array_push($errors, ['phoneNumber', 'No deje este campo vacío']);
	            else if (!is_numeric($phoneNumber))
	                array_push($errors, ['phoneNumber', 'Ingrese únicamente números']);
	            else if ($phoneNumber < 0)
	                array_push($errors, ['phoneNumber', 'No ingrese números negativos']);
	            else if ($phoneType == 'Móvil' AND strlen($phoneNumber) != 10)
	                array_push($errors, ['phoneNumber', 'Ingrese su número telefónico a 10 digitos']);
	            else if ($phoneType == 'Local' AND strlen($phoneNumber) != 7)
	                array_push($errors, ['phoneNumber', 'Ingrese su número telefónico a 7 digitos']);
	            else if (Security::checkIsFloat($phoneNumber) == true)
	                array_push($errors, ['phoneNumber', 'No ingrese números decimales']);
				else if (Security::checkIfExistSpaces($phoneNumber) == true)
					array_push($errors, ['phoneNumber', 'No ingrese espacios']);

				if (!isset($phoneType))
	                array_push($errors, ['phoneType', 'Seleccione una opción']);
	            else if ($phoneType != 'Local' AND $phoneType != 'Móvil')
	                array_push($errors, ['phoneType', 'Opción no válida']);

				if (empty($errors))
				{
					$phoneNumber = json_encode([
						'country_code' => $phoneCountryCode,
						'number' => $phoneNumber,
						'type' => $phoneType
					]);

					$exist = $this->model->checkExistProspect($id, $email, $phoneNumber, $action);

					if ($exist['status'] == true)
					{
						if ($exist['errors']['errorEmail'] == true)
							array_push($errors, ['email', 'Este registro ya existe']);

						if ($exist['errors']['errorPhoneNumber'] == true)
							array_push($errors, ['phoneNumber', 'Este registro ya existe']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
					}
					else
					{
						if ($action == 'new')
							$query = $this->model->newProspect($name, $email, $phoneNumber, $address);
						else if ($action == 'edit')
							$query = $this->model->editProspect($id, $name, $email, $phoneNumber, $address);

						if (!empty($query))
						{
							echo json_encode([
								'status' => 'success'
							]);
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

				$template = $this->view->render($this, 'index');
				$template = $this->format->replaceFile($template, 'header');

				$prospects = $this->model->getAllProspects();
				$countries = $this->model->getAllCountries();

				$btnDeleteProspect = '';
				$lstProspects = '';
				$lstCountriesPhoneCodes = '';
				$mdlDeleteProspects = '';

				if (Session::getValue('level') >= 9)
				{
					$btnDeleteProspect .=
					'<a data-button-modal="deleteProspects"><i class="material-icons">delete</i><span>Eliminar</span></a>';
				}

				foreach ($prospects as $prospect)
				{
	                $phoneNumber = json_decode($prospect['phone_number'], true);

					$lstProspects .=
					'<tr>
						<td><input type="checkbox" data-check value="' . $prospect['id_client'] . '" /></td>
						<td>' . $prospect['name'] . '</td>
						<td>' . $prospect['email'] . '</td>
						<td>' . $phoneNumber['type'] . '. (+' . $phoneNumber['country_code'] . ') ' . $phoneNumber['number'] . '</td>
						<td>
							<a data-action="getProspectToEdit" data-id="' . $prospect['id_client'] . '"><i class="material-icons">edit</i><span>Detalles / Editar</span></a>
						</td>
					</tr>';
				}

				foreach ($countries as $country)
				{
					$lstCountriesPhoneCodes .=
					'<option value="' . $country['phone_code'] . '" ' . (($country['phone_code'] == '52') ? 'selected' : '') . '>' . '[+' . $country['phone_code'] . '] ' . $country['name'] . '</option>';
				}

				if (Session::getValue('level') >= 9)
				{
					$mdlDeleteProspects .=
					'<section class="modal alert" data-modal="deleteProspects">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea eliminar está selección de prospectos?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="deleteProspects">Aceptar</a>
					        </footer>
					    </div>
					</section>';
				}

				$replace = [
					'{$btnDeleteProspect}' => $btnDeleteProspect,
					'{$lstProspects}' => $lstProspects,
					'{$lstCountriesPhoneCodes}' => $lstCountriesPhoneCodes,
					'{$mdlDeleteProspects}' => $mdlDeleteProspects
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Importar prospectos desde Excel
	--------------------------------------------------------------------------- */
	public function importFromExcel()
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$xlsx = (isset($_FILES['xlsx']['name']) AND !empty($_FILES['xlsx']['name'])) ? $_FILES['xlsx'] : null;

				$errors = [];

				if (!isset($xlsx))
	                array_push($errors, ['xlsx', 'Seleccione un archivo']);

				if (empty($errors))
				{
					$query = $this->model->importFromExcel($xlsx);

					if ($query['status'] == 'success')
					{
						echo json_encode([
							'status' => 'success'
						]);
					}
					else if ($query['status'] == 'error')
					{
						echo json_encode([
							'status' => 'error',
							'labels' => $query['errors']
						]);
					}
					else
					{
						echo json_encode([
							'status' => 'error',
							'labels' => [['xlsx', 'Error desconocido. Pongase en contácto con soporte técnico']]
						]);
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
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener prospecto para editar
	--------------------------------------------------------------------------- */
	public function getProspectToEdit($id)
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$prospect = $this->model->getProspectById($id);

	            if (!empty($prospect))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $prospect
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Agregar selección de prospectos a clientes
	--------------------------------------------------------------------------- */
	public function addToClients()
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$query = $this->model->addToClients($selection);

					if (!empty($query))
					{
						echo json_encode([
							'status' => 'success'
						]);
					}
				}
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Enviar correo masivo a clientes
	--------------------------------------------------------------------------- */
	public function sendMassEmail()
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$subject	= (isset($_POST['subject']) AND !empty($_POST['subject'])) ? $_POST['subject'] : null;
				$message	= (isset($_POST['message']) AND !empty($_POST['message'])) ? $_POST['message'] : null;
				$image		= (isset($_FILES['image']['name']) AND !empty($_FILES['image']['name'])) ? $_FILES['image'] : null;
				$sendTo		= (isset($_POST['sendTo']) AND !empty($_POST['sendTo'])) ? $_POST['sendTo'] : null;
				$selected	= (isset($_POST['selected']) AND !empty($_POST['selected'])) ? $_POST['selected'] : null;

				$errors = [];

				if (!isset($subject))
					array_push($errors, ['subject', 'No deje este campo vacío']);

				if (!isset($sendTo))
					array_push($errors, ['sendTo', 'Seleccione una opción']);
				else if ($sendTo != 'selected' AND $sendTo != 'all')
					array_push($errors, ['sendTo', 'Opción no válida']);

				if ($sendTo == 'selected' AND !isset($selected))
					array_push($errors, ['subject', 'No se seleccionarón clientes']);

				if (empty($errors))
				{
					$query = $this->model->sendMassEmail($subject, $message, $image, $sendTo, $selected);

					echo json_encode([
						'status' => 'success'
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
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Eliminar selección de prospectos
	--------------------------------------------------------------------------- */
	public function deleteProspects()
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$query = $this->model->deleteProspects($selection);

					if (!empty($query))
					{
						echo json_encode([
							'status' => 'success'
						]);
					}
				}
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}
}
