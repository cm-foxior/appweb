<?php

defined('_EXEC') or die;

class Directory_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Lista de contactos, crear y editar contacto
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action	= $_POST['action'];
				$id = ($action == 'edit') ? $_POST['id'] : null;

				$name = (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
				$email = (isset($_POST['email']) AND !empty($_POST['email'])) ? $_POST['email'] : null;
				$phoneCountryCode = (isset($_POST['phoneCountryCode']) AND !empty($_POST['phoneCountryCode'])) ? $_POST['phoneCountryCode'] : null;
	            $phoneNumber = (isset($_POST['phoneNumber']) AND !empty($_POST['phoneNumber'])) ? $_POST['phoneNumber'] : null;
	            $phoneType = (isset($_POST['phoneType']) AND !empty($_POST['phoneType'])) ? $_POST['phoneType'] : null;
	            $contact = (isset($_POST['client']) AND !empty($_POST['client'])) ? $_POST['client'] : null;
				$category = (isset($_POST['category']) AND !empty($_POST['category'])) ? $_POST['category'] : null;

				$errors = [];

	            if (!isset($name))
	                array_push($errors, ['name', 'No deje este campo vacío']);

	            if (isset($email) AND Security::checkMail($email) == false)
	                array_push($errors, ['email', 'Formato incorrecto']);

				if (isset($phoneCountryCode) AND !is_numeric($phoneCountryCode))
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
	            else if (isset($phoneNumber) AND $phoneType == 'Móvil' AND isset($phoneNumber) AND strlen($phoneNumber) != 10)
	                array_push($errors, ['phoneNumber', 'Ingrese su número telefónico a 10 digitos']);
	            else if (isset($phoneNumber) AND $phoneType == 'Local' AND isset($phoneNumber) AND strlen($phoneNumber) != 7)
	                array_push($errors, ['phoneNumber', 'Ingrese su número telefónico a 7 digitos']);
	            else if (isset($phoneNumber) AND Security::checkIsFloat($phoneNumber) == true)
	                array_push($errors, ['phoneNumber', 'No ingrese números decimales']);
				else if (isset($phoneNumber) AND Security::checkIfExistSpaces($phoneNumber) == true)
					array_push($errors, ['phoneNumber', 'No ingrese espacios']);

				if (isset($phoneType) AND $phoneType != 'Local' AND isset($phoneType) AND $phoneType != 'Móvil')
	                array_push($errors, ['phoneType', 'Opción no válida']);

				if (empty($errors))
				{
                    if (isset($phoneNumber))
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
                        $query = $this->model->newContact($name, $email, $phoneNumber, $contact, $category);
                    else if ($action == 'edit')
                        $query = $this->model->editContact($id, $name, $email, $phoneNumber, $contact, $category);

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
			{
				define('_title', '{$lang.title}');

				$template = $this->view->render($this, 'index');
				$template = $this->format->replaceFile($template, 'header');

				$contacts = $this->model->getAllContacts();
				$countries = $this->model->getAllCountries();
                $clients = $this->model->getAllClients();
                $categories = $this->model->getAllCategories();

				$btnDeleteContacts = '';
                $btnCategories = '';
				$lstContacts = '';
				$lstCountriesPhoneCodes = '';
                $lstClients = '';
                $lstCategories = '';
				$mdlDeleteContacts = '';

				if (Session::getValue('level') >= 9)
				{
                    $btnDeleteContacts .=
					'<a data-button-modal="deleteContacts"><i class="material-icons">delete</i><span>Eliminar</span></a>';

                    $btnCategories .=
					'<a href="/directory/categories"><i class="material-icons">turned_in</i><span>Lista de categorías</span></a>';
				}

				foreach ($contacts as $contact)
				{
                    if (isset($contact['phone_number']) AND !empty($contact['phone_number']))
                    {
                        $phoneNumber = json_decode($contact['phone_number'], true);
                        $phoneNumber = $phoneNumber['type'] . '. (+' . $phoneNumber['country_code'] . ') ' . $phoneNumber['number'];
                    }
                    else
                        $phoneNumber = '- - -';

                    if (isset($contact['id_client']) AND !empty($contact['id_client']))
                    {
                        $client = $this->model->getClientById($contact['id_client']);
                        $client = $client['name'];
                    }
                    else
                        $client = '- - -';

                    if (isset($contact['id_contact_category']) AND !empty($contact['id_contact_category']))
                    {
                        $category = $this->model->getCategoryById($contact['id_contact_category']);
                        $category = $category['name'];
                    }
                    else
                        $category = '- - -';

					$lstContacts .=
					'<tr>
						<td><input type="checkbox" data-check value="' . $contact['id_contact'] . '" /></td>
						<td>' . $contact['name'] . '</td>
						<td>' . ((isset($contact['email']) AND !empty($contact['email'])) ? $contact['email'] : '- - -') . '</td>
						<td>' . $phoneNumber . '</td>
						<td>' . $client . '</td>
						<td>' . $category . '</td>
						<td><a data-action="getContactToEdit" data-id="' . $contact['id_contact'] . '"><i class="material-icons">edit</i><span>Detalles / Editar</span></a></td>
					</tr>';
				}

				foreach ($countries as $country)
				{
					$lstCountriesPhoneCodes .=
					'<option value="' . $country['phone_code'] . '" ' . (($country['phone_code'] == '52') ? 'selected' : '') . '>' . '[+' . $country['phone_code'] . '] ' . $country['name'] . '</option>';
				}

				foreach ($clients as $client)
				{
					$lstClients .=
					'<option value="' . $client['id_client'] . '">' . $client['name'] . '</option>';
				}

				foreach ($categories as $category)
				{
					$lstCategories .=
					'<option value="' . $category['id_contact_category'] . '">' . $category['name'] . '</option>';
				}

				if (Session::getValue('level') >= 9)
				{
					$mdlDeleteContacts .=
					'<section class="modal alert" data-modal="deleteContacts">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea eliminar está selección de contactos?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="deleteContacts">Aceptar</a>
					        </footer>
					    </div>
					</section>';
				}

				$replace = [
					'{$btnDeleteContacts}' => $btnDeleteContacts,
                    '{$btnCategories}' => $btnCategories,
					'{$lstContacts}' => $lstContacts,
					'{$lstCountriesPhoneCodes}' => $lstCountriesPhoneCodes,
					'{$lstClients}' => $lstClients,
					'{$lstCategories}' => $lstCategories,
					'{$mdlDeleteContacts}' => $mdlDeleteContacts
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener contacto para editar
	--------------------------------------------------------------------------- */
	public function getContactToEdit($id)
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$contact = $this->model->getContactById($id);

	            if (!empty($contact))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $contact
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Eliminar selección de contactos
	--------------------------------------------------------------------------- */
	public function deleteContacts()
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$query = $this->model->deleteContacts($selection);

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

    /* Lista de categorías, nuevo y editar categoría
	--------------------------------------------------------------------------- */
	public function categories()
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action = $_POST['action'];
				$id = ($action == 'edit') ? $_POST['id'] : null;

				$name = (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;

				$errors = [];

				if (!isset($name))
					array_push($errors, ['name', 'Ingrese el nombre de la categoría']);

				if (empty($errors))
	            {
	                $exist = $this->model->checkExistCategory($id, $name, $action);

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
							$query = $this->model->newCategory($name);
						else if ($action == 'edit')
							$query = $this->model->editCategory($id, $name);

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

				$template = $this->view->render($this, 'categories');
				$template = $this->format->replaceFile($template, 'header');

				$categories = $this->model->getAllCategories();

				$lstCategories = '';

				foreach ($categories as $category)
				{

					$lstCategories .=
					'<tr>
						<td><input type="checkbox" data-check value="' . $category['id_contact_category'] . '" /></td>
						<td>' . $category['name'] . '</td>
						<td><a href="" data-action="getCategoryToEdit" data-id="' . $category['id_contact_category'] . '"><i class="material-icons">edit</i><span>Detalles / Editar</span></a></td>
					</tr>';
				}

				$replace = [
					'{$lstCategories}' => $lstCategories
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener categoría para editar
	--------------------------------------------------------------------------- */
	public function getCategoryToEdit($id)
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$category = $this->model->getCategoryById($id);

	            if (!empty($category))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $category
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Eliminar selección de categorías
	--------------------------------------------------------------------------- */
	public function deleteCategories()
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$deleteCategories = $this->model->deleteCategories($selection);

					if (!empty($deleteCategories))
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
