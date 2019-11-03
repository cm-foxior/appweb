<?php

defined('_EXEC') or die;

class Providers_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Lista de proveedores, crear y editar proveedor
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action	= $_POST['action'];
				$id		= ($action == 'edit') ? $_POST['id'] : null;

				$name               = (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
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

	            if (isset($email) AND Security::checkMail($email) == false)
	                array_push($errors, ['email', 'Formato incorrecto']);

				if (!isset($phoneCountryCode) AND isset($phoneNumber))
	                array_push($errors, ['phoneCountryCode', 'Seleccione una opción']);
	            else if (isset($phoneCountryCode) AND !is_numeric($phoneCountryCode))
	                array_push($errors, ['phoneCountryCode', 'Ingrese únicamente números']);
	            else if (isset($phoneCountryCode) AND $phoneCountryCode < 0)
	                array_push($errors, ['phoneCountryCode', 'No ingrese números negativos']);
	            else if (isset($phoneCountryCode) AND Security::checkIsFloat($phoneCountryCode) == true)
	                array_push($errors, ['phoneCountryCode', 'No ingrese números decimales']);
				else if (isset($phoneCountryCode) AND Security::checkIfExistSpaces($phoneCountryCode) == true)
					array_push($errors, ['phoneCountryCode', 'No ingrese espacios']);

				if (isset($phoneNumber) AND !is_numeric($phoneNumber))
	                array_push($errors, ['phoneNumber', 'Ingrese únicamente números']);
	            else if (isset($phoneNumber) AND $phoneNumber < 0)
	                array_push($errors, ['phoneNumber', 'No ingrese números negativos']);
	            else if (isset($phoneNumber) AND $phoneType == 'Móvil' AND strlen($phoneNumber) != 10)
	                array_push($errors, ['phoneNumber', 'Ingrese su número telefónico a 10 digitos']);
	            else if (isset($phoneNumber) AND $phoneType == 'Local' AND strlen($phoneNumber) != 7)
	                array_push($errors, ['phoneNumber', 'Ingrese su número telefónico a 7 digitos']);
	            else if (isset($phoneNumber) AND Security::checkIsFloat($phoneNumber) == true)
	                array_push($errors, ['phoneNumber', 'No ingrese números decimales']);
				else if (isset($phoneNumber) AND Security::checkIfExistSpaces($phoneNumber) == true)
					array_push($errors, ['phoneNumber', 'No ingrese espacios']);

				if (!isset($phoneType) AND isset($phoneNumber))
	                array_push($errors, ['phoneType', 'Seleccione una opción']);
	            else if (isset($phoneType) AND $phoneType != 'Local' AND isset($phoneType) AND $phoneType != 'Móvil')
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
					if (isset($phoneCountryCode) AND isset($phoneNumber) AND isset($phoneType))
					{
						$phoneNumber = json_encode([
							'country_code' => $phoneCountryCode,
							'number' => $phoneNumber,
							'type' => $phoneType
						]);
					}
					else
						$phoneNumber = null;

					if (isset($fiscalCode))
						$fiscalCode = strtoupper($fiscalCode);

					$exist = $this->model->checkExistProvider($id, $name, $fiscalName, $fiscalCode, $action);

					if ($exist['status'] == true)
					{
						if ($exist['errors']['errorName'] == true)
							array_push($errors, ['name', 'Este registro ya existe']);

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
							$query = $this->model->newProvider($name, $email, $phoneNumber, $address, $fiscalCountry, $fiscalName, $fiscalCode, $fiscalAddress);
						else if ($action == 'edit')
							$query = $this->model->editProvider($id, $name, $email, $phoneNumber, $address, $fiscalCountry, $fiscalName, $fiscalCode, $fiscalAddress);

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

				$providers = $this->model->getAllProviders();
				$countries = $this->model->getAllCountries();

				$lstProviders = '';
				$lstCountriesPhoneCodes = '';
				$lstCountries = '';

				foreach ($providers as $provider)
				{
					if (!empty($provider['phone_number']))
					{
						$phoneNumber = json_decode($provider['phone_number'], true);
						$phoneNumber = $phoneNumber['type'] . '. (+' . $phoneNumber['country_code'] . ') ' . $phoneNumber['number'];
					}
					else
						$phoneNumber = '-';

					$lstProviders .=
					'<tr>
						<td><input type="checkbox" data-check value="' . $provider['id_provider'] . '" /></td>
						<td>' . $provider['name'] . '</td>
						<td>' . (!empty($provider['email']) ? $provider['email'] : '-') . '</td>
						<td>' . $phoneNumber . '</td>
						<td>' . (!empty($provider['fiscal_code']) ? $provider['fiscal_code'] : '-') . '</td>
						<td>' . (($provider['status'] == true) ? '<span class="active">Activado</span>' : '<span class="deactive">Desactivado</span>') . '</td>
						<td>
							<a ' . (($provider['status'] == true) ? 'data-action="getProviderToEdit" data-id="' . $provider['id_provider'] . '"' : 'disabled') . '><i class="material-icons">edit</i><span>Detalles / Editar</span></a>
							<!-- <a href="/providers/view/' . $provider['id_provider'] . '"><i class="material-icons">more_horiz</i><span>Detalles</span></a> -->
						</td>
					</tr>';
				}

				foreach ($countries as $country)
				{
					$lstCountriesPhoneCodes .=
					'<option value="' . $country['phone_code'] . '" ' . (($country['phone_code'] == '52') ? 'selected' : '') . '>[+' . $country['phone_code'] . '] ' . $country['name'] . '</option>';
				}

				foreach ($countries as $country)
				{
					$lstCountries .=
					'<option value="' . $country['name'] . '">' . $country['name'] . '</option>';
				}

				$replace = [
					'{$lstProviders}' => $lstProviders,
					'{$lstCountriesPhoneCodes}' => $lstCountriesPhoneCodes,
					'{$lstCountries}' => $lstCountries
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener proveedor para editar
	--------------------------------------------------------------------------- */
	public function getProviderToEdit($id)
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$provider = $this->model->getProviderById($id);

	            if (!empty($provider))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $provider
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Activar y desactivar selección de proveedores
	--------------------------------------------------------------------------- */
	public function changeStatusProviders($action)
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

					$query = $this->model->changeStatusProviders($selection, $status);

					if (!empty($query))
					{
						echo json_encode([
							'status' => 'success'
						]);
					}
				}
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Eliminar selección de proveedores
	--------------------------------------------------------------------------- */
	public function deleteProviders()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$query = $this->model->deleteProviders($selection);

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

	/* Detalles del proveedor seleccionado
	--------------------------------------------------------------------------- */
    public function view($id)
    {
		if (Session::getValue('level') == 10)
		{
			define('_title', '{$lang.title}');

	        $template = $this->view->render($this, 'view');
	        $template = $this->format->replaceFile($template, 'header');

	        $provider = $this->model->getProviderById($id);

			if (!empty($provider['phone_number']))
				$phoneNumber = json_decode($provider['phone_number'], true);

	        $replace = [
	            '{$name}' => $provider['name'],
	            '{$email}' => !empty($provider['email']) ? $provider['email'] : 'No identificado',
	            '{$phoneNumber}' => !empty($provider['phone_number']) ? $phoneNumber['type'] . '. (+' . $phoneNumber['country_code'] . ') ' . $phoneNumber['number'] : 'No identificado',
	            '{$address}' => !empty($provider['address']) ? $provider['address'] : 'No identificado',
				'{$status}' => ($provider['status'] == true) ? 'Activo' : 'Desactivado',
				'{$registrationDate}' => $provider['registration_date'],
				'{$fiscalCountry}' => !empty($provider['fiscal_country']) ? $provider['fiscal_country'] : 'No identificado',
	            '{$fiscalNameTitle}' => (empty($provider['fiscal_country']) OR $provider['fiscal_country'] == 'México') ? 'Razón Social' : 'Nombre Fiscal',
				'{$fiscalName}' => !empty($provider['fiscal_name']) ? $provider['fiscal_name'] : 'No identificado',
	            '{$fiscalCodeTitle}' => (empty($provider['fiscal_country']) OR $provider['fiscal_country'] == 'México') ? 'RFC' : 'ID Fiscal',
				'{$fiscalCode}' => !empty($provider['fiscal_code']) ? $provider['fiscal_code'] : 'No identificado',
	            '{$fiscalAddress}' => !empty($provider['fiscal_address']) ? $provider['fiscal_address'] : 'No identificado'
	        ];

	        $template = $this->format->replace($replace, $template);

	        echo $template;
		}
		else
			header('Location: /dashboard');
    }
}
