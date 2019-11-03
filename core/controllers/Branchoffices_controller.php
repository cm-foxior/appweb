<?php

defined('_EXEC') or die;

class Branchoffices_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Lista de sucursales, crear y editar sucursal
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
				$fiscalRegime 		= (isset($_POST['fiscalRegime']) AND !empty($_POST['fiscalRegime'])) ? $_POST['fiscalRegime'] : null;
				$fiscalAddress 		= (isset($_POST['fiscalAddress']) AND !empty($_POST['fiscalAddress'])) ? $_POST['fiscalAddress'] : null;

				$errors = [];

	            if (!isset($name))
	                array_push($errors, ['name', 'No deje este campo vacío']);

	            if (isset($email) AND Security::checkMail($email) == false)
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

				if (!isset($address))
					array_push($errors, ['address', 'No deje este campo vacío']);

				if (!isset($fiscalCountry))
					array_push($errors, ['fiscalCountry', 'Seleccione una opción']);

				if (!isset($fiscalName))
					array_push($errors, ['fiscalName', 'No deje este campo vacío']);

				if (!isset($fiscalCode))
					array_push($errors, ['fiscalCode', 'No deje este campo vacío']);
				else if ($fiscalCountry == 'México' AND strlen($fiscalCode) < 12)
					array_push($errors, ['fiscalCode', 'Ingrese mínimo 12 carácteres']);
				else if ($fiscalCountry == 'México' AND strlen($fiscalCode) > 13)
					array_push($errors, ['fiscalCode', 'Ingrese máximo 13 carácteres']);

				if (!isset($fiscalRegime))
					array_push($errors, ['fiscalRegime', 'No deje este campo vacío']);

				if (!isset($fiscalAddress))
					array_push($errors, ['fiscalAddress', 'No deje este campo vacío']);

				if (empty($errors))
				{
					$phoneNumber = json_encode([
						'country_code' => '52',
						'number' => $phoneNumber,
						'type' => $phoneType
					]);

					$fiscalCountry = 'México';
					$fiscalCode = strtoupper($fiscalCode);

					$exist = $this->model->checkExistBranchOffice($id, $name, $action);

					if ($exist == true)
					{
						array_push($errors, ['name', 'Este registro ya existe']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
					}
					else
					{
						if ($action == 'new')
							$query = $this->model->newBranchOffice($name, $email, $phoneNumber, $address, $fiscalCountry, $fiscalName, $fiscalCode, $fiscalRegime, $fiscalAddress);
						else if ($action == 'edit')
							$query = $this->model->editBranchOffice($id, $name, $email, $phoneNumber, $address, $fiscalCountry, $fiscalName, $fiscalCode, $fiscalRegime, $fiscalAddress);

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

				$branchOffices = $this->model->getAllBranchOffices();

				$lstBranchOffices = '';

				foreach ($branchOffices as $branchOffice)
				{
					$lstBranchOffices .=
					'<tr>
						<td><input type="checkbox" data-check value="' . $branchOffice['id_branch_office'] . '" /></td>
						<td>' . $branchOffice['name'] . '</td>
						<td>' . (($branchOffice['status'] == true) ? '<span class="active">Activado</span>' : '<span class="deactive">Desactivado</span>') . '</td>
						<td>
							<a ' . (($branchOffice['status'] == true) ? 'data-action="getBranchOfficeToEdit" data-id="' . $branchOffice['id_branch_office'] . '"' : 'disabled') . '><i class="material-icons">edit</i><span>Detalles / Editar</span></a>
							<!-- <a href="/branchoffices/view/' . $branchOffice['id_branch_office'] . '"><i class="material-icons">more_horiz</i><span>Detalles</span></a> -->
						</td>
					</tr>';
				}

				$replace = [
					'{$lstBranchOffices}' => $lstBranchOffices
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener sucursal para editar
	--------------------------------------------------------------------------- */
	public function getBranchOfficeToEdit($id)
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$branchOffice = $this->model->getBranchOfficeById($id);

	            if (!empty($branchOffice))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $branchOffice
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Activar y desactivar selección de sucursales
	--------------------------------------------------------------------------- */
	public function changeStatusBranchOffices($action)
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

					$query = $this->model->changeStatusBranchOffices($selection, $status);

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

	/* Eliminar selección de sucursales
	--------------------------------------------------------------------------- */
	public function deleteBranchOffices()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$query = $this->model->deleteBranchOffices($selection);

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

	/* Detalles del la sucursal seleccionada
	--------------------------------------------------------------------------- */
    public function view($id)
    {
		if (Session::getValue('level') == 10)
		{
			define('_title', '{$lang.title}');

	        $template = $this->view->render($this, 'view');
	        $template = $this->format->replaceFile($template, 'header');

	        $branchOffice = $this->model->getBranchOfficeById($id);

        	$phoneNumber = json_decode($branchOffice['phone_number'], true);

	        $replace = [
	            '{$name}' => $branchOffice['name'],
	            '{$email}' => !empty($branchOffice['email']) ? $branchOffice['email'] : 'No identificado',
	            '{$phoneNumber}' => $phoneNumber['type'] . '. (+ ' . $phoneNumber['country_code'] . ') ' . $phoneNumber['number'],
				'{$address}' => $branchOffice['address'],
				'{$status}' => ($branchOffice['status'] == true) ? 'Activo' : 'Desactivado',
				'{$registrationDate}' => $branchOffice['registration_date'],
				'{$fiscalCountry}' => $branchOffice['fiscal_country'],
				'{$fiscalName}' => $branchOffice['fiscal_name'],
	            '{$fiscalCode}' => $branchOffice['fiscal_code'],
	            '{$fiscalRegime}' => $branchOffice['fiscal_regime'],
	            '{$fiscalAddress}' => $branchOffice['address']
	        ];

	        $template = $this->format->replace($replace, $template);

	        echo $template;
		}
		else
			header('Location: /dashboard');
    }
}
