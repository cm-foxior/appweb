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
				$action				= $_POST['action'];
				$id					= ($action == 'edit') ? $_POST['id'] : null;
				$name              	= (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
				$type        	    = (isset($_POST['type']) AND !empty($_POST['type'])) ? $_POST['type'] : null;
				$email              = (isset($_POST['email']) AND !empty($_POST['email'])) ? $_POST['email'] : null;
				$phoneCountryCode	= (isset($_POST['phoneCountryCode']) AND !empty($_POST['phoneCountryCode'])) ? $_POST['phoneCountryCode'] : null;
	            $phoneNumber        = (isset($_POST['phoneNumber']) AND !empty($_POST['phoneNumber'])) ? $_POST['phoneNumber'] : null;
	            $phoneType          = (isset($_POST['phoneType']) AND !empty($_POST['phoneType'])) ? $_POST['phoneType'] : null;
				$address	        = (isset($_POST['address']) AND !empty($_POST['address'])) ? $_POST['address'] : null;
				$fiscalCountry 		= 'México';
				$fiscalName 		= (isset($_POST['fiscalName']) AND !empty($_POST['fiscalName'])) ? $_POST['fiscalName'] : null;
	            $fiscalCode 		= (isset($_POST['fiscalCode']) AND !empty($_POST['fiscalCode'])) ? $_POST['fiscalCode'] : null;
				$fiscalAddress 		= (isset($_POST['fiscalAddress']) AND !empty($_POST['fiscalAddress'])) ? $_POST['fiscalAddress'] : null;

				$errors = [];

	            if (!isset($name))
	                array_push($errors, ['name', 'No deje este campo vacío']);

	            if (!isset($type))
	                array_push($errors, ['type', 'Seleccione una opción']);

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

					$exist = $this->model->checkExistClient($id, $name, $action);

					if ($exist['status'] == true)
					{
						if ($exist['errors']['errorName'] == true)
							array_push($errors, ['name', 'Este registro ya existe']);

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
				define('_title', '{$lang.title} | Dashboard');

				$template = $this->view->render($this, 'index');
				$template = $this->format->replaceFile($template, 'header');
				$clients = $this->model->getAllClients();
				$lstClients = '';

				foreach ($clients as $client)
				{
					if (!empty($provider['phone_number']))
					{
						$phoneNumber = json_decode($provider['phone_number'], true);
						$phoneNumber = $phoneNumber['type'] . '. (+' . $phoneNumber['country_code'] . ') ' . $phoneNumber['number'];
					}
					else
						$phoneNumber = '';

					$lstClients .=
					'<tr>
						<td><input type="checkbox" data-check value="' . $client['id_client'] . '" /></td>
						<td>' . $client['name'] . '</td>
						<td>' . $client['type'] . '</td>
						<td>' . (!empty($client['email']) ? $client['email'] : '') . '</td>
						<td>' . $phoneNumber . '</td>
						<td>' . (!empty($client['fiscal_code']) ? $client['fiscal_code'] : '') . '</td>
						<td>' . (($client['status'] == true) ? '<span class="active">Activado</span>' : '<span class="deactive">Desactivado</span>') . '</td>
						<td>
							<a ' . (($client['status'] == true) ? 'data-action="getClientToEdit" data-id="' . $client['id_client'] . '"' : 'disabled') . '><i class="material-icons">edit</i><span>Detalles / Editar</span></a>
						</td>
					</tr>';
				}

				$replace = [
					'{$lstClients}' => $lstClients
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
}
