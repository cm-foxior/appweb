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
		if (Session::getValue('level') == 8 OR Session::getValue('level') == 10)
		{
			$userLogged = $this->model->getUserLogged();

			if (Format::existAjaxRequest() == true)
			{
				$action	= $_POST['action'];
				$id		= ($action == 'edit') ? $_POST['id'] : null;
				$name 	= (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
				$type 	= (isset($_POST['type']) AND !empty($_POST['type'])) ? $_POST['type'] : null;

				if (Session::getValue('level') == 10)
					$branchOffice = (isset($_POST['branchOffice']) AND !empty($_POST['branchOffice'])) ? $_POST['branchOffice'] : null;
				else if (Session::getValue('level') == 8)
					$branchOffice = $userLogged['id_branch_office'];

				$errors = [];

	            if (!isset($name))
	                array_push($errors, ['name', 'No deje este campo vacío']);

				if (!isset($type))
	                array_push($errors, ['type', 'Seleccione una opción']);

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
				else if (Session::getValue('level') == 8)
					$inventories = $this->model->getAllInventoriesByBranchOffice($userLogged['id_branch_office']);

				$buttons = '';
				$tblInventories = '';
				$mdlInventories = '';
				$mdlActivateInventories = '';
				$mdlDeactivateInventories = '';
				$mdlDeleteInventories = '';

				$buttons .=
				'<div class="box-buttons">';

				if (Session::getValue('level') == 10)
				{
					$buttons .=
					'<a data-button-modal="deleteInventories"><i class="material-icons">delete</i><span>Eliminar</span></a>
					<!-- <a data-button-modal="deactivateInventories"><i class="material-icons">block</i><span>Desactivar</span></a>
			    	<a data-button-modal="activateInventories"><i class="material-icons">check</i><span>Activar</span></a> -->';
				}

				$buttons .=
				'	<a data-button-modal="inventories"><i class="material-icons">add</i><span>Nuevo</span></a>
					<div class="clear"></div>
				</div>';

				$tblInventories .=
				'<table id="inventoriesTable" class="display" data-page-length="100" data-table-order="' . ((Session::getValue('level') == 10) ? '3' : '1') . '">
	                <thead>
	                    <tr>
	                        ' . ((Session::getValue('level') == 10) ? '<th width="20px"></th>' : '') . '
	                        <th>Nombre</th>
	                        ' . ((Session::getValue('level') == 10) ? '<th>Sucursal</th>' : '') . '
							<th width="100px">Tipo</th>
	                        <!-- <th width="100px">Estado</th> -->
	                        <th ' . ((Session::getValue('level') == 10) ? 'width="140px"' : 'width="105px"') . '></th>
	                    </tr>
	                </thead>
	                <tbody>';

				foreach ($inventories as $inventory)
				{
					$inputs = $this->model->getAllInputs($inventory['id_inventory']);
					$outputs = $this->model->getAllOutputs($inventory['id_inventory']);

					if (Session::getValue('level') == 10)
						$branchOffice = $this->model->getBranchOfficeById($inventory['id_branch_office']);

					if ($inventory['type'] == '1')
						$type = 'Venta';
					else if ($inventory['type'] == '2')
						$type = 'Producción';
					else if ($inventory['type'] == '3')
						$type = 'Operación';

					$tblInventories .=
					'<tr>
						' . ((Session::getValue('level') == 10) ? '<td><input type="checkbox" data-check value="' . $inventory['id_inventory'] . '" /></td>' : '') . '
						<td>' . $inventory['name'] . ((empty($inputs) AND empty($outputs)) ? '<span class="empty">Vacío</span>' : '') . '</td>
						' . ((Session::getValue('level') == 10) ? '<td>' . $branchOffice['name'] . '</td>' : '') . '
						<td>' . $type . '</td>
						<!-- <td>' . (($inventory['status'] == true) ? '<span class="active">Activado</span>' : '<span class="deactive">Desctivado</span>') . '</td> -->
						<td>
							' . ((Session::getValue('level') == 10) ? '<a ' . (($inventory['status'] == true) ? 'data-action="getInventoryToEdit" data-id="' . $inventory['id_inventory'] . '"' : 'disabled') . '><i class="material-icons">edit</i><span>Detalles / Editar</span></a>' : '') . '
							<a ' . (($inventory['status'] == true) ? 'href="/inventories/stocks/' . $inventory['id_inventory'] . '"' : 'disabled') . '><i class="material-icons">timeline</i><span>Stocks</span></a>
							<a ' . (($inventory['status'] == true) ? 'href="/inventories/outputs/' . $inventory['id_inventory'] . '"' : 'disabled') . '><i class="material-icons">arrow_downward</i><span>Salidas</span></a>
							<a ' . (($inventory['status'] == true) ? 'href="/inventories/inputs/' . $inventory['id_inventory'] . '"' : 'disabled') . '><i class="material-icons">arrow_upward</i><span>Entradas</span></a>
						</td>
					</tr>';
				}

				$tblInventories .=
	            '    </tbody>
	            </table>';

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
				                            <!-- <option value="2">Producción</option> -->
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

				if (Session::getValue('level') == 10)
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
		if (Session::getValue('level') == 10)
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
		if (Session::getValue('level') == 10)
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
		if (Session::getValue('level') == 8 OR Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action			= $_POST['action'];
				$id				= ($action == 'edit') ? $_POST['id'] : $id;
				$product     	= (isset($_POST['product']) AND !empty($_POST['product'])) ? $_POST['product'] : null;
				$quantify    	= (isset($_POST['quantify']) AND !empty($_POST['quantify'])) ? $_POST['quantify'] : null;
				$type		 	= (isset($_POST['type']) AND !empty($_POST['type'])) ? $_POST['type'] : null;
				$bill		 	= (isset($_POST['bill']) AND !empty($_POST['bill'])) ? $_POST['bill'] : null;
				$price		 	= (isset($_POST['price']) AND !empty($_POST['price'])) ? $_POST['price'] : null;
				$payment		= (isset($_POST['payment']) AND !empty($_POST['payment'])) ? $_POST['payment'] : null;
				$provider    	= (isset($_POST['provider']) AND !empty($_POST['provider'])) ? $_POST['provider'] : null;

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

	            if (!isset($type))
	                array_push($errors, ['type', 'Seleccione una opción']);

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
						$query = $this->model->newInput($product, $quantify, $type, $bill, $price, $payment, $provider, $date . ' ' . $hour, $id);
					else if ($action == 'edit')
						$query = $this->model->editInput($id, $product, $quantify, $type, $bill, $price, $payment, $provider, $date . ' ' . $hour);

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

				if (Session::getValue('level') == 10)
					$inventoryLogged = true;
				else if (Session::getValue('level') == 8)
				{
					if ($inventory['id_branch_office'] == $userLogged['id_branch_office'])
						$inventoryLogged = true;
					else
						$inventoryLogged = false;
				}

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

					$lstInputs .=
					'<table id="inputsTable" class="display" data-page-length="100">
		                <thead>
		                    <tr>
								<th width="170px">Fecha</th>
		                        <th>Producto</th>
		                        <th width="100px">Cantidad</th>
		                        <th width="130px">Tipo</th>
		                        <th>Proveedor</th>
		                        <th width="100px">Factura</th>
		                        ' . ((Session::getValue('level') == 10) ? '<th width="35px"></th>' : '') . '
		                    </tr>
		                </thead>
		                <tbody>';

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
							$type = 'Devolución de préstamo';
							$typeStyleColor = '#2e7d32';
						}

						if (Session::getValue('level') == 10)
						{
							if ($input['type'] == '1' OR $input['type'] == '3')
								$btnEditInput = '<a data-action="getInputToEdit" data-id="' . $input['id_inventory_input'] . '"><i class="material-icons">edit</i><span>Detalles / Editar</span></a>';
							else
								$btnEditInput = '';
						}

	    				$lstInputs .=
	    				'<tr style="color:' . $typeStyleColor . ';">
							<td>' . $input['input_date_time'] . '</td>
	    					<td>[' . $product['folio'] . '] ' . $product['name'] . (!empty($product['category_one']) ? ' - ' . $product['category_one'] : '')  . (!empty($product['category_two']) ? ' - ' . $product['category_two'] : '')  . (!empty($product['category_tree']) ? ' - ' . $product['category_tree'] : '')  . (!empty($product['category_four']) ? ' - ' . $product['category_four'] : '') . '</td>
							<td>' . $input['quantify'] . ' ' . $unity . '</td>
							<td>' . $type . '</td>
							<td>' . (!empty($provider['name']) ? $provider['name'] : '') . '</td>
	    					<td>
								' . ((!empty($input['bill'])) ? '# ' . $input['bill'] . '<br>' : '') . '
								' . ((!empty($input['price'])) ? '$ ' . $input['price'] . ' MXN<br>' : '') . '
								' . ((!empty($input['payment'])) ? $input['payment'] : '') . '
							</td>
	                        ' . ((Session::getValue('level') == 10) ? '<td>' . $btnEditInput . '</td>' : '') . '
	    				</tr>';
	    			}

					$lstInputs .=
					'	</tbody>
	            	</table>';

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
		if (Session::getValue('level') == 10)
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

	/* Lista de salidas del inventario, crear salida del inventario
	--------------------------------------------------------------------------- */
	public function outputs($id)
	{
		if (Session::getValue('level') == 8 OR Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action		= $_POST['action'];
				$idOutput	= ($action == 'edit') ? $_POST['id'] : null;
				$product    = (isset($_POST['product']) AND !empty($_POST['product'])) ? $_POST['product'] : null;
				$quantity	= (isset($_POST['quantity']) AND !empty($_POST['quantity'])) ? $_POST['quantity'] : null;
				$type		= (isset($_POST['type']) AND !empty($_POST['type'])) ? $_POST['type'] : null;
				$client		= (isset($_POST['client']) AND !empty($_POST['client'])) ? $_POST['client'] : null;

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

	            if (!isset($type))
	                array_push($errors, ['type', 'Seleccione una opción']);

	            if ($type == '6' AND !isset($client))
	                array_push($errors, ['client', 'Seleccione una opción']);

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
							$query = $this->model->newOutput($product, $quantity, $type, $client, $date . ' ' . $hour, $id);

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
							$query = $this->model->editOutput($idOutput, $product, $quantity, $type, $client, $date . ' ' . $hour);

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

				if (Session::getValue('level') == 10)
					$inventoryLogged = true;
				else if (Session::getValue('level') == 8)
				{
					if ($inventory['id_branch_office'] == $userLogged['id_branch_office'])
						$inventoryLogged = true;
					else
						$inventoryLogged = false;
				}

	            if ($inventory['status'] == true AND $inventoryLogged == true)
	            {
	                define('_title', '{$lang.title} | Dashboard');

	    			$template = $this->view->render($this, 'outputs');
	    			$template = $this->format->replaceFile($template, 'header');
	    			$outputs = $this->model->getAllOutputs($id);
	                $products = $this->model->getAllProducts($inventory['type']);
	                $clients = $this->model->getAllClients();
	    			$lstOutputs = '';
	                $lstProducts = '';
	                $lstClients = '';
					$mdlTransferProduct = '';

					$lstOutputs .=
					'<table id="outputsTable" class="display" data-page-length="100">
		                <thead>
		                    <tr>
		                        <th width="170px">Fecha</th>
		                        <th>Producto</th>
		                        <th width="100px">Cantidad</th>
		                        <th width="130px">Tipo</th>
		                        ' . ((Session::getValue('level') == 10) ? '<th width="35px"></th>' : '') . '
		                    </tr>
		                </thead>
		                <tbody>';

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
							$type = 'Préstamo';
							$typeStyleColor = '#000';
						}

						if (Session::getValue('level') == 10)
						{
							if ($output['type'] == '2' OR $output['type'] == '3' OR $output['type'] == '6')
								$btnEditOutput = '<a data-action="getOutputToEdit" data-id="' . $output['id_inventory_output'] . '"><i class="material-icons">edit</i><span>Detalles / Editar</span></a>';
							else
								$btnEditOutput = '';
						}

	    				$lstOutputs .=
	    				'<tr style="color:' . $typeStyleColor . ';">
							<td>' . $output['output_date_time'] . '</td>
	    					<td>[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</td>
	    					<td>' . $output['quantity'] . ' ' . $unity . '</td>
	    					<td>' . $type . '</td>
	                        ' . ((Session::getValue('level') == 10) ? '<td>' . $btnEditOutput . '</td>' : '') . '
	    				</tr>';
	    			}

					$lstOutputs .=
					'	</tbody>
	            	</table>';

	                foreach ($products as $product)
	                    $lstProducts .= '<option value="' . $product['id_product'] . '">[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</option>';

					foreach ($clients as $value)
	                    $lstClients .= '<option value="' . $value['id_client'] . '">' . $value['name'] . '</option>';

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

					$replace = [
						'{$title}' => 'Inv: ' . $inventory['name'] . ', Suc: ' . $inventory['branch_office'],
	    				'{$lstOutputs}' => $lstOutputs,
	    				'{$lstProducts}' => $lstProducts,
	    				'{$lstClients}' => $lstClients,
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
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$output = $this->model->getOutputById($id);

				if (!empty($output))
				{
					$output['output_date_time'] = explode(' ', $output['output_date_time']);

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
		if (Session::getValue('level') == 10)
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
		if (Session::getValue('level') == 10)
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
		if (Session::getValue('level') == 10)
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

				if (!isset($filterTransfer))
					array_push($errors, ['filterTransfer', 'Seleccione una opción']);

				if ($typeTransfer == '2' AND !isset($filterInventory))
					array_push($errors, ['filterInventory', 'Seleccione una opción']);

				if (!isset($product))
					array_push($errors, ['product', 'Seleccione una opción']);

				if (!isset($quantity))
					array_push($errors, ['quantity', 'No deje este campo vacío']);

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

	/* Lista de stocks de productos por inventario
	--------------------------------------------------------------------------- */
	public function stocks($idInventory)
	{
		if (Session::getValue('level') == 8 OR Session::getValue('level') == 10)
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

				if (isset($max) AND !is_numeric($max))
					array_push($errors, ['max', 'Ingrese únicamente números']);

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

				if (Session::getValue('level') == 10)
					$inventoryLogged = true;
				else if (Session::getValue('level') == 8)
				{
					if ($inventory['id_branch_office'] == $userLogged['id_branch_office'])
						$inventoryLogged = true;
					else
						$inventoryLogged = false;
				}

	            if ($inventory['status'] == true AND $inventoryLogged == true)
	            {
	                define('_title', '{$lang.title} | Dashboard');

	        		$template = $this->view->render($this, 'stocks');
	        		$template = $this->format->replaceFile($template, 'header');
					$stocks = $this->model->getAllStocksByInventory($inventory['id_inventory']);
					$products = $this->model->getAllProducts($inventory['type']);
					$tblStocks = '';
	                $lstProducts = '';

					$tblStocks .=
					'<table id="stocksTable" class="display" data-page-length="100">
		                <thead>
		                    <tr>
		                        ' . ((Session::getValue('level') == 10) ? '<th width="20px"></th>' : '') . '
		                        <th>Producto</th>
		                        <th width="100px">Mínimo</th>
		                        <th width="100px">Máximo</th>
		                        <th width="100px">Actual</th>
		                        <th width="100px">Estado</th>
		                        ' . ((Session::getValue('level') == 10) ? '<th width="35px"></th>' : '') . '
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

						if (!empty($stock['max']) AND $currentStock > $stock['max'])
							$status = '<span class="missing">Alto</span>';
						else if ($currentStock >= $stock['min'] AND $currentStock <= ($stock['min'] + 10))
							$status = '<span class="same">Bajo</span>';
						else if ($currentStock > ($stock['min'] + 10))
							$status = '<span class="stable">Normal</span>';
						else if ($currentStock < $stock['min'])
							$status = '<span class="missing">Faltante</span>';

	                    $tblStocks .=
	                    '<tr>
							' . ((Session::getValue('level') == 10) ? '<td><input type="checkbox" data-check value="' . $stock['id_inventory_stock'] . '" /></td>' : '') . '
							<td>[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</td>
	                        <td>' . $stock['min'] . ' ' . $unity . '</td>
	                        <td>' . (!empty($stock['max']) ? $stock['max'] . ' ' . $unity : '-') . '</td>
	                        <td>' . $currentStock . ' ' . $unity . '</td>
	                        <td>' . $status . '</td>
							' . ((Session::getValue('level') == 10) ? '<td><a data-action="getStockToEdit" data-id="' . $stock['id_inventory_stock'] . '"><i class="material-icons">edit</i><span>Detalles / Editar</span></a></td>' : '') . '
	                    </tr>';
					}

					$tblStocks .=
					'	</tbody>
					</table>';

	                foreach ($products as $product)
	                    $lstProducts .= '<option value="' . $product['id_product'] . '">[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</option>';

	                $replace = [
						'{$title}' => 'Inv: ' . $inventory['name'] . ', Suc: ' . $inventory['branch_office'],
	                    '{$tblStocks}' => $tblStocks,
	                    '{$lstProducts}' => $lstProducts
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
        if (Session::getValue('level') == 10)
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
		if (Session::getValue('level') == 10)
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
}
