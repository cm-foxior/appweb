<?php

defined('_EXEC') or die;

class Clients_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Lista de clientes, crear y editar cliente
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action	= $_POST['action'];
				$id		= ($action == 'edit') ? $_POST['id'] : null;

				$name              	= (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
				$type        	    = (isset($_POST['type']) AND !empty($_POST['type'])) ? $_POST['type'] : null;
				$email              = (isset($_POST['email']) AND !empty($_POST['email'])) ? $_POST['email'] : null;
				$phoneCountryCode	= (isset($_POST['phoneCountryCode']) AND !empty($_POST['phoneCountryCode'])) ? $_POST['phoneCountryCode'] : null;
	            $phoneNumber        = (isset($_POST['phoneNumber']) AND !empty($_POST['phoneNumber'])) ? $_POST['phoneNumber'] : null;
	            $phoneType          = (isset($_POST['phoneType']) AND !empty($_POST['phoneType'])) ? $_POST['phoneType'] : null;
				$address	        = (isset($_POST['address']) AND !empty($_POST['address'])) ? $_POST['address'] : null;
				$fiscalCountry 		= (isset($_POST['fiscalCountry']) AND !empty($_POST['fiscalCountry'])) ? $_POST['fiscalCountry'] : null;
				$fiscalName 		= (isset($_POST['fiscalName']) AND !empty($_POST['fiscalName'])) ? $_POST['fiscalName'] : null;
	            $fiscalCode 		= (isset($_POST['fiscalCode']) AND !empty($_POST['fiscalCode'])) ? $_POST['fiscalCode'] : null;
				$fiscalAddress 		= (isset($_POST['fiscalAddress']) AND !empty($_POST['fiscalAddress'])) ? $_POST['fiscalAddress'] : null;

				$errors = [];

	            if (!isset($name))
	                array_push($errors, ['name', 'No deje este campo vacío']);

	            if (!isset($type))
	                array_push($errors, ['type', 'Seleccione una opción']);
				if ($type != 'Minorista' AND $type != 'Mayorista' AND $type != 'Empresarial')
					array_push($errors, ['type', 'Opción no válida']);

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
					array_push($errors, ['phoneCountryCode', 'No ingrese espacios']);

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

				if (!isset($fiscalCountry) AND isset($fiscalName)
					OR !isset($fiscalCountry) AND isset($fiscalCode)
					OR !isset($fiscalCountry) AND isset($fiscalAddress))
					array_push($errors, ['fiscalCountry', 'Seleccione una opción']);

				if (isset($fiscalCode) AND $fiscalCountry == 'México' AND strlen($fiscalCode) < 12)
					array_push($errors, ['fiscalCode', 'Ingrese mínimo 12 carácteres']);
				else if (isset($fiscalCode) AND $fiscalCountry == 'México' AND strlen($fiscalCode) > 13)
					array_push($errors, ['fiscalCode', 'Ingrese máximo 13 carácteres']);

				if (empty($errors))
				{
					$phoneNumber = json_encode([
						'country_code' => $phoneCountryCode,
						'number' => $phoneNumber,
						'type' => $phoneType
					]);

					if (isset($fiscalCode))
						$fiscalCode = strtoupper($fiscalCode);

					$exist = $this->model->checkExistClient($id, $email, $phoneNumber, $fiscalName, $fiscalCode, $action);

					if ($exist['status'] == true)
					{
						if ($exist['errors']['errorEmail'] == true)
							array_push($errors, ['email', 'Este registro ya existe']);

						if ($exist['errors']['errorPhoneNumber'] == true)
							array_push($errors, ['phoneNumber', 'Este registro ya existe']);

						if ($exist['errors']['errorFiscalName'] == true)
							array_push($errors, ['fiscalName', 'Este registro ya existe']);

						if ($exist['errors']['errorFiscalCode'] == true)
							array_push($errors, ['fiscalCode', 'Este registro ya existe']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
					}
					else
					{
						if ($action == 'new')
							$query = $this->model->newClient($name, $email, $phoneNumber, $address, $fiscalCountry, $fiscalName, $fiscalCode, $fiscalAddress, $type);
						else if ($action == 'edit')
							$query = $this->model->editClient($id, $name, $email, $phoneNumber, $address, $fiscalCountry, $fiscalName, $fiscalCode, $fiscalAddress, $type);

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

				$clients = $this->model->getAllClients();
				$countries = $this->model->getAllCountries();

				$btnDeleteClients = '';
				$btnDeactivateClients = '';
				$btnActivateClients = '';
				$lstClients = '';
				$lstCountriesPhoneCodes = '';
				$lstCountries = '';
				$mdlActivateClients = '';
				$mdlDeactivateClients = '';
				$mdlDeleteClients = '';

				$btnDeleteClients .=
				'<a data-button-modal="deleteClients"><i class="material-icons">delete</i><span>Eliminar</span></a>';

				$btnDeactivateClients .=
				'<a data-button-modal="deactivateClients"><i class="material-icons">block</i><span>Desactivar</span></a>';

				$btnActivateClients .=
				'<a data-button-modal="activateClients"><i class="material-icons">check</i><span>Activar</span></a>';

				foreach ($clients as $client)
				{
	                $phoneNumber = json_decode($client['phone_number'], true);

					$lstClients .=
					'<tr>
						<td><input type="checkbox" data-check value="' . $client['id_client'] . '" /></td>
						<td>' . $client['name'] . '</td>
						<td>' . $client['email'] . '</td>
						<td>' . $phoneNumber['type'] . '. (+' . $phoneNumber['country_code'] . ') ' . $phoneNumber['number'] . '</td>
						<td>' . (!empty($client['fiscal_code']) ? $client['fiscal_code'] : '-') . '</td>
						<td>' . $client['type'] . '</td>
						<td>' . (($client['status'] == true) ? '<span class="active">Activado</span>' : '<span class="deactive">Desactivado</span>') . '</td>
						<td>
							<a ' . (($client['status'] == true) ? 'data-action="getClientToEdit" data-id="' . $client['id_client'] . '"' : 'disabled') . '><i class="material-icons">edit</i><span>Detalles / Editar</span></a>
							<!-- <a href="/clients/view/' . $client['id_client'] . '"><i class="material-icons">more_horiz</i><span>Detalles</span></a> -->
						</td>
					</tr>';
				}

				foreach ($countries as $country)
				{
					$lstCountriesPhoneCodes .=
					'<option value="' . $country['phone_code'] . '" ' . (($country['phone_code'] == '52') ? 'selected' : '') . '>' . '[+' . $country['phone_code'] . '] ' . $country['name'] . '</option>';
				}

				foreach ($countries as $country)
				{
					$lstCountries .=
					'<option value="' . $country['name'] . '">' . $country['name'] . '</option>';
				}

				$mdlActivateClients .=
				'<section class="modal alert" data-modal="activateClients">
				    <div class="content">
				        <header>
				            <h6>Alerta</h6>
				        </header>
				        <main>
				            <p>¿Está seguro de que desea activar está selección de clientes?</p>
				        </main>
				        <footer>
				            <a button-close>Cancelar</a>
				            <a data-action="activateClients">Aceptar</a>
				        </footer>
				    </div>
				</section>';

				$mdlDeactivateClients .=
				'<section class="modal alert" data-modal="deactivateClients">
				    <div class="content">
				        <header>
				            <h6>Alerta</h6>
				        </header>
				        <main>
				            <p>¿Está seguro de que desea desactivar está selección de clientes?</p>
				        </main>
				        <footer>
				            <a button-close>Cancelar</a>
				            <a data-action="deactivateClients">Aceptar</a>
				        </footer>
				    </div>
				</section>';

				$mdlDeleteClients .=
				'<section class="modal alert" data-modal="deleteClients">
				    <div class="content">
				        <header>
				            <h6>Alerta</h6>
				        </header>
				        <main>
				            <p>¿Está seguro de que desea eliminar está selección de clientes?</p>
				        </main>
				        <footer>
				            <a button-close>Cancelar</a>
				            <a data-action="deleteClients">Aceptar</a>
				        </footer>
				    </div>
				</section>';

				$replace = [
					'{$btnDeleteClients}' => $btnDeleteClients,
					'{$btnDeactivateClients}' => $btnDeactivateClients,
					'{$btnActivateClients}' => $btnActivateClients,
					'{$lstClients}' => $lstClients,
					'{$lstCountriesPhoneCodes}' => $lstCountriesPhoneCodes,
					'{$lstCountries}' => $lstCountries,
					'{$mdlActivateClients}' => $mdlActivateClients,
					'{$mdlDeactivateClients}' => $mdlDeactivateClients,
					'{$mdlDeleteClients}' => $mdlDeleteClients
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener cliente para editar
	--------------------------------------------------------------------------- */
	public function getClientToEdit($id)
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$client = $this->model->getClientById($id);

	            if (!empty($client))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $client
					]);
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
		if (Session::getValue('level') == 10)
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

	/* Activar y desactivar selección de clientes
	--------------------------------------------------------------------------- */
	public function changeStatusClients($action)
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					if ($action == 'activate')
						$status = true;
					else if ($action == 'deactivate')
						$status = false;

					$query = $this->model->changeStatusClients($selection, $status);

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

	/* Eliminar selección de clientes
	--------------------------------------------------------------------------- */
	public function deleteClients()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$query = $this->model->deleteClients($selection);

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

	/* Detalles del cliente seleccionado
	--------------------------------------------------------------------------- */
    public function view($id)
    {
		if (Session::getValue('level') == 10)
		{
			define('_title', '{$lang.title}');

			$template = $this->view->render($this, 'view');
			$template = $this->format->replaceFile($template, 'header');

			$client = $this->model->getClientById($id);

			$phoneNumber = json_decode($client['phone_number'], true);

			$replace = [
				'{$name}' => $client['name'],
				'{$email}' => $client['email'],
				'{$phoneNumber}' => $phoneNumber['type'] . '. (+ ' . $phoneNumber['country_code'] . ') ' . $phoneNumber['number'],
				'{$address}' => !empty($client['address']) ? $client['address'] : 'No identificado',
				'{$type}' => $client['type'],
				'{$status}' => ($client['status'] == true) ? 'Activo' : 'Desactivado',
				'{$registrationDate}' => $client['registration_date'],
				'{$fiscalCountry}' => !empty($client['fiscal_country']) ? $client['fiscal_country'] : 'No identificado',
				'{$fiscalNameTitle}' => (empty($client['fiscal_country']) OR $client['fiscal_country'] == 'México') ? 'Razón Social' : 'Nombre Fiscal',
				'{$fiscalName}' => !empty($client['fiscal_name']) ? $client['fiscal_name'] : 'No identificado',
				'{$fiscalCodeTitle}' => (empty($client['fiscal_country']) OR $client['fiscal_country'] == 'México') ? 'RFC' : 'ID Fiscal',
				'{$fiscalCode}' => !empty($client['fiscal_code']) ? $client['fiscal_code'] : 'No identificado',
				'{$fiscalAddress}' => !empty($client['fiscal_address']) ? $client['fiscal_address'] : 'No identificado'
			];

			$template = $this->format->replace($replace, $template);

			echo $template;
		}
		else
			header('Location: /dashboard');
    }
}
