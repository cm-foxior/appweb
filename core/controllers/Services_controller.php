<?php

defined('_EXEC') or die;

class Services_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Lista de servicios, nuevo y editar servicio
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action	= $_POST['action'];
				$id		= ($action == 'edit') ? $_POST['id'] : null;

				$name 				= (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
				$folio 				= (isset($_POST['folio']) AND !empty($_POST['folio'])) ? $_POST['folio'] : null;
				$type 				= (isset($_POST['type']) AND !empty($_POST['type'])) ? $_POST['type'] : null;
				$price				= (isset($_POST['price']) AND !empty($_POST['price'])) ? $_POST['price'] : null;
				$discountQuantity	= (isset($_POST['discountQuantity']) AND !empty($_POST['discountQuantity'])) ? $_POST['discountQuantity'] : null;
				$discountType 		= (isset($_POST['discountType']) AND !empty($_POST['discountType'])) ? $_POST['discountType'] : null;
				$coin 				= (isset($_POST['coin']) AND !empty($_POST['coin'])) ? $_POST['coin'] : null;
				$warranty 			= (isset($_POST['warranty']) AND !empty($_POST['warranty'])) ? $_POST['warranty'] : null;
				$category 			= (isset($_POST['category']) AND !empty($_POST['category'])) ? $_POST['category'] : null;
				$observations 		= (isset($_POST['observations']) AND !empty($_POST['observations'])) ? $_POST['observations'] : null;

				$errors = [];

				if (!isset($name))
					array_push($errors, ['name', 'No deje este campo vacío']);

				if (!isset($folio))
					array_push($errors, ['folio', 'No deje este campo vacío']);

				if (!isset($type))
					array_push($errors, ['type', 'Seleccione una opción']);
				else if ($type != '1' AND $type != '2')
					array_push($errors, ['type', 'Opción no válida']);

				if (!isset($price))
					array_push($errors, ['price', 'No deje este campo vacío']);
				else if (!is_numeric($price))
					array_push($errors, ['price', 'Ingrese únicamente números en el precio público']);
				else if ($price < 0)
					array_push($errors, ['price', 'No ingrese números negativos']);

				if (!isset($discountQuantity) AND isset($discountType))
					array_push($errors, ['discountQuantity', 'No deje este campo vacío']);
				else if (isset($discountQuantity) AND !is_numeric($discountQuantity))
					array_push($errors, ['discountQuantity', 'Ingrese únicamente números']);
				else if (isset($discountQuantity) AND $discountQuantity < 0)
		            array_push($errors, ['discountQuantity', 'No ingrese números negativos']);

				if (!isset($discountType) AND isset($discountQuantity))
					array_push($errors, ['discountType', 'Seleccione una opción']);
				else if (isset($discountType) AND $discountType != '1' AND isset($discountType) AND $discountType != '2')
					array_push($errors, ['discountType', 'Opción no válida']);

				if (!isset($coin))
					array_push($errors, ['coin', 'Seleccione una opción']);
				else if ($coin != '1' AND $coin != '2')
					array_push($errors, ['coin', 'Opción no válida']);

				if (empty($errors))
				{
					$exist = $this->model->checkExistService($id, $name, $folio, $action);

					if ($exist['status'] == true)
					{
						if ($exist['errors']['errorName'] == true)
	                    	array_push($errors, ['name', 'Este registro ya existe']);

						if ($exist['errors']['errorFolio'] == true)
	                    	array_push($errors, ['folio', 'Este registro ya existe']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
					}
					else
					{
						$folio = strtoupper($folio);

						if ($type == '2')
						{
							if ($action == 'edit')
							{
								$service = $this->model->getServiceById($id);

								if (!empty($service['components']))
									$components = json_decode($service['components'], true);
								else
									$components = json_encode([]);
							}
							else
								$components = json_encode([]);
						}
						else
							$components = null;

						if (isset($discountQuantity) AND isset($discountType))
						{
							$discount = json_encode([
								'quantity' => $discountQuantity,
								'type' => $discountType
							]);
						}
						else
							$discount = null;

						if ($action == 'new')
							$query = $this->model->newService($name, $folio, $price, $discount, $coin, $components, $warranty, $category, $observations);
						else if ($action == 'edit')
							$query = $this->model->editService($id, $name, $folio, $price, $discount, $coin, $components, $warranty, $category, $observations);

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

				$services = $this->model->getAllServices();
				$warranties	= $this->model->getAllWarranties();
				$categories	= $this->model->getAllCategories();

				$buttons = '';
				$tblServices = '';
				$mdlServices = '';
				$mdlActivateServices = '';
				$mdlDeactivateServices = '';
				$mdlDeleteServices = '';

				if (Session::getValue('level') == 10)
				{
					$buttons .=
					'<div class="box-buttons">
			            <a data-button-modal="deleteServices"><i class="material-icons">delete</i><span>Eliminar</span></a>
			            <a data-button-modal="services"><i class="material-icons">add</i><span>Nuevo</span></a>
			            <!-- <a data-button-modal="deactivateServices"><i class="material-icons">block</i><span>Desactivar</span></a>
			            <a data-button-modal="activateServices"><i class="material-icons">check</i><span>Activar</span></a> -->
			            <a href="/services/categories/"><i class="material-icons">turned_in</i><span>Categorías</span></a>
			            <div class="clear"></div>
			        </div>';
				}

				$tblServices .=
				'<table id="tblServices" class="display" data-page-length="100" data-table-order="' . ((Session::getValue('level') == 10) ? '6' : '5') . '">
	                <thead>
	                    <tr>
	                        ' . ((Session::getValue('level') == 10) ? '<th width="20px"></th>' : '') . '
	                        <th>Nombre</th>
	                        <th>Folio</th>
	                        <th>Precio</th>
	                        <th>Descuento</th>
	                        <th>Garantía</th>
	                        <th>Categoría</th>
	                        <th width="100px">Estado</th>
	                        ' . ((Session::getValue('level') == 10) ? '<th width="70px"></th>' : '') . '
	                    </tr>
	                </thead>
	                <tbody>';

				foreach ($services as $service)
				{
					if ($service['coin'] == '1')
						$coin = 'MXN';
					else if ($service['coin'] == '2')
						$coin = 'USD';

					if (!empty($service['discount']))
					{
						$discount = json_decode($service['discount'], true);

						if ($discount['type'] == '1')
							$discount = $discount['quantity'] . ' %';
						else if ($discount['type'] == '2')
							$discount = '$ ' . number_format($discount['quantity'], 2, '.', ',') . ' ' . $coin;
					}
					else
						$discount = '-';

					if (!empty($service['id_warranty']))
					{
						$warranty = $this->model->getWarrantyById($service['id_warranty']);

						if ($warranty['time_frame'] == '1')
							$warranty = $warranty['quantity'] . ' Días';
						else if ($warranty['time_frame'] == '2')
							$warranty = $warranty['quantity'] . ' Meses';
						else if ($warranty['time_frame'] == '3')
							$warranty = $warranty['quantity'] . ' Años';
					}
					else
						$warranty = '-';

					if (!empty($service['id_service_category']))
					{
						$category = $this->model->getCategoryById($service['id_service_category']);
						$category = $category['name'];
					}
					else
						$category = '-';

					$tblServices .=
					'<tr>
						' . ((Session::getValue('level') == 10) ? '<td><input type="checkbox" data-check value="' . $service['id_service'] . '" /></td>' : '') . '
						<td>' . $service['name'] . '</td>
						<td>' . $service['folio'] . '</td>
						<td>$ ' . number_format($service['price'], 2, '.', ',') . ' ' . $coin . '</td>
						<td>' . $discount . '</td>
						<td>' . $warranty . '</td>
						<td>' . $category . '</td>
						<td>' . (($service['status'] == true) ? '<span class="active">Activado</span>' : '<span class="deactive">Desactivado</span>') . '</td>';

					if (Session::getValue('level') == 10)
					{
						$tblServices .=
						'<td>
							<a ' . (($service['status'] == true) ? 'data-action="getServiceToEdit" data-id="' . $service['id_service'] . '"' : 'disabled') . '><i class="material-icons">edit</i><span>Detalles / Editar</span></a>
							' . (!empty($service['components']) ? '<a ' . (($service['status'] == true) ? 'href="/services/components/' . $service['id_service'] . '"' : 'disabled') . '><i class="material-icons">format_list_bulleted</i><span>Componentes</span></a>' : '') . '
						</td>';
					}

					$tblServices .= '</tr>';
				}

				$tblServices .=
	            '	</tbody>
	            </table>';

				if (Session::getValue('level') == 10)
				{
					$mdlServices .=
					'<section class="modal" data-modal="services">
					    <div class="content">
					        <header>
					            <h6>Nuevo servicio</h6>
					        </header>
					        <main>
					            <form name="services" data-submit-action="new">
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
					                        <span><span class="required-field">*</span>Folio</span>
					                        <input type="text" name="folio" class="uppercase">
					                    </label>
					                    <label class="checkbox" data-important>
					                        <input type="checkbox" data-action="randomFolio">
					                        <span>Folio aleatorio</span>
					                        <div class="clear"></div>
					                    </label>
					                </fieldset>
									<fieldset class="input-group">
					                    <label data-important>
					                        <span><span class="required-field">*</span>Tipo de servicio</span>
					                        <select name="type">
					                            <option value="1">Servicio simple</option>
					                            <option value="2">Servicio compuesto</option>
					                        </select>
					                    </label>
					                </fieldset>
					                <fieldset class="input-group">
					                    <label data-important>
					                        <span><span class="required-field">*</span>Precio</span>
					                        <input type="number" name="price">
					                    </label>
					                </fieldset>
									<fieldset class="input-group">
					                    <label data-important>
					                        <span>Descuento</span>
											<select name="discountType" class="span6 margin-right">
					                            <option value="">Sin descuento</option>
					                            <option value="1">(%) Porcentaje</option>
					                            <option value="2">($) Dinero</option>
					                        </select>
					                        <input id="discountQuantity" type="number" name="discountQuantity" class="span6" disabled>
											<div class="clear"></div>
					                    </label>
					                </fieldset>
									<fieldset class="input-group">
					                    <label data-important>
					                        <span><span class="required-field">*</span>Moneda de venta</span>
					                        <select name="coin">
					                            <option value="1">Pesos Mexicanos (MXN)</option>
					                            <option value="2">Dólales Americanos (USD)</option>
					                        </select>
					                    </label>
					                </fieldset>
					                <fieldset class="input-group">
					                    <label data-important>
					                        <span>Garantía</span>
					                        <select name="warranty" class="chosen-select">
					                            <option value="">Sin garantía</option>';

					foreach ($warranties as $warranty)
					{
						if ($warranty['time_frame'] == '1')
							$timeFrame = 'Días';
						else if ($warranty['time_frame'] == '2')
							$timeFrame = 'Meses';
						else if ($warranty['time_frame'] == '3')
							$timeFrame = 'Años';

						$mdlServices .= '<option value="' . $warranty['id_warranty'] . '">' . $warranty['quantity'] . ' ' . $timeFrame . '</option>';
					}

					$mdlServices .=
					'						</select>
					                    </label>
					                </fieldset>
					                <fieldset class="input-group">
					                    <label data-important>
					                        <span>Categoría</span>
					                        <select name="category" class="chosen-select">
					                            <option value="">Sin categoría</option>';

					foreach ($categories as $category)
						$mdlServices .= '<option value="' . $category['id_service_category'] . '">' . $category['name'] . '</option>';

					$mdlServices .=
					'  						</select>
					                    </label>
					                </fieldset>
									<fieldset class="input-group">
					                    <label data-important>
					                        <span>Observaciones</span>
					                        <textarea name="observations"></textarea>
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

					$mdlActivateServices .=
					'<section class="modal alert" data-modal="activateServices">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea activar está selección de servicios?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="activateServices">Aceptar</a>
					        </footer>
					    </div>
					</section>';

					$mdlDeactivateServices .=
					'<section class="modal alert" data-modal="deactivateServices">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea desactivar está selección de servicios?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="deactivateServices">Aceptar</a>
					        </footer>
					    </div>
					</section>';

					$mdlDeleteServices .=
					'<section class="modal alert" data-modal="deleteServices">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea eliminar está selección de servicios?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="deleteServices">Aceptar</a>
					        </footer>
					    </div>
					</section>';
				}

				$replace = [
					'{$buttons}' => $buttons,
					'{$tblServices}' => $tblServices,
					'{$mdlServices}' => $mdlServices,
					'{$mdlActivateServices}' => $mdlActivateServices,
					'{$mdlDeactivateServices}' => $mdlDeactivateServices,
					'{$mdlDeleteServices}' => $mdlDeleteServices
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener servicio para editar
	--------------------------------------------------------------------------- */
	public function getServiceToEdit($id)
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$service = $this->model->getServiceById($id);

	            if (!empty($service))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $service
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Activar y desactivar selección de servicios
	--------------------------------------------------------------------------- */
	public function changeStatusServices($action)
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

					$query = $this->model->changeStatusServices($selection, $status);

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

	/* Eliminar selección de servicios
	--------------------------------------------------------------------------- */
	public function deleteServices()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$query = $this->model->deleteServices($selection);

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

	/* Lista de componentes, agregar componente
	--------------------------------------------------------------------------- */
	public function components($id)
	{
		if (Session::getValue('level') == 10)
		{
			$components = $this->model->getServiceById($id)['components'];
			$components = json_decode($components, true);

			if (Format::existAjaxRequest() == true)
			{
				$product	= (isset($_POST['product']) AND !empty($_POST['product'])) ? $_POST['product'] : null;
				$quantity	= (isset($_POST['quantity']) AND !empty($_POST['quantity'])) ? $_POST['quantity'] : null;

				$errors = [];

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
					$repeat = false;

					foreach ($components as $component)
					{
						if ($component['product'] == $product)
							$repeat = true;
					}

					if ($repeat == true)
					{
						array_push($errors, ['product', 'Este producto ya está integrado']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
					}
					else
					{
						array_push($components, ['quantity' => $quantity, 'product' => $product]);

						$components = json_encode($components);

						$query = $this->model->newComponent($id, $components);

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
								'message' => 'Error al realizar la operación a la base de datos'
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
				if (isset($components))
				{
					define('_title', '{$lang.title}');

					$template = $this->view->render($this, 'components');
					$template = $this->format->replaceFile($template, 'header');

					$btnNewComponent = '';
					$tblComponents = '';
					$mdlNewComponent = '';
					$mdlDeleteComponent = '';

					$products = $this->model->getAllComponents();

					$btnNewComponent .=
					'<a data-button-modal="newComponent"><i class="material-icons">add</i><span>Nuevo</span></a>';

					$tblComponents .=
					'<table id="tblComponents" class="display" data-page-length="100">
		                <thead>
		                    <tr>
		                        <th width="40px"></th>
								<th>Producto</th>
		                        <th>Cantidad</th>
								<th width="35px"></th>
		                    </tr>
		                </thead>
		                <tbody>';

					foreach ($components as $component)
					{
						$component['product'] = $this->model->getComponentById($component['product']);

						if ($component['product']['unity'] == '1')
							$unity = 'Kilogramos';
						else if ($component['product']['unity'] == '2')
							$unity = 'Gramos';
						else if ($component['product']['unity'] == '3')
							$unity = 'Mililitros';
						else if ($component['product']['unity'] == '4')
							$unity = 'Litros';
						else if ($component['product']['unity'] == '5')
							$unity = 'Piezas';

						$tblComponents .=
						'<tr>
							<td>' . (!empty($component['product']['avatar']) ? '<a href="{$path.images}products/' . $component['product']['avatar'] . '" class="fancybox-thumb" rel="fancybox-thumb"><img src="{$path.images}products/' . $component['product']['avatar'] . '" /></a>' : '<img src="{$path.images}empty.png" class="emptyAvatar" />') . '</td>
							<td>[' . $component['product']['folio'] . '] ' . $component['product']['name'] . '</td>
							<td>' . $component['quantity'] . ' ' . $unity . '</td>
							<td><a data-button-modal="deleteComponent" data-action="sendIdComponentToDelete" data-id="' . $component['product']['id_product'] . '"><i class="material-icons">close</i><span>Eliminar</span></a></td>
						</tr>';
					}

					$tblComponents .=
					'   </tbody>
		            </table>';

					$mdlNewComponent .=
					'<section class="modal" data-modal="newComponent">
					    <div class="content">
					        <header>
					            <h6>Nuevo componente</h6>
					        </header>
					        <main>
					            <form name="newComponent">
									<fieldset class="input-group">
										<label data-important>
											<span>Producto</span>
											<select name="product" class="chosen-select">
												<option value="">Seleccione una opción</option>';

					foreach ($products as $product)
						$mdlNewComponent .= '<option value="' . $product['id_product'] . '">[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</option>';

					$mdlNewComponent .=
					'						</select>
										</label>
									</fieldset>
									<fieldset class="input-group">
					                    <label data-important>
					                        <span>Cantidad</span>
					                        <input type="number" name="quantity" min="1">
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

					$mdlDeleteComponent .=
					'<section class="modal alert" data-modal="deleteComponent">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Esta seguro que desea eliminar este componente?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="deleteComponent" data-id="' . $id . '">Aceptar</a>
					        </footer>
					    </div>
					</section>';

					$replace = [
						'{$btnNewComponent}' => $btnNewComponent,
						'{$tblComponents}' => $tblComponents,
						'{$mdlNewComponent}' => $mdlNewComponent,
						'{$mdlDeleteComponent}' => $mdlDeleteComponent
					];

					$template = $this->format->replace($replace, $template);

					echo $template;
				}
				else
					header('Location: /services');
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Eliminar componente
	--------------------------------------------------------------------------- */
	public function deleteComponent()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$idService = (isset($_POST['idService']) AND !empty($_POST['idService'])) ? $_POST['idService'] : null;
				$idComponent = (isset($_POST['idComponent']) AND !empty($_POST['idComponent'])) ? $_POST['idComponent'] : null;

				$query = $this->model->deleteComponent($idService, $idComponent);

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
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Lista de categorías, nuevo y editar categoría
	--------------------------------------------------------------------------- */
	public function categories()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action = $_POST['action'];
				$id		= ($action == 'edit') ? $_POST['id'] : null;

				$name 	= (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;

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
					$relationships = $this->model->checkCategoryRelationships($category['id_service_category']);

					$lstCategories .=
					'<tr>
						<td>' . (($relationships == true) ? '' : '<input type="checkbox" data-check value="' . $category['id_service_category'] . '" />') . '</td>
						<td>' . $category['name'] . '</td>
						<td><a href="" data-action="getCategoryToEdit" data-id="' . $category['id_service_category'] . '"><i class="material-icons">edit</i><span>Editar</span></a></td>
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
		if (Session::getValue('level') == 10)
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
		if (Session::getValue('level') == 10)
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
