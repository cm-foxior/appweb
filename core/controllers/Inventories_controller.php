<?php

defined('_EXEC') or die;

class Inventories_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Lista de inventarios, crear y editar inventario
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Session::getValue('level') >= 8)
		{
			$userLogged = $this->model->getUserLogged();

			if (Format::existAjaxRequest() == true)
			{
				$action	= $_POST['action'];
				$id		= ($action == 'edit') ? $_POST['id'] : null;

				$name = (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
				$type = (isset($_POST['type']) AND !empty($_POST['type'])) ? $_POST['type'] : null;

				if (Session::getValue('level') == 10)
					$branchOffice = (isset($_POST['branchOffice']) AND !empty($_POST['branchOffice'])) ? $_POST['branchOffice'] : null;
				else if (Session::getValue('level') == 8 OR Session::getValue('level') == 9)
					$branchOffice = $userLogged['id_branch_office'];

				$errors = [];

	            if (!isset($name))
	                array_push($errors, ['name', 'No deje este campo vacío']);

				if (!isset($type))
	                array_push($errors, ['type', 'Seleccione una opción']);
	            else if ($type != '1' AND $type != '2' AND $type != '3')
	                array_push($errors, ['type', 'Opción no válida']);

	            if (Session::getValue('level') == 10 AND !isset($branchOffice))
	                array_push($errors, ['branchOffice', 'Seleccione una opción']);

				if (empty($errors))
				{
					$exist = $this->model->checkExistInventory($id, $name, $type, $branchOffice, $action);

					if ($exist == true)
					{
						array_push($errors, ['name', 'Este registro ya existe']);
						array_push($errors, ['type', 'Este registro ya existe']);
						array_push($errors, ['branchOffice', 'Este registro ya existe']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
					}
					else
					{
						if ($action == 'new')
							$query = $this->model->newInventory($name, $type, $branchOffice);
						else if ($action == 'edit')
							$query = $this->model->editInventory($id, $name, $type, $branchOffice);

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

				if (Session::getValue('level') == 10)
				{
					$inventories = $this->model->getAllInventories();
					$branchOffices = $this->model->getAllBranchOffices();
				}
				else if (Session::getValue('level') == 8 OR Session::getValue('level') == 9)
					$inventories = $this->model->getAllInventoriesByBranchOffice($userLogged['id_branch_office']);

				$buttons = '';
				$tblInventories = '';
				$mdlInventories = '';
				$mdlActivateInventories = '';
				$mdlDeactivateInventories = '';
				$mdlDeleteInventories = '';

				$buttons .=
				'<div class="box-buttons">';

				if (Session::getValue('level') >= 9)
				{
					$buttons .=
					'<a data-button-modal="deleteInventories"><i class="material-icons">delete</i><span>Eliminar</span></a>';
				}

				$buttons .=
				'<a data-button-modal="inventories"><i class="material-icons">add</i><span>Nuevo</span></a>';

				// if (Session::getValue('level') >= 9)
				// {
				// 	$buttons .=
				// 	'<a data-button-modal="deactivateInventories"><i class="material-icons">block</i><span>Desactivar</span></a>
		        //     <a data-button-modal="activateInventories"><i class="material-icons">check</i><span>Activar</span></a>';
				// }

				$buttons .=
				'	<div class="clear"></div>
				</div>';

				$tblInventories .=
				'<table id="inventoriesTable" class="display" data-page-length="100" data-table-order="' . ((Session::getValue('level') >= 9) ? '2' : '1') . '">
	                <thead>
	                    <tr>
	                        ' . ((Session::getValue('level') >= 9) ? '<th width="20px"></th>' : '') . '
	                        <th>Nombre</th>
	                        <th>Tipo</th>
	                        ' . ((Session::getValue('level') == 10) ? '<th>Sucursal</th>' : '') . '
	                        <th>Registro</th>
	                        <th width="100px">Estado</th>
	                        <th width="140px"></th>
	                    </tr>
	                </thead>
	                <tbody>';

				foreach ($inventories as $inventory)
				{
					$inputs = $this->model->getAllInputs($inventory['id_inventory']);
					$outputs = $this->model->getAllOutputs($inventory['id_inventory']);

					if ($inventory['type'] == '1')
						$type = 'Venta';
					else if ($inventory['type'] == '2')
						$type = 'Producción';
					else if ($inventory['type'] == '3')
						$type = 'Operación';

					if (Session::getValue('level') == 10)
						$branchOffice = $this->model->getBranchOfficeById($inventory['id_branch_office']);

					$tblInventories .=
					'<tr>
						' . ((Session::getValue('level') >= 9) ? '<td><input type="checkbox" data-check value="' . $inventory['id_inventory'] . '" /></td>' : '') . '
						<td>' . $inventory['name'] . ((empty($inputs) AND empty($outputs)) ? '<span class="empty">Vacío</span>' : '') . '</td>
						<td>' . $type . '</td>
						' . ((Session::getValue('level') == 10) ? '<td>' . $branchOffice['name'] . '</td>' : '') . '
						<td>' . $inventory['registration_date'] . '</td>
						<td>' . (($inventory['status'] == true) ? '<span class="active">Activado</span>' : '<span class="deactive">Desctivado</span>') . '</td>
						<td>
							' . ((Session::getValue('level') >= 9) ? '<a ' . (($inventory['status'] == true) ? 'data-action="getInventoryToEdit" data-id="' . $inventory['id_inventory'] . '"' : 'disabled') . '><i class="material-icons">edit</i><span>Detalles / Editar</span></a>' : '') . '
							' . ((Session::getValue('level') >= 9) ? '<a ' . (($inventory['status'] == true) ? 'href="/inventories/loans/' . $inventory['id_inventory'] . '"' : 'disabled') . '><i class="material-icons">person</i><span>Prestamos</span></a>' : '') . '
							<a ' . (($inventory['status'] == true) ? 'href="/inventories/stocks/' . $inventory['id_inventory'] . '"' : 'disabled') . '><i class="material-icons">timeline</i><span>Stocks</span></a>
							<a ' . (($inventory['status'] == true) ? 'href="/inventories/inputs/' . $inventory['id_inventory'] . '"' : 'disabled') . '><i class="material-icons">format_list_bulleted</i><span>Entradas / Salidas</span></a>
						</td>
					</tr>';
				}

				$tblInventories .=
	            '    </tbody>
	            </table>';

				if (Session::getValue('level') >= 8)
				{
					$mdlInventories .=
					'<section class="modal" data-modal="inventories">
					    <div class="content">
					        <header>
					            <h6>Nuevo inventario</h6>
					        </header>
					        <main>
					            <form name="inventories" data-submit-action="new">
									<fieldset class="input-group">
										<p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
									</fieldset>
									<fieldset class="input-group">
					                    <label data-important>
					                        <span><span class="required-field">*</span>Nombre</span>
					                        <input type="text" name="name" autofocus>
					                    </label>
					                </fieldset>
					                <fieldset class="input-group">
					                    <label data-important>
					                        <span><span class="required-field">*</span>Tipo</span>
					                        <select name="type">
					                            <option value="1">Venta</option>
					                            <option value="2">Producción</option>
					                            <option value="3">Operación</option>
					                        </select>
					                    </label>
					                </fieldset>';

					if (Session::getValue('level') == 10)
					{
						$mdlInventories .=
						'<fieldset class="input-group">
							<label data-important>
								<span><span class="required-field">*</span>Sucursal</span>
								<select name="branchOffice" class="chosen-select">';

						foreach ($branchOffices as $branchOffice)
							$mdlInventories .= '<option value="' . $branchOffice['id_branch_office'] . '">' . $branchOffice['name'] . '</option>';

						$mdlInventories .=
						'		</select>
							</label>
						</fieldset>';
					}

					$mdlInventories .=
					'            </form>
					        </main>
					        <footer>
					            <a button-cancel>Cancelar</a>
					            <a button-success>Aceptar</a>
					        </footer>
					    </div>
					</section>';
				}

				if (Session::getValue('level') >= 9)
				{
					$mdlActivateInventories .=
					'<section class="modal alert" data-modal="activateInventories">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea activar está selección de inventarios?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="activateInventories">Aceptar</a>
					        </footer>
					    </div>
					</section>';

					$mdlDeactivateInventories .=
					'<section class="modal alert" data-modal="deactivateInventories">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea desactivar está selección de inventarios?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="deactivateInventories">Aceptar</a>
					        </footer>
					    </div>
					</section>';

					$mdlDeleteInventories .=
					'<section class="modal alert" data-modal="deleteInventories">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea eliminar está selección de inventarios?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="deleteInventories">Aceptar</a>
					        </footer>
					    </div>
					</section>';
				}

				$replace = [
					'{$buttons}' => $buttons,
					'{$tblInventories}' => $tblInventories,
					'{$mdlInventories}' => $mdlInventories,
					'{$mdlActivateInventories}' => $mdlActivateInventories,
					'{$mdlDeactivateInventories}' => $mdlDeactivateInventories,
					'{$mdlDeleteInventories}' => $mdlDeleteInventories
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener inventario para editar
	--------------------------------------------------------------------------- */
	public function getInventoryToEdit($id)
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$inventory = $this->model->getInventoryById($id);

	            if (!empty($inventory))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $inventory
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Activar y desactivar selección de inventarios
	--------------------------------------------------------------------------- */
	public function changeStatusInventories($action)
	{
		if (Session::getValue('level') >= 9)
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

					$query = $this->model->changeStatusInventories($selection, $status);

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

	/* Eliminar selección de inventarios
	--------------------------------------------------------------------------- */
	public function deleteInventories()
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$query = $this->model->deleteInventories($selection);

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

    /* Lista de entradas al inventario, crear entrada al inventario
	--------------------------------------------------------------------------- */
	public function inputs($id)
	{
		if (Session::getValue('level') >= 8)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action	= $_POST['action'];
				$id		= ($action == 'edit') ? $_POST['id'] : $id;

				$product     = (isset($_POST['product']) AND !empty($_POST['product'])) ? $_POST['product'] : null;
				$quantify    = (isset($_POST['quantify']) AND !empty($_POST['quantify'])) ? $_POST['quantify'] : null;
				$type		 = (isset($_POST['type']) AND !empty($_POST['type'])) ? $_POST['type'] : null;
				$price		 = (isset($_POST['price']) AND !empty($_POST['price'])) ? $_POST['price'] : null;
				$bill		 = (isset($_POST['bill']) AND !empty($_POST['bill'])) ? $_POST['bill'] : null;
				$provider    = (isset($_POST['provider']) AND !empty($_POST['provider'])) ? $_POST['provider'] : null;

				if (Session::getValue('level') == 10)
				{
					$date = (isset($_POST['date']) AND !empty($_POST['date'])) ? $_POST['date'] : null;
					$hour = (isset($_POST['hour']) AND !empty($_POST['hour'])) ? $_POST['hour'] : null;
				}
				else
				{
					$date = null;
					$hour = null;
				}

				$errors = [];

	            if (!isset($product))
	                array_push($errors, ['product', 'Seleccione una opción']);

				if (!isset($quantify))
	                array_push($errors, ['quantify', 'No deje este campo vacío']);
	            else if (!is_numeric($quantify))
	                array_push($errors, ['quantify', 'Ingrese únicamente números']);
	            else if ($quantify < 0)
	                array_push($errors, ['quantify', 'No ingrese números negativos']);

	            if (!isset($type))
	                array_push($errors, ['type', 'Seleccione una opción']);
	            else if ($type != '1' AND $type != '3')
	                array_push($errors, ['type', 'Opción no válida']);

				if (Session::getValue('level') == 10)
				{
					if (!isset($date))
		                array_push($errors, ['date', 'No deje este campo vacío']);

					if (!isset($hour))
		                array_push($errors, ['hour', 'No deje este campo vacío']);
				}

				if (empty($errors))
				{
					if ($action == 'new')
						$query = $this->model->newInput($product, $quantify, $type, $price, $bill, $provider, $date . ' ' . $hour, $id);
					else if ($action == 'edit')
						$query = $this->model->editInput($id, $product, $quantify, $type, $price, $bill, $provider, $date . ' ' . $hour);

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
	            $inventory = $this->model->getInventoryById($id);
				$userLogged = $this->model->getUserLogged();

				if (Session::getValue('level') == 8 OR Session::getValue('level') == 9)
				{
					if ($inventory['id_branch_office'] == $userLogged['id_branch_office'])
						$inventoryLogged = true;
					else
						$inventoryLogged = false;
				}
				else if (Session::getValue('level') == 10)
					$inventoryLogged = true;

	            if ($inventory['status'] == true AND $inventoryLogged == true)
	            {
	                define('_title', '{$lang.title} | Dashboard');

	    			$template = $this->view->render($this, 'inputs');
	    			$template = $this->format->replaceFile($template, 'header');

	    			$inputs = $this->model->getAllInputs($id);
	                $products = $this->model->getAllProducts($inventory['type']);
	                $providers = $this->model->getAllProviders();

	    			$lstInputs = '';
	                $lstProducts = '';
	                $lstProviders = '';

	    			foreach ($inputs as $input)
	    			{
	                    $product = $this->model->getProductById($input['id_product']);
	                    $provider = $this->model->getProviderById($input['id_provider']);

	                    if ($product['unity'] == '1')
	                        $unity = 'Kilogramos';
	                    else if ($product['unity'] == '2')
	                        $unity = 'Gramos';
	                    else if ($product['unity'] == '3')
	                        $unity = 'Mililitros';
	                    else if ($product['unity'] == '4')
	                        $unity = 'Litros';
	                    else if ($product['unity'] == '5')
	                        $unity = 'Piezas';

						if ($input['type'] == '1')
						{
							$type = 'Compra';
							$typeStyleColor = '#000';
						}
						else if ($input['type'] == '2')
						{
							$type = 'Transferencia';
							$typeStyleColor = '#3f51b5';
						}
						else if ($input['type'] == '3')
						{
							$type = 'Devolución de venta';
							$typeStyleColor = '#2e7d32';
						}
						else if ($input['type'] == '4')
						{
							$type = 'Prestamo';
							$typeStyleColor = '#2e7d32';
						}

						$btnEditInput = '';

						if (Session::getValue('level') >= 9 AND $input['type'] == '1' OR Session::getValue('level') >= 9 AND $input['type'] == '3')
							$btnEditInput = '<a data-action="getInputToEdit" data-id="' . $input['id_inventory_input'] . '"><i class="material-icons">edit</i><span>Detalles / Editar</span></a>';

						// if ($input['type'] == '2' OR $input['type'] == '4')
						// 	$btnEditInput = '<a data-action="getInputTransferInfo" data-id="' . $input['id_inventory_input'] . '"><i class="material-icons">info_outline</i><span>Detalles</span></a>';

	    				$lstInputs .=
	    				'<tr style="color:' . $typeStyleColor . ';">
							<td>' . $input['input_date_time'] . '</td>
	    					<td>[' . $product['folio'] . '] ' . $product['name'] . (!empty($product['category_one']) ? ' - ' . $product['category_one'] : '')  . (!empty($product['category_two']) ? ' - ' . $product['category_two'] : '')  . (!empty($product['category_tree']) ? ' - ' . $product['category_tree'] : '')  . (!empty($product['category_four']) ? ' - ' . $product['category_four'] : '') . '</td>
	    					<td>' . $input['quantify'] . ' ' . $unity . '</td>
							<td>' . $type . '</td>
	    					<td>' . ((!empty($input['price'])) ? '$ ' . $input['price'] . ' MXN' : '') . '</td>
	    					<td>' . ((!empty($input['bill'])) ? $input['bill'] : '') . '</td>
							<td>' . (!empty($provider['name']) ? $provider['name'] : '') . '</td>
	                        <td>' . $btnEditInput . '</td>
	    				</tr>';
	    			}

	                foreach ($products as $product)
						$lstProducts .= '<option value="' . $product['id_product'] . '">[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</option>';

	                foreach ($providers as $provider)
	                    $lstProviders .= '<option value="' . $provider['id_provider'] . '">' . $provider['name'] . '</option>';

	    			$replace = [
						'{$title}' => 'Inv: ' . $inventory['name'] . ', Suc: ' . $inventory['branch_office'],
	    				'{$lstInputs}' => $lstInputs,
	    				'{$lstProducts}' => $lstProducts,
	    				'{$lstProviders}' => $lstProviders,
						'{$idInventory}' => $id
	    			];

	    			$template = $this->format->replace($replace, $template);

	    			echo $template;
	            }
				else
					header('Location: /inventories');
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener entrada al inventario para editar
	--------------------------------------------------------------------------- */
	public function getInputToEdit($id)
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$input = $this->model->getInputById($id);

	            if (!empty($input))
	            {
					$input['input_date_time'] = explode(' ', $input['input_date_time']);

	                echo json_encode([
						'status' => 'success',
						'data' => $input
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener información de transferencia de entrrada al inventario
	--------------------------------------------------------------------------- */
	public function getInputTransferInfo($id)
	{
		if (Session::getValue('level') >= 8)
		{
			if (Format::existAjaxRequest() == true)
			{
				$input 		= $this->model->getInputById($id);
				$inventory	= $this->model->getInventoryById($input['id_inventory_transfer']);
				$branchOffice		= $this->model->getBranchOfficeById($inventory['id_branch_office']);

				$data = [
					'inventory' => $inventory['name'],
					'branchOffice' => $branchOffice['name']
				];

				echo json_encode([
					'status' => 'success',
					'data' => $data
				]);
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Lista de salidas del inventario, crear salida del inventario
	--------------------------------------------------------------------------- */
	public function outputs($id)
	{
		if (Session::getValue('level') >= 8)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action		= $_POST['action'];
				$idOutput	= ($action == 'edit') ? $_POST['id'] : null;

				$product    = (isset($_POST['product']) AND !empty($_POST['product'])) ? $_POST['product'] : null;
				$quantity	= (isset($_POST['quantity']) AND !empty($_POST['quantity'])) ? $_POST['quantity'] : null;
				$type		= (isset($_POST['type']) AND !empty($_POST['type'])) ? $_POST['type'] : null;

				if (Session::getValue('level') == 10)
				{
					$date = (isset($_POST['date']) AND !empty($_POST['date'])) ? $_POST['date'] : null;
					$hour = (isset($_POST['hour']) AND !empty($_POST['hour'])) ? $_POST['hour'] : null;
				}
				else
				{
					$date = null;
					$hour = null;
				}

				$errors = [];

	            if (!isset($product))
	                array_push($errors, ['product', 'Seleccione una opción']);

				if (!isset($quantity))
	                array_push($errors, ['quantity', 'No deje este campo vacío']);
	            else if (!is_numeric($quantity))
	                array_push($errors, ['quantity', 'Ingrese únicamente números']);
	            else if ($quantity < 0)
	                array_push($errors, ['quantity', 'No ingrese números negativos']);

	            if (!isset($type))
	                array_push($errors, ['type', 'Seleccione una opción']);
	            else if ($type != '2' AND $type != '3' AND $type != '4')
	                array_push($errors, ['type', 'Opción no válida']);

				if (Session::getValue('level') == 10)
				{
					if (!isset($date))
		                array_push($errors, ['date', 'No deje este campo vacío']);

					if (!isset($hour))
		                array_push($errors, ['hour', 'No deje este campo vacío']);
				}

				if (empty($errors))
				{
					$exist = $this->model->checkExistInInventory($product, $quantity, $id, $idOutput, $action);

					if ($exist['status'] == true)
					{
						if ($exist['errors']['errorNotExistInInventory'] == true)
							array_push($errors, ['product', 'No hay ninguna entrada al inventario con este producto']);

						if ($exist['errors']['errorExceed'] == true)
							array_push($errors, ['quantity', 'Ha exedido la cantidad que existe en el inventario']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
					}
					else
					{
						if ($action == 'new')
						{
							$query = $this->model->newOutput($product, $quantity, $type, $date . ' ' . $time, $id);

							if (empty($query))
							{
								echo json_encode([
									'status' => 'success'
								]);
							}
							else
							{
								foreach ($query as $value)
					                array_push($errors, ['product', $value]);

								echo json_encode([
									'status' => 'error',
									'labels' => $errors
								]);
							}
						}
						else if ($action == 'edit')
						{
							$query = $this->model->editOutput($idOutput, $product, $quantity, $type, $date . ' ' . $time);

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
	            $inventory = $this->model->getInventoryById($id);
				$userLogged = $this->model->getUserLogged();

				if (Session::getValue('level') == 8 OR Session::getValue('level') == 9)
				{
					if ($inventory['id_branch_office'] == $userLogged['id_branch_office'])
						$inventoryLogged = true;
					else
						$inventoryLogged = false;
				}
				else if (Session::getValue('level') == 10)
					$inventoryLogged = true;

	            if ($inventory['status'] == true AND $inventoryLogged == true)
	            {
	                define('_title', '{$lang.title} | Dashboard');

	    			$template = $this->view->render($this, 'outputs');
	    			$template = $this->format->replaceFile($template, 'header');

	    			$outputs = $this->model->getAllOutputs($id);
	                $products = $this->model->getAllProducts($inventory['type']);

					$btnTransferProduct = '';
	    			$lstOutputs = '';
	                $lstProducts = '';
					$mdlTransferProduct = '';

					if (Session::getValue('level') >= 9)
						$btnTransferProduct .= '<a data-button-modal="transferProduct"><i class="material-icons">reply</i><span>Transferir producto</span></a>';

	    			foreach ($outputs as $output)
	    			{
	                    $product = $this->model->getProductById($output['id_product']);

						if ($product['unity'] == '1')
	                        $unity = 'Kilogramos';
	                    else if ($product['unity'] == '2')
	                        $unity = 'Gramos';
	                    else if ($product['unity'] == '3')
	                        $unity = 'Mililitros';
	                    else if ($product['unity'] == '4')
	                        $unity = 'Litros';
	                    else if ($product['unity'] == '5')
	                        $unity = 'Piezas';

						if ($output['type'] == '1')
						{
							$type = 'Transferencia';
							$typeStyleColor = '#3f51b5';
						}
						else if ($output['type'] == '2')
						{
							$type = 'Merma / Perdida';
							$typeStyleColor = '#f44336';
						}
						else if ($output['type'] == '3')
						{
							$type = 'Devolución a proveedor';
							$typeStyleColor = '#000';
						}
						else if ($output['type'] == '4')
						{
							$type = 'Venta';
							$typeStyleColor = '#000';
						}
						else if ($output['type'] == '5')
						{
							$type = 'Cambio de venta';
							$typeStyleColor = '#000';
						}
						else if ($output['type'] == '6')
						{
							$type = 'Devolución de prestamo';
							$typeStyleColor = '#000';
						}

						$btnEditOutput = '';

						if (Session::getValue('level') >= 9 AND $output['type'] == '2' OR Session::getValue('level') >= 9 AND $output['type'] == '3')
							$btnEditOutput = '<a data-action="getOutputToEdit" data-id="' . $output['id_inventory_output'] . '"><i class="material-icons">edit</i><span>Detalles / Editar</span></a>';

						// if ($output['type'] == '1' OR $output['type'] == '6')
						// 	$btnEditOutput = '<a data-action="getOutputTransferInfo" data-id="' . $output['id_inventory_output'] . '"><i class="material-icons">info_outline</i><span>Detalles</span></a>';

	    				$lstOutputs .=
	    				'<tr style="color:' . $typeStyleColor . ';">
	    					<td>[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</td>
	    					<td>' . $output['quantity'] . ' ' . $unity . '</td>
	    					<td>' . $output['output_date_time'] . '</td>
	    					<td>' . $type . '</td>
	                        <td>' . $btnEditOutput . '</td>
	    				</tr>';
	    			}

	                foreach ($products as $product)
	                    $lstProducts .= '<option value="' . $product['id_product'] . '">[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</option>';

					if (Session::getValue('level') >= 9)
					{
						$mdlTransferProduct .=
						'<section class="modal" data-modal="transferProduct">
						    <div class="content">
						        <header>
						            <h6>Transferir producto</h6>
						        </header>
						        <main>
						            <form name="transferProduct" data-id="{$idInventory}">
						                <fieldset class="input-group">
						                    <label data-important>
						                        <span>Tipo de transferencia</span>
						                        <select name="typeTransfer" data-id="{$idInventory}">
						                            <option value="">Seleccione una opción</option>
						                            <option value="1">En la misma sucursal</option>
						                            <option value="2">De sucursal a sucursal</option>
						                        </select>
						                    </label>
						                </fieldset>
						                <fieldset class="input-group hidden">
						                    <label data-important>
						                        <span></span>
						                        <select name="filterTransfer" class="chosen-select"></select>
						                    </label>
						                </fieldset>
						                <fieldset class="input-group hidden">
						                    <label data-important>
						                        <span>Inventario destino</span>
						                        <select name="filterInventory" class="chosen-select"></select>
						                    </label>
						                </fieldset>
						                <fieldset class="input-group hidden">
						                    <label data-important>
						                        <span>Producto</span>
						                        <select name="product" class="chosen-select">
						                            <option value="">Seleccione una opción</option>';

						foreach ($products as $product)
		                    $mdlTransferProduct .= '<option value="' . $product['id_product'] . '">[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</option>';

						$mdlTransferProduct .=
						'                        </select>
						                    </label>
						                </fieldset>
						                <fieldset class="input-group hidden">
						                    <label data-important>
						                        <span>Cantidad</span>
						                        <input type="number" name="quantity">
						                    </label>
						                </fieldset>
						            </form>
						        </main>
						        <footer>
						            <a button-cancel>Cancelar</a>
						            <a button-success>Aceptar</a>
						        </footer>
						    </div>
						</section>';
					}

					$replace = [
						'{$title}' => 'Inv: ' . $inventory['name'] . ', Suc: ' . $inventory['branch_office'],
						'{$btnTransferProduct}' => $btnTransferProduct,
	    				'{$lstOutputs}' => $lstOutputs,
	    				'{$lstProducts}' => $lstProducts,
						'{$mdlTransferProduct}' => $mdlTransferProduct,
						'{$idInventory}' => $id
	    			];

	    			$template = $this->format->replace($replace, $template);

	    			echo $template;
	            }
				else
					header('Location: /inventories');
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener salida del inventario para editar
	--------------------------------------------------------------------------- */
	public function getOutputToEdit($id)
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$output = $this->model->getOutputById($id);

				if (!empty($output))
				{
					echo json_encode([
						'status' => 'success',
						'data' => $output
					]);
				}
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener opciones de filtrado para transferir un producto
	--------------------------------------------------------------------------- */
	public function getFilterTransfer()
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$data = [];

				$filter = (isset($_POST['filter']) AND !empty($_POST['filter'])) ? $_POST['filter'] : null;
				$id		= (isset($_POST['id']) AND !empty($_POST['id'])) ? $_POST['id'] : null; // id del inventario del cual se va a hacer la transferencia

				$inventory = $this->model->getInventoryById($id);

				if ($filter == '1')
				{
					$inventories = $this->model->getAllInventoriesByBranchOffice2($inventory['id_branch_office']);

					foreach ($inventories as $value)
					{
						if ($value['id_inventory'] != $inventory['id_inventory'] AND $value['type'] == $inventory['type'])
							array_push($data, $value);
					}
				}
				else if ($filter == '2')
				{
					$branchOffices = $this->model->getAllBranchOffices();

					foreach ($branchOffices as $value)
					{
						if ($value['id_branch_office'] != $inventory['id_branch_office'])
							array_push($data, $value);
					}
				}

				echo json_encode([
					'status' => 'success',
					'data' => $data
				]);
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener filtrado de inventarios
	--------------------------------------------------------------------------- */
	public function getFilterInventories()
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$data = [];

				$idBranchOffice	= (isset($_POST['idBranchOffice']) AND !empty($_POST['idBranchOffice'])) ? $_POST['idBranchOffice'] : null; // id de la sucursal seleccionada
				$idInventory 	= (isset($_POST['idInventory']) AND !empty($_POST['idInventory'])) ? $_POST['idInventory'] : null; // id del inventario del cual se va a hacer la transferencia

				$inventory	 = $this->model->getInventoryById($idInventory); // inventario del cual se va a hacer la transeferencia
				$inventories = $this->model->getAllInventoriesByBranchOffice2($idBranchOffice);

				foreach ($inventories as $value)
				{
					if ($value['type'] == $inventory['type'])
						array_push($data, $value);
				}

				echo json_encode([
					'status' => 'success',
					'data' => $data
				]);
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Transferir producto
	--------------------------------------------------------------------------- */
	public function transferProduct()
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$typeTransfer 		= (isset($_POST['typeTransfer']) AND !empty($_POST['typeTransfer'])) ? $_POST['typeTransfer'] : null;
				$filterTransfer 	= (isset($_POST['filterTransfer']) AND !empty($_POST['filterTransfer'])) ? $_POST['filterTransfer'] : null;
				$filterInventory	= (isset($_POST['filterInventory']) AND !empty($_POST['filterInventory'])) ? $_POST['filterInventory'] : null;
				$product 			= (isset($_POST['product']) AND !empty($_POST['product'])) ? $_POST['product'] : null;
				$quantity 			= (isset($_POST['quantity']) AND !empty($_POST['quantity'])) ? $_POST['quantity'] : null;
				$idInventory		= (isset($_POST['idInventory']) AND !empty($_POST['idInventory'])) ? $_POST['idInventory'] : null;

				$errors = [];

				if (!isset($typeTransfer))
					array_push($errors, ['typeTransfer', 'Seleccione una opción']);
				else if ($typeTransfer != '1' AND $typeTransfer != '2')
					array_push($errors, ['typeTransfer', 'Opción no válida']);

				if (!isset($filterTransfer))
					array_push($errors, ['filterTransfer', 'Seleccione una opción']);

				if ($typeTransfer == '2' AND !isset($filterInventory))
					array_push($errors, ['filterInventory', 'Seleccione una opción']);

				if (!isset($product))
					array_push($errors, ['product', 'Seleccione una opción']);

				if (!isset($quantity))
					array_push($errors, ['quantity', 'No deje este campo vacío']);
				else if (!is_numeric($quantity))
					array_push($errors, ['quantity', 'Ingrese únicamente números']);
				else if ($quantity < 0)
					array_push($errors, ['quantity', 'No ingrese números negativos']);

				if (empty($errors))
				{
					$exist = $this->model->checkExistInInventory($product, $quantity, $idInventory, null, 'new');

					if ($exist['status'] == true)
					{
						if ($exist['errors']['errorNotExistInInventory'] == true)
							array_push($errors, ['product', 'No hay ninguna entrada al inventario con este producto']);

						if ($exist['errors']['errorExceed'] == true)
							array_push($errors, ['quantity', 'Ha exedido la cantidad que existe en el inventario']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
					}
					else
					{
						if ($typeTransfer == '1')
							$idTransferInventory = $filterTransfer;
						else if ($typeTransfer == '2')
							$idTransferInventory = $filterInventory;

						$query = $this->model->transferProduct($product, $quantity, $idInventory, $idTransferInventory);

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
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener información de transferencia de salida del inventario
	--------------------------------------------------------------------------- */
	public function getOutputTransferInfo($id)
	{
		if (Session::getValue('level') >= 8)
		{
			if (Format::existAjaxRequest() == true)
			{
				$output = $this->model->getOutputById($id);
				$inventory = $this->model->getInventoryById($output['id_inventory_transfer']);
				$branchOffice = $this->model->getBranchOfficeById($inventory['id_branch_office']);

				$data = [
					'inventory' => $inventory['name'],
					'branchOffice' => $branchOffice['name']
				];

				echo json_encode([
					'status' => 'success',
					'data' => $data
				]);
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Lista de stocks de productos por inventario
	--------------------------------------------------------------------------- */
	public function stocks($idInventory)
	{
		if (Session::getValue('level') >= 8)
		{
            if (Format::existAjaxRequest() == true)
            {
                $action		= $_POST['action'];
				$idStock	= ($action == 'edit') ? $_POST['id'] : null;

				$min 		= (isset($_POST['min']) AND !empty($_POST['min'])) ? $_POST['min'] : null;
				$max 		= (isset($_POST['max']) AND !empty($_POST['max'])) ? $_POST['max'] : null;
				$idProduct	= (isset($_POST['product']) AND !empty($_POST['product'])) ? $_POST['product'] : null;

                $errors = [];

                if (!isset($min))
	                array_push($errors, ['min', 'No deje este campo vacío']);
	            else if (!is_numeric($min))
	                array_push($errors, ['min', 'Ingrese únicamente números']);
	            else if ($min < 1)
	                array_push($errors, ['min', 'Ingerese mínimo una unidad']);

				if (isset($max) AND !is_numeric($max))
					array_push($errors, ['max', 'Ingrese únicamente números']);
				else if (isset($max) AND $max <= $min)
					array_push($errors, ['max', 'El máximo no puede menor o igual al mínimo']);

                if (!isset($idProduct))
                    array_push($errors, ['product', 'Seleccione una opción']);

                if (empty($errors))
                {
                    $exist = $this->model->checkExistStock($idStock, $idProduct, $idInventory, $action);

                    if ($exist == true)
					{
                        array_push($errors, ['product', 'Este registro ya existe']);
                        array_push($errors, ['inventory', 'Este registro ya existe']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
                    }
                    else
					{
						if ($action == 'new')
							$query = $this->model->newStock($min, $max, $idProduct, $idInventory);
						else if ($action == 'edit')
							$query = $this->model->editStock($idStock, $min, $max, $idProduct);

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
				$inventory = $this->model->getInventoryById($idInventory);
				$userLogged = $this->model->getUserLogged();

				if (Session::getValue('level') == 8 OR Session::getValue('level') == 9)
				{
					if ($inventory['id_branch_office'] == $userLogged['id_branch_office'])
						$inventoryLogged = true;
					else
						$inventoryLogged = false;
				}
				else if (Session::getValue('level') == 10)
					$inventoryLogged = true;

	            if ($inventory['status'] == true AND $inventoryLogged == true)
	            {
	                define('_title', '{$lang.title} | Dashboard');

	        		$template = $this->view->render($this, 'stocks');
	        		$template = $this->format->replaceFile($template, 'header');

					$stocks = $this->model->getAllStocksByInventory($inventory['id_inventory']);
					$products = $this->model->getAllProducts($inventory['type']);

					$btnDeleteStocks = '';
					$tblStocks = '';
	                $lstProducts = '';
					$mdlDeleteStocks = '';

					if (Session::getValue('level') >= 9)
						$btnDeleteStocks .= '<a data-button-modal="deleteStocks"><i class="material-icons">delete</i><span>Eliminar</span></a>';

					$tblStocks .=
					'<table id="stocksTable" class="display" data-page-length="100">
		                <thead>
		                    <tr>
		                        ' . ((Session::getValue('level') >= 9) ? '<th width="20px"></th>' : '') . '
		                        <th>Producto</th>
		                        <th width="100px">Mínimo</th>
		                        <th width="100px">Máximo</th>
		                        <th width="100px">Actual</th>
		                        <th width="100px">Estado</th>
		                        ' . ((Session::getValue('level') >= 9) ? '<th width="35px"></th>' : '') . '
		                    </tr>
		                </thead>
		                <tbody>';

	                foreach ($stocks as $stock)
					{
	                    $product = $this->model->getProductById($stock['id_product']);
						$currentStock = $this->model->getCurrentStock($product['id_product'], $inventory['id_inventory']);

						if ($product['unity'] == '1')
							$unity = 'Kilogramos';
						else if ($product['unity'] == '2')
							$unity = 'Gramos';
						else if ($product['unity'] == '3')
							$unity = 'Mililitros';
						else if ($product['unity'] == '4')
							$unity = 'Litros';
						else if ($product['unity'] == '5')
							$unity = 'Piezas';

						if ($currentStock > $stock['min'])
							$status = '<span class="stable">Estable</span>';
						else if ($currentStock == $stock['min'])
							$status = '<span class="same">Igualdad</span>';
						else if ($currentStock < $stock['min'])
							$status = '<span class="missing">Faltante</span>';

	                    $tblStocks .=
	                    '<tr>
							' . ((Session::getValue('level') >= 9) ? '<td><input type="checkbox" data-check value="' . $stock['id_inventory_stock'] . '" /></td>' : '') . '
							<td>[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</td>
	                        <td>' . $stock['min'] . ' ' . $unity . '</td>
	                        <td>' . (!empty($stock['max']) ? $stock['max'] . ' ' . $unity : '-') . '</td>
	                        <td>' . $currentStock . ' ' . $unity . '</td>
	                        <td>' . $status . '</td>
							' . ((Session::getValue('level') >= 9) ? '<td><a data-action="getStockToEdit" data-id="' . $stock['id_inventory_stock'] . '"><i class="material-icons">edit</i><span>Detalles / Editar</span></a></td>' : '') . '
	                    </tr>';
					}

					$tblStocks .=
					'	</tbody>
					</table>';

	                foreach ($products as $product)
	                    $lstProducts .= '<option value="' . $product['id_product'] . '">[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</option>';

					if (Session::getValue('level') >= 9)
					{
						$mdlDeleteStocks .=
						'<section class="modal alert" data-modal="deleteStocks">
						    <div class="content">
						        <header>
						            <h6>Alerta</h6>
						        </header>
						        <main>
						            <p>¿Está seguro de que desea eliminar este stock?</p>
						        </main>
						        <footer>
						            <a button-close>Cancelar</a>
						            <a data-action="deleteStocks">Aceptar</a>
						        </footer>
						    </div>
						</section>';
					}

	                $replace = [
						'{$title}' => 'Inv: ' . $inventory['name'] . ', Suc: ' . $inventory['branch_office'],
						'{$btnDeleteStocks}' => $btnDeleteStocks,
	                    '{$tblStocks}' => $tblStocks,
	                    '{$lstProducts}' => $lstProducts,
	                    '{$mdlDeleteStocks}' => $mdlDeleteStocks
	                ];

	                $template = $this->format->replace($replace, $template);

	        		echo $template;
				}
				else
					header('Location: /inventories');
            }
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener stock para editar
    --------------------------------------------------------------------------- */
    public function getStockToEdit($id)
    {
        if (Session::getValue('level') >= 9)
        {
            if (Format::existAjaxRequest() == true)
            {
                $stock = $this->model->getStockById($id);

                if (!empty($stock))
                {
                    echo json_encode([
                        'status' => 'success',
                        'data' => $stock
                    ]);
                }
            }
			else
				Errors::http('404');
        }
        else
            header('Location: /dashboard');
    }

    /* Eliminar selección de stocks
	--------------------------------------------------------------------------- */
	public function deleteStocks()
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$query = $this->model->deleteStocks($selection);

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

	/* Lista de prestamos
	--------------------------------------------------------------------------- */
	public function loans($idInventory)
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action = $_POST['action'];

				if ($action == 'open')
				{
					$product = (isset($_POST['product']) AND !empty($_POST['product'])) ? $_POST['product'] : null;
					$quantity = (isset($_POST['quantity']) AND !empty($_POST['quantity'])) ? $_POST['quantity'] : null;
					$client = (isset($_POST['client']) AND !empty($_POST['client'])) ? $_POST['client'] : null;
					$date = (isset($_POST['date']) AND !empty($_POST['date'])) ? $_POST['date'] : null;
					$time = (isset($_POST['time']) AND !empty($_POST['time'])) ? $_POST['time'] : null;
					$setDateTime = (isset($_POST['setDateTime']) AND !empty($_POST['setDateTime'])) ? true : false;

					$errors = [];

					if (!isset($product))
						array_push($errors, ['product', 'Seleccione una opción']);

					if (!isset($quantity))
						array_push($errors, ['quantity', 'No deje este campo vacío']);
					else if ($quantity < 0)
						array_push($errors, ['quantity', 'No ingrese números negativos']);

					if (!isset($client))
						array_push($errors, ['client', 'Seleccione una opción']);

					if (!isset($date) AND $setDateTime == true)
						array_push($errors, ['date', 'No deje este campo vacío']);

					if (!isset($time) AND $setDateTime == true)
						array_push($errors, ['time', 'No deje este campo vacío']);

					if (empty($errors))
					{
						$existence = $this->model->getExistence($product, $idInventory);

						if ($quantity <= $existence)
						{
							$loan = [
								'quantity' => $quantity,
								'datetime' => ($setDateTime == true) ? $date . ' ' . $time : Format::getDateHour(),
								'id_product' => $product,
								'id_inventory' => $idInventory,
								'id_client' => $client
							];

							$query = $this->model->newLoan($loan);

							if (!empty($query))
							{
								$query = $this->model->newInput($loan['id_product'], $loan['quantity'], 4, null, null, $loan['datetime'], $idInventory);

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
										'message' => 'DATABASE OPERATION ERROR'
									]);
								}
							}
							else
							{
								echo json_encode([
									'status' => 'error',
									'message' => 'DATABASE OPERATION ERROR'
								]);
							}
						}
						else
						{
							array_push($errors, ['product', 'No hay productos suficientes. Disponible: ' . $existence]);

							echo json_encode([
								'status' => 'error',
								'labels' => $errors
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

				if ($action == 'close')
				{
					$loan = (isset($_POST['id']) AND !empty($_POST['id'])) ? $_POST['id'] : null;

					$query = $this->model->closeLoan($loan);

					if (!empty($query))
					{
						$loan = $this->model->getLoan($loan);

						$query = $this->model->newOutput($loan['id_product'], $loan['quantity'], 6, null, $idInventory);

						if (empty($query))
						{
							echo json_encode([
								'status' => 'success'
							]);
						}
						else
						{
							echo json_encode([
								'status' => 'error',
								'message' => 'DATABASE OPERATION ERROR'
							]);
						}
					}
					else
					{
						echo json_encode([
							'status' => 'error',
							'message' => 'DATABASE OPERATION ERROR'
						]);
					}
				}
			}
			else
			{
				define('_title', '{$lang.title} | Dashboard');

				$template = $this->view->render($this, 'loans');
				$template = $this->format->replaceFile($template, 'header');

				$inventory = $this->model->getInventoryById($idInventory);
				$loans = $this->model->getLoans($idInventory);
				$products = $this->model->getProducts();
				$clients = $this->model->getClients();

				$lstLoans = '';
				$lstProducts = '';
				$lstClients = '';

				foreach ($loans as $loan)
				{
					if ($loan['unity'] == '1')
	                    $unity = 'Kilogramos';
	                else if ($loan['unity'] == '2')
	                    $unity = 'Gramos';
	                else if ($loan['unity'] == '3')
	                    $unity = 'Mililitros';
	                else if ($loan['unity'] == '4')
	                    $unity = 'Litros';
	                else if ($loan['unity'] == '5')
	                    $unity = 'Piezas';

					$lstLoans .=
					'<tr>
						<td>[' . $loan['folio'] . '] ' . $loan['product'] . ' ' . $loan['category_one'] . ' ' . $loan['category_two'] . ' ' . $loan['category_tree'] . ' ' . $loan['category_four'] . '</td>
						<td>' . $loan['quantity'] . ' ' . $unity . '</td>
						<td>' . $loan['datetime'] . '</td>
						<td>' . $loan['client'] . '</td>
						<td>' . (($loan['status'] == true) ? '<span class="active">Abierto</span>' : '<span class="expired">Cerrado</span>') . '</td>
						<td><a ' . (($loan['status'] == true) ? 'data-button-modal="closeLoan" data-id="' . $loan['id_loan'] . '"' : 'disabled') . '><i class="material-icons">close</i><span>Cerrar prestamo</span></a></td>
					</tr>';
				}

				foreach ($products as $product)
					$lstProducts .= '<option value="' . $product['id_product'] . '">[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</option>';

				foreach ($clients as $client)
					$lstClients .= '<option value="' . $client['id_client'] . '">' . $client['name'] . '</option>';

				$replace = [
					'{$title}' => 'Inv: ' . $inventory['name'] . ', Suc: ' . $inventory['branch_office'],
					'{$lstLoans}' => $lstLoans,
					'{$lstProducts}' => $lstProducts,
					'{$lstClients}' => $lstClients,
					'{$date}' => Format::getDate(),
					'{$time}' => Format::getTime(),
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}
}
