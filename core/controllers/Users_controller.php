<?php

defined('_EXEC') or die;

class Users_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Lista de usuarios, crear y editar usuario
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Session::getValue('level') == 10)
		{
			$userLogged = $this->model->getUserLogged();

			if (Format::existAjaxRequest() == true)
			{
				$action				= $_POST['action'];
				$id					= ($action == 'edit') ? $_POST['id'] : null;
				$name               = (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
				$email              = (isset($_POST['email']) AND !empty($_POST['email'])) ? $_POST['email'] : null;
				$phoneCountryCode	= (isset($_POST['phoneCountryCode']) AND !empty($_POST['phoneCountryCode'])) ? $_POST['phoneCountryCode'] : null;
	            $phoneNumber        = (isset($_POST['phoneNumber']) AND !empty($_POST['phoneNumber'])) ? $_POST['phoneNumber'] : null;
	            $phoneType          = (isset($_POST['phoneType']) AND !empty($_POST['phoneType'])) ? $_POST['phoneType'] : null;
	            $username           = (isset($_POST['username']) AND !empty($_POST['username'])) ? $_POST['username'] : null;
	            $password           = (isset($_POST['password']) AND !empty($_POST['password'])) ? $_POST['password'] : null;
	            $level              = (isset($_POST['level']) AND !empty($_POST['level'])) ? $_POST['level'] : null;
				$avatar 			= (isset($_FILES['avatar']['name']) AND !empty($_FILES['avatar']['name'])) ? $_FILES['avatar'] : null;
				$branchOffice 		= (isset($_POST['branchOffice']) AND !empty($_POST['branchOffice'])) ? $_POST['branchOffice'] : null;

				$errors = [];

	            if (!isset($name))
	                array_push($errors, ['name', 'No deje este campo vacío']);

				if (!isset($username))
					array_push($errors, ['username', 'No deje este campo vacío']);
				else if (Security::checkIfExistSpaces($username) == true)
	                array_push($errors, ['username', 'No ingrese espacios']);

				if ($action == 'new' AND !isset($password))
					array_push($errors, ['password', 'No deje este campo vacío']);
				else if ($action == 'new' AND Security::checkIfExistSpaces($password) == true)
	                array_push($errors, ['password', 'No ingrese espacios']);

				if (!isset($level))
					array_push($errors, ['level', 'Seleccione una opción']);

				if ($level != '10' AND !isset($branchOffice))
					array_push($errors, ['branchOffice', 'Seleccione una opción']);

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

					if ($action == 'new')
						$password = $this->security->createPassword($password);

					$exist = $this->model->checkExistUser($id, $username, $action);

					if ($exist['status'] == true)
					{
						if ($exist['errors']['errorUsername'] == true)
							array_push($errors, ['username', 'Este registro ya existe']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
					}
					else
					{
						if ($action == 'new')
							$query = $this->model->newUser($name, $email, $phoneNumber, $username, $password, $level, $avatar, $branchOffice);
						else if ($action == 'edit')
							$query = $this->model->editUser($id, $name, $email, $phoneNumber, $username, $level, $avatar, $branchOffice);

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
				$users = $this->model->getAllUsers();
				$branchOffices = $this->model->getAllBranchOffices();
				$tblUsers = '';
				$lstBranchOffices = '';

				foreach ($users as $user)
				{
					if (!empty($user['id_branch_office']))
					{
						$branchOffice = $this->model->getBranchOfficeById($user['id_branch_office']);
						$branchOffice = $branchOffice['name'];
					}
					else
						$branchOffice = '';

					if ($user['level'] == '10')
						$level = 'Administrador';
					else if ($user['level'] == '9')
						$level = 'Supervisor';
					else if ($user['level'] == '8')
						$level = 'Inventarista';
					else if ($user['level'] == '7')
						$level = 'Vendedor';

					$tblUsers .=
					'<tr>
						<td><input type="checkbox" data-check value="' . $user['id_user'] . '" /></td>
						<td>' . (!empty($user['avatar']) ? '<a href="{$path.images}users/' . $user['avatar'] . '" class="fancybox-thumb" rel="fancybox-thumb"><img src="{$path.images}users/' . $user['avatar'] . '" /></a>' : '<img src="{$path.images}users/avatar.png" class="emptyAvatar" />') . '</td>
						<td>' . $user['name'] . '</td>
						<td>' . $branchOffice . '</td>
						<td>' . $level . '</td>
						<td>' . (($user['status'] == true) ? '<span class="active">Activado</span>' : '<span class="deactive">Desctivado</span>') . '</td>
						<td>
							<a ' . (($user['status'] == true) ? 'data-action="getUserToEdit" data-id="' . $user['id_user'] . '"' : 'disabled') . '><i class="material-icons">edit</i><span>Detalles / Editar</span></a>
							<a ' . (($user['status'] == true) ? 'data-action="getUserToRestorePassword" data-id="' . $user['id_user'] . '" data-button-modal="restoreUserPassword"' : 'disabled') . '><i class="material-icons">lock_outline</i><span>Restablecer contraseña</span></a>
						</td>
					</tr>';
				}

				$lstBranchOffices .=
				'<fieldset class="input-group hidden">
                    <label data-important>
                        <span><span class="required-field">*</span>Sucursal</span>
                        <select name="branchOffice" class="chosen-select">
                            <option value="">Seleccione una opción</option>';

				foreach ($branchOffices as $branchOffice)
				{
					$lstBranchOffices .=
					'<option value="' . $branchOffice['id_branch_office'] . '">' . $branchOffice['name'] . '</option>';
				}

				$lstBranchOffices .=
				'		</select>
                    </label>
                </fieldset>';

				$replace = [
					'{$tblUsers}' => $tblUsers,
					'{$lstBranchOffices}' => $lstBranchOffices
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener usuario para editar
	--------------------------------------------------------------------------- */
	public function getUserToEdit($id)
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$user = $this->model->getUserById($id);

	            if (!empty($user))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $user
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Restablecer contraseña de usuario
	--------------------------------------------------------------------------- */
	public function restoreUserPassword()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$id = $_POST['id'];

				$password = (isset($_POST['newPassword']) AND !empty($_POST['newPassword'])) ? $_POST['newPassword'] : null;

				$errors = [];

				if (!isset($password))
					array_push($errors, ['newPassword', 'No deje este campo vacío']);
				else if (Security::checkIfExistSpaces($password) == true)
	                array_push($errors, ['newPassword', 'No ingrese espacios']);

				if (empty($errors))
				{
					$password = $this->security->createPassword($password);

					$query = $this->model->restoreUserPassword($id, $password);

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

	/* Activar y desactivar selección de usuarios
	--------------------------------------------------------------------------- */
	public function changeStatusUsers($action)
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

					$query = $this->model->changeStatusUsers($selection, $status);

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

	/* Eliminar selección de usuarios
	--------------------------------------------------------------------------- */
	public function deleteUsers()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$query = $this->model->deleteUsers($selection);

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
