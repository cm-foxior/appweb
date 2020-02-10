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
				$action				= $_POST['action'];
				$id					= ($action == 'edit') ? $_POST['id'] : null;
				$name               = (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
				$email              = (isset($_POST['email']) AND !empty($_POST['email'])) ? $_POST['email'] : null;
				$phoneCountryCode	= (isset($_POST['phoneCountryCode']) AND !empty($_POST['phoneCountryCode'])) ? $_POST['phoneCountryCode'] : null;
				$phoneNumber        = (isset($_POST['phoneNumber']) AND !empty($_POST['phoneNumber'])) ? $_POST['phoneNumber'] : null;
	            $phoneType          = (isset($_POST['phoneType']) AND !empty($_POST['phoneType'])) ? $_POST['phoneType'] : null;
				$address	        = (isset($_POST['address']) AND !empty($_POST['address'])) ? $_POST['address'] : null;
				$fiscalCountry 		= 'México';
				$fiscalName 		= (isset($_POST['fiscalName']) AND !empty($_POST['fiscalName'])) ? $_POST['fiscalName'] : null;
	            $fiscalCode 		= (isset($_POST['fiscalCode']) AND !empty($_POST['fiscalCode'])) ? $_POST['fiscalCode'] : null;
				$fiscalRegime 		= (isset($_POST['fiscalRegime']) AND !empty($_POST['fiscalRegime'])) ? $_POST['fiscalRegime'] : null;
				$fiscalAddress 		= (isset($_POST['fiscalAddress']) AND !empty($_POST['fiscalAddress'])) ? $_POST['fiscalAddress'] : null;

				$errors = [];

	            if (!isset($name))
	                array_push($errors, ['name', 'No deje este campo vacío']);

				if (empty($errors))
				{
					if (isset($phoneCountryCode) AND isset($phoneNumber) AND isset($phoneType))
					{
						$phoneNumber = json_encode([
							'country_code' => '52',
							'number' => $phoneNumber,
							'type' => $phoneType
						]);
					}
					else
						$phoneNumber = null;

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
				define('_title', '{$lang.title} | Dashboard');

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
}
