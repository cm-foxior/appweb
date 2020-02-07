<?php

defined('_EXEC') or die;

class Products_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Lista de productos, nuevo y editar producto
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action = $_POST['action'];
				$id		= ($action == 'edit') ? $_POST['id'] : null;

				$name 				= (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
				$name_compound 		= (isset($_POST['name_compound']) AND !empty($_POST['name_compound'])) ? true : false;
				$folio 				= (isset($_POST['folio']) AND !empty($_POST['folio'])) ? $_POST['folio'] : null;
				$type 				= (isset($_POST['type']) AND !empty($_POST['type'])) ? $_POST['type'] : null;
				$components 		= (isset($_POST['components']) AND !empty($_POST['components'])) ? $_POST['components'] : null;
				$basePrice 			= (isset($_POST['basePrice']) AND !empty($_POST['basePrice'])) ? $_POST['basePrice'] : null;
				$prefPrice			= (isset($_POST['prefPrice']) AND !empty($_POST['prefPrice'])) ? $_POST['prefPrice'] : null;
				$publicPrice		= (isset($_POST['publicPrice']) AND !empty($_POST['publicPrice'])) ? $_POST['publicPrice'] : null;
				$discountQuantity	= (isset($_POST['discountQuantity']) AND !empty($_POST['discountQuantity'])) ? $_POST['discountQuantity'] : null;
				$discountType 		= (isset($_POST['discountType']) AND !empty($_POST['discountType'])) ? $_POST['discountType'] : null;
				$coin 				= (isset($_POST['coin']) AND !empty($_POST['coin'])) ? $_POST['coin'] : null;
				$unity       		= (isset($_POST['unity']) AND !empty($_POST['unity'])) ? $_POST['unity'] : null;
				$avatar 			= (isset($_FILES['avatar']['name']) AND !empty($_FILES['avatar']['name'])) ? $_FILES['avatar'] : null;
				$category_one 		= (isset($_POST['category_one']) AND !empty($_POST['category_one'])) ? $_POST['category_one'] : null;
				$category_two 		= (isset($_POST['category_two']) AND !empty($_POST['category_two'])) ? $_POST['category_two'] : null;
				$category_tree 		= (isset($_POST['category_tree']) AND !empty($_POST['category_tree'])) ? $_POST['category_tree'] : null;
				$category_four 		= (isset($_POST['category_four']) AND !empty($_POST['category_four'])) ? $_POST['category_four'] : null;
				$observations 		= (isset($_POST['observations']) AND !empty($_POST['observations'])) ? $_POST['observations'] : null;

				$errors = [];

				if ($name_compound == false AND !isset($name))
					array_push($errors, ['name', 'No deje este campo vacío']);

				if (!isset($folio))
					array_push($errors, ['folio', 'No deje este campo vacío']);

				if (!isset($type))
					array_push($errors, ['type', 'Seleccione una opción']);
				else if ($type != '1' AND $type != '2' AND $type != '3' AND $type != '4')
					array_push($errors, ['type', 'Opción no válidad']);

				// if ($type <= '2' AND !isset($components))
				// 	array_push($errors, ['components', 'Seleccione una opción']);
				// else if ($type <= '2' AND $components != '1' AND $type <= '2' AND $components != '2')
				// 	array_push($errors, ['components', 'Opción no válidad']);

				if (isset($basePrice) AND $type <= '2' AND isset($basePrice) AND !is_numeric($basePrice))
					array_push($errors, ['basePrice', 'Ingrese únicamente números']);
				else if (isset($basePrice) AND $type <= '2' AND isset($basePrice) AND $basePrice < 0)
					array_push($errors, ['basePrice', 'No ingrese números negativos']);

				if (isset($prefPrice) AND $type <= '2' AND isset($prefPrice) AND !is_numeric($prefPrice))
					array_push($errors, ['prefPrice', 'Ingrese únicamente números']);
				else if (isset($prefPrice) AND $type <= '2' AND isset($prefPrice) AND $prefPrice < 0)
					array_push($errors, ['prefPrice', 'No ingrese números negativos']);

				if ($type <= '2' AND !isset($publicPrice))
					array_push($errors, ['publicPrice', 'No deje este campo vacío']);
				else if ($type <= '2' AND !is_numeric($publicPrice))
					array_push($errors, ['publicPrice', 'Ingrese únicamente números']);
				else if ($type <= '2' AND $publicPrice < 0)
					array_push($errors, ['publicPrice', 'No ingrese números negativos']);

				if ($type <= '2' AND !isset($discountQuantity) AND isset($discountType))
					array_push($errors, ['discountQuantity', 'No deje este campo vacío']);
				else if ($type <= '2' AND isset($discountQuantity) AND !is_numeric($discountQuantity))
					array_push($errors, ['discountQuantity', 'Ingrese únicamente números']);
				else if ($type <= '2' AND isset($discountQuantity) AND $discountQuantity < 0)
		            array_push($errors, ['discountQuantity', 'No ingrese números negativos']);
				else if ($type <= '2' AND isset($discountQuantity) AND $discountType == '1' AND $discountQuantity > 100)
		            array_push($errors, ['discountQuantity', 'Porcentaje de descuento no puede ser mayor a cien']);
				else if ($type <= '2' AND isset($discountQuantity) AND $discountType == '2' AND $discountQuantity > $publicPrice)
		            array_push($errors, ['discountQuantity', 'La cantidad de descuento no puede ser mayor al precio público']);

				if ($type <= '2' AND !isset($discountType) AND isset($discountQuantity))
					array_push($errors, ['discountType', 'Seleccione una opción']);
				else if ($type <= '2' AND isset($discountType) AND $discountType != '1' AND $type <= '2' AND isset($discountType) AND $discountType != '2')
					array_push($errors, ['discountType', 'Opción no válida']);

				if ($type <= '2' AND !isset($coin))
					array_push($errors, ['coin', 'Seleccione una opción']);
				else if ($type <= '2' AND $coin != '1' AND $type <= '2' AND $coin != '2')
					array_push($errors, ['coin', 'Opción no válida']);

				if (!isset($unity))
					array_push($errors, ['unity', 'Seleccione una opción']);
				else if ($unity != '1' AND $unity != '2' AND $unity != '3' AND $unity != '4' AND $unity != '5')
					array_push($errors, ['unity', 'Opción no válida']);

				if ($name_compound == true AND !isset($category_one) AND $name_compound == true AND !isset($category_two) AND $name_compound == true AND !isset($category_tree) AND $name_compound == true AND !isset($category_four))
					array_push($errors, ['name', 'Seleccione una de las categorías']);

				if (empty($errors))
				{
					if ($name_compound == true)
					{
						if (!empty($category_one))
							$name_category_one = $this->model->getCategoryById($category_one, 'one')['name'];
						else
							$name_category_one = '';

						if (!empty($category_two))
							$name_category_two = $this->model->getCategoryById($category_two, 'two')['name'];
						else
							$name_category_two = '';

						if (!empty($category_tree))
							$name_category_tree = $this->model->getCategoryById($category_tree, 'tree')['name'];
						else
							$name_category_tree = '';

						if (!empty($category_four))
							$name_category_four = $this->model->getCategoryById($category_four, 'four')['name'];
						else
							$name_category_four = '';

						$name = $name_category_one . ' ' . $name_category_two . ' ' . $name_category_tree . ' ' . $name_category_four;
					}

					$folio = strtoupper($folio);

					if ($type == '1' OR $type == '2')
					{
						// if ($components == '2')
						// {
						// 	if ($action == 'edit')
						// 	{
						// 		$product = $this->model->getProductById($id);
						//
						// 		if (!empty($product['components']))
						// 			$components = json_decode($product['components'], true);
						// 		else
						// 			$components = json_encode([]);
						// 	}
						// 	else
						// 		$components = json_encode([]);
						// }
						// else
						// 	$components = null;

						$price = json_encode([
							'base_price' => $basePrice,
							'pref_price' => $prefPrice,
							'public_price' => $publicPrice
						]);

						if (isset($discountQuantity) AND isset($discountType))
						{
							$discount = json_encode([
								'quantity' => $discountQuantity,
								'type' => $discountType
							]);
						}
						else
							$discount = null;
					}
					else
					{
						$components = null;
						$price		= null;
						$discount	= null;
						$coin		= null;
					}

					$exist = $this->model->checkExistProduct($id, $name, $category_one, $category_two, $category_tree, $category_four, $folio, $type, $action);

					if ($exist['status'] == true)
					{
						if ($exist['errors']['errorName'] == true)
						{
							array_push($errors, ['name', 'Este registro ya existe']);
							array_push($errors, ['type', 'Este registro ya existe']);
						}

						if ($exist['errors']['errorFolio'] == true)
							array_push($errors, ['folio', 'Este registro ya existe']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
					}
					else
					{
						if ($action == 'new')
							$query = $this->model->newProduct($name, $folio, $type, $components, $price, $discount, $coin, $unity, $avatar, $category_one, $category_two, $category_tree, $category_four, $observations);
						else if ($action == 'edit')
							$query = $this->model->editProduct($id, $name, $folio, $type, $components, $price, $discount, $coin, $unity, $avatar, $category_one, $category_two, $category_tree, $category_four, $observations);

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

				$products = $this->model->getAllProducts();
				$categories_one = $this->model->getAllCategories('one');
				$categories_two = $this->model->getAllCategories('two');
				$categories_tree = $this->model->getAllCategories('tree');
				$categories_four = $this->model->getAllCategories('four');

				$buttons = '';
				$tblProducts = '';
				$mdlProducts = '';
				$mdlImportFromExcel = '';
				$mdlActivateProducts = '';
				$mdlDectivateProducts = '';
				$mdlDeleteProducts = '';
				$mdlPostProductsToEcommerce = '';
				$mdlUnpostProductsToEcommerce = '';

				if (Session::getValue('level') >= 9)
				{
					$buttons .=
					'<div class="box-buttons">';

					if (Session::getValue('level') == 10)
					{
						$buttons .=
						'<a data-button-modal="deleteProducts"><i class="material-icons">delete</i><span>Eliminar</span></a>
						<a data-button-modal="products"><i class="material-icons">add</i><span>Nuevo</span></a>
						<!-- <a data-button-modal="deactivateProducts"><i class="material-icons">block</i><span>Desactivar</span></a>
			            <a data-button-modal="activateProducts"><i class="material-icons">check</i><span>Activar</span></a>
						<a data-button-modal="importFromExcel"><i class="material-icons">cloud_upload</i><span>Importar desde excel</span></a> -->
						<a href="/products/flirts"><i class="material-icons">link</i><span>Products ligados</span></a>
						<a href="/products/categories_one"><i class="material-icons">turned_in</i><span>Categorías</span></a>';
					}

					$buttons .=
					'	<a href="/products/tags/all"><i class="material-icons">local_offer</i><span>Etiquetas</span></a>
						<div class="clear"></div>
					</div>';
				}

				$tblProducts .=
				'<table id="tblProducts" class="display" data-page-length="100" data-table-order="' . ((Session::getValue('level') == 10) ? '2' : '1') . '">
	                <thead>
	                    <tr>
	                        ' . ((Session::getValue('level') == 10) ? '<th width="20px"></th>' : '') . '
	                        <th width="40px"></th>
							<th>Folio</th>
							<th>Nombre</th>
							<th>Categorías</th>
							<th width="50px">Unidad</th>
							<th width="180px">Precio</th>
							<th width="50px">Tipo</th>
							<th width="100px">Estado</th>
							' . ((Session::getValue('level') == 10) ? '<th width="70px"></th>' : '') . '
	                    </tr>
	                </thead>
	                <tbody>';

				foreach ($products as $product)
				{
					if ($product['coin'] == '1')
						$coin = 'MXN';
					else if ($product['coin'] == '2')
						$coin = 'USD';

					if (!empty($product['price']))
					{
						$basePrice = json_decode($product['price'], true)['base_price'];

						if (!empty($basePrice))
							$basePrice = '$ ' . $basePrice . ' ' . $coin;
						else
							$basePrice = 'No establecido';
					}
					else
						$basePrice = 'No aplica';

					if (!empty($product['price']))
					{
						$prefPrice = json_decode($product['price'], true)['pref_price'];

						if (!empty($prefPrice))
							$prefPrice = '$ ' . $prefPrice . ' ' . $coin;
						else
							$prefPrice = 'No establecido';
					}
					else
						$prefPrice = 'No aplica';

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

					if ($product['type'] == '1')
						$type = 'Venta';
					else if ($product['type'] == '2')
						$type = 'VSI';
					else if ($product['type'] == '3')
						$type = 'Producción';
					else if ($product['type'] == '4')
						$type = 'Operación';

					if (!empty($product['id_product_category_one']))
					{
						$categoryOne = $this->model->getCategoryById($product['id_product_category_one'], 'one');
						$coAvatar = $categoryOne['avatar'];
						$categoryOne = $categoryOne['name'];
					}
					else
						$categoryOne = '';

					if (!empty($product['id_product_category_two']))
					{
						$categoryTwo = $this->model->getCategoryById($product['id_product_category_two'], 'two');
						$categoryTwo = ' - ' . $categoryTwo['name'];
					}
					else
						$categoryTwo = '';

					if (!empty($product['id_product_category_tree']))
					{
						$categoryTree = $this->model->getCategoryById($product['id_product_category_tree'], 'tree');
						$categoryTree = ' - ' . $categoryTree['name'];
					}
					else
						$categoryTree = '';

					if (!empty($product['id_product_category_four']))
					{
						$categoryFour = $this->model->getCategoryById($product['id_product_category_four'], 'four');
						$categoryFour = ' - ' . $categoryFour['name'];
					}
					else
						$categoryFour = '';

					if (!empty($product['avatar']))
						$pAvatar = '<a href="{$path.images}products/' . $product['avatar'] . '" class="fancybox-thumb" rel="fancybox-thumb"><img src="{$path.images}products/' . $product['avatar'] . '" /></a>';
					else if (!empty($coAvatar))
						$pAvatar = '<a href="{$path.images}products/categories/' . $coAvatar . '" class="fancybox-thumb" rel="fancybox-thumb"><img src="{$path.images}products/categories/' . $coAvatar . '" /></a>';
					else
						$pAvatar = '<img src="{$path.images}empty.png" class="emptyAvatar" />';

					$tblProducts .=
					'<tr>
						' . ((Session::getValue('level') == 10) ? '<td><input type="checkbox" data-check value="' . $product['id_product'] . '" /></td>' : '') . '
						<td>' . $pAvatar . '</td>
						<td>' . $product['folio'] . '</td>
						<td>' . $product['name'] . '</td>
						<td>' . $categoryOne . ' ' . $categoryTwo . ' ' . $categoryTree . ' ' . $categoryFour . '</td>
						<td>' . $unity . '</td>
						<td>
							Base: ' . $basePrice . '<br>
							Secundario: ' . $prefPrice . '<br>
							Público: ' . (!empty($product['price']) ? '$ ' . json_decode($product['price'], true)['public_price'] . ' ' . $coin : 'No aplica') . '
						</td>
						<td>' . $type . '</td>
						<td>' . (($product['status'] == true) ? '<span class="active">Activado</span>' : '<span class="deactive">Desactivado</span>') . '</td>';

					if (Session::getValue('level') == 10)
					{
						$tblProducts .=
						'<td>
							<a ' . (($product['status'] == true) ? 'data-action="getProductToEdit" data-id="' . $product['id_product'] . '"' : 'disabled') . '><i class="material-icons">edit</i><span>Detalles / Editar</span></a>
							' . (!empty($product['components']) ? '<a ' . (($product['status'] == true) ? 'href="/products/components/' . $product['id_product'] . '"' : 'disabled') . '><i class="material-icons">format_list_bulleted</i><span>Componentes</span></a>' : '') . '
						</td>';
					}

					$tblProducts .= '</tr>';
				}

				$tblProducts .=
	                '</tbody>
	            </table>';

				if (Session::getValue('level') == 10)
				{
					$mdlProducts .=
					'<section class="modal" data-modal="products">
					    <div class="content">
					        <header>
					            <h6>Nuevo producto</h6>
					        </header>
					        <main>
					            <form name="products" data-submit-action="new">
									<fieldset class="input-group">
										<p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
									</fieldset>
					                <fieldset class="input-group">
					                    <label data-important>
					                        <span><span class="required-field">*</span>Nombre</span>
					                        <input type="text" name="name" autofocus>
					                    </label>
										<label class="checkbox" data-important>
					                        <input type="checkbox" name="name_compound">
					                        <span>Componer nombre con categorías</span>
					                        <div class="clear"></div>
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
					                        <span><span class="required-field">*</span>Tipo de producto</span>
					                        <select name="type">
					                            <option value="1">Venta</option>
					                            <option value="2">Venta sin inventario</option>
					                            <option value="3">Producción</option>
					                            <option value="4">Operación</option>
					                        </select>
					                    </label>
					                </fieldset>
									<!-- <fieldset class="input-group">
					                    <label data-important>
					                        <select name="components">
					                            <option value="1">Producto simple</option>
					                            <option value="2">Producto compuesto</option>
					                        </select>
					                    </label>
					                </fieldset> -->
					                <fieldset class="input-group">
					                    <label data-important>
					                        <span>Precio base</span>
					                        <input type="number" name="basePrice">
					                    </label>
					                </fieldset>
									<fieldset class="input-group">
					                    <label data-important>
					                        <span>Precio secundario</span>
					                        <input type="number" name="prefPrice">
					                    </label>
					                </fieldset>
					                <fieldset class="input-group">
					                    <label data-important>
					                        <span><span class="required-field">*</span>Precio público</span>
					                        <input type="number" name="publicPrice">
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
					                        <span><span class="required-field">*</span>Tipo de moneda</span>
					                        <select name="coin">
					                            <option value="1">Pesos Mexicanos (MXN)</option>
					                            <option value="2">Dólales Americanos (USD)</option>
					                        </select>
					                    </label>
					                </fieldset>
									<fieldset class="input-group">
					                    <label data-important>
					                        <span><span class="required-field">*</span>Unidad</span>
					                        <select name="unity">
					                            <option value="1">Kilogramos</option>
					                            <option value="2">Gramos</option>
					                            <option value="3">Mililitros</option>
					                            <option value="4">Litros</option>
					                            <option value="5" selected>Piezas</option>
					                        </select>
					                    </label>
					                </fieldset>
					                <div class="upload-image">
					                    <div class="image-preview" image-preview="image-preview"></div>
					                    <a select-image>Seleccionar imagen</a>
										<a clear-image>Eliminar imagen</a>
					                    <input id="image-preview" name="avatar" type="file" accept="image/*" image-preview="image-preview"/>
					                </div>
					                <fieldset class="input-group">
					                    <label data-important>
					                        <span>Categoría 1</span>
					                        <select name="category_one" class="chosen-select">
					                            <option value="">Sin categoría</option>';

					foreach ($categories_one as $category_one)
						$mdlProducts .= '<option value="' . $category_one['id_product_category_one'] . '">' . $category_one['name'] . '</option>';

					$mdlProducts .=
					'						</select>
					                    </label>
					                </fieldset>
									<fieldset class="input-group">
					                    <label data-important>
					                        <span>Categoría 2</span>
					                        <select name="category_two" class="chosen-select">
					                            <option value="">Sin categoría</option>';

					foreach ($categories_two as $category_two)
						$mdlProducts .= '<option value="' . $category_two['id_product_category_two'] . '">' . $category_two['name'] . '</option>';

					$mdlProducts .=
					'						</select>
					                    </label>
					                </fieldset>
									<fieldset class="input-group">
					                    <label data-important>
					                        <span>Categoría 3</span>
					                        <select name="category_tree" class="chosen-select">
					                            <option value="">Sin categoría</option>';

					foreach ($categories_tree as $category_tree)
						$mdlProducts .= '<option value="' . $category_tree['id_product_category_tree'] . '">' . $category_tree['name'] . '</option>';

					$mdlProducts .=
					'						</select>
					                    </label>
					                </fieldset>
									<fieldset class="input-group">
					                    <label data-important>
					                        <span>Categoría 4</span>
					                        <select name="category_four" class="chosen-select">
					                            <option value="">Sin categoría</option>';

					foreach ($categories_four as $category_four)
						$mdlProducts .= '<option value="' . $category_four['id_product_category_four'] . '">' . $category_four['name'] . '</option>';

					$mdlProducts .=
					'						</select>
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

					$mdlImportFromExcel .=
					'<section class="modal" data-modal="importFromExcel">
					    <div class="content">
					        <header>
					            <h6>Importar prospectos desde Excel</h6>
					        </header>
					        <main>
					            <form name="importFromExcel">
					                <fieldset class="input-group">
					                    <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
					                </fieldset>
					                <fieldset class="input-group">
					                    <label data-important>
					                        <span><span class="required-field">*</span>Excel</span>
					                        <input name="xlsx" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
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

				if (Session::getValue('level') == 10)
				{
					$mdlActivateProducts .=
					'<section class="modal alert" data-modal="activateProducts">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea activar está selección de productos?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="activateProducts">Aceptar</a>
					        </footer>
					    </div>
					</section>';

					$mdlDectivateProducts .=
					'<section class="modal alert" data-modal="deactivateProducts">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea desactivar está selección de productos?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="deactivateProducts">Aceptar</a>
					        </footer>
					    </div>
					</section>';

					$mdlDeleteProducts .=
					'<section class="modal alert" data-modal="deleteProducts">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea eliminar está selección de productos?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="deleteProducts">Aceptar</a>
					        </footer>
					    </div>
					</section>';

					$mdlPostProductsToEcommerce .=
					'<section class="modal" data-modal="postProductsToEcommerce">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea publicar está selección de productos para la tienda en linea?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="postProductsToEcommerce">Aceptar</a>
					        </footer>
					    </div>
					</section>';

					$mdlUnpostProductsToEcommerce .=
					'<section class="modal" data-modal="unpostProductsToEcommerce">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea remover está selección de productos de la tienda en linea?</p>
					        </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="unpostProductsToEcommerce">Aceptar</a>
					        </footer>
					    </div>
					</section>';
				}

				$replace = [
					'{$buttons}' => $buttons,
					'{$tblProducts}' => $tblProducts,
					'{$mdlProducts}' => $mdlProducts,
					'{$mdlImportFromExcel}' => $mdlImportFromExcel,
					'{$mdlActivateProducts}' => $mdlActivateProducts,
					'{$mdlDectivateProducts}' => $mdlDectivateProducts,
					'{$mdlDeleteProducts}' => $mdlDeleteProducts,
					'{$mdlPostProductsToEcommerce}' => $mdlPostProductsToEcommerce,
					'{$mdlUnpostProductsToEcommerce}' => $mdlUnpostProductsToEcommerce,
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Importar prospectos desde Excel
	--------------------------------------------------------------------------- */
	public function importFromExcel()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$xlsx = (isset($_FILES['xlsx']['name']) AND !empty($_FILES['xlsx']['name'])) ? $_FILES['xlsx'] : null;

				$errors = [];

				if (!isset($xlsx))
	                array_push($errors, ['xlsx', 'Seleccione un archivo']);

				if (empty($errors))
				{
					$query = $this->model->importFromExcel($xlsx);

					if ($query['status'] == 'success')
					{
						echo json_encode([
							'status' => 'success'
						]);
					}
					else if ($query['status'] == 'error')
					{
						echo json_encode([
							'status' => 'error',
							'labels' => $query['errors']
						]);
					}
					else
					{
						echo json_encode([
							'status' => 'error',
							'labels' => [['xlsx', 'Error desconocido. Pongase en contácto con soporte técnico']]
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
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener producto para editar
	--------------------------------------------------------------------------- */
	public function getProductToEdit($id)
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$product = $this->model->getProductById($id);

	            if (!empty($product))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $product
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Activar y desactivar selección de productos
	--------------------------------------------------------------------------- */
	public function changeStatusProducts($action)
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

					$changeStatusProducts = $this->model->changeStatusProducts($selection, $status);

					if (!empty($changeStatusProducts))
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

	/* Eliminar selección de productos
	--------------------------------------------------------------------------- */
	public function deleteProducts()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$deleteProducts = $this->model->deleteProducts($selection);

					if (!empty($deleteProducts))
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

	/* Publicar selección de productos en tienda en linea
	--------------------------------------------------------------------------- */
	public function postProductsToEcommerce()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$postProductsToEcommerce = $this->model->postProductsToEcommerce($selection);

					if (!empty($postProductsToEcommerce))
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

	/* Remover selección de productos de tienda en linea
	--------------------------------------------------------------------------- */
	public function unpostProductsToEcommerce()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$unpostProductsToEcommerce = $this->model->unpostProductsToEcommerce($selection);

					if (!empty($unpostProductsToEcommerce))
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
			$components = $this->model->getProductById($id)['components'];
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
					define('_title', '{$lang.title} | Dashboard');

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
						$component['product'] = $this->model->getProductById($component['product']);

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
							<td><a data-button-modal="deleteComponent" data-action="sendIdComponentToDelete" data-id="' . $component['product']['id_product'] . '"><i class="material-icons">close</i></a></td>
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
										<p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
									</fieldset>
									<fieldset class="input-group">
										<label data-important>
											<span><span class="required-field">*</span>Producto</span>
											<select name="product" class="chosen-select">
												<option value="">Seleccione una opción</option>';

					foreach ($products as $product)
					{
						if ($product['id_product'] != $id)
							$mdlNewComponent .= '<option value="' . $product['id_product'] . '">[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</option>';
					}

					$mdlNewComponent .=
					'						</select>
										</label>
									</fieldset>
									<fieldset class="input-group">
					                    <label data-important>
					                        <span><span class="required-field">*</span>Cantidad</span>
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
					header('Location: /products');
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Eliminar selección de componentes
	--------------------------------------------------------------------------- */
	public function deleteComponent()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$idProduct = (isset($_POST['idProduct']) AND !empty($_POST['idProduct'])) ? $_POST['idProduct'] : null;
				$idComponent = (isset($_POST['idComponent']) AND !empty($_POST['idComponent'])) ? $_POST['idComponent'] : null;

				$query = $this->model->deleteComponent($idProduct, $idComponent);

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

	/* Imprimir folios de producto
	--------------------------------------------------------------------------- */
	public function tags($idProduct)
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$freeList = (isset($_POST['freeList']) AND !empty($_POST['freeList'])) ? json_decode($_POST['freeList'], true) : null;
				$createFreeList = (isset($_POST['createFreeList']) AND !empty($_POST['createFreeList'])) ? true : false;
				$searchDate = (isset($_POST['searchDate']) AND !empty($_POST['searchDate'])) ? $_POST['searchDate'] : null;
				$establishSearchDate = (isset($_POST['establishSearchDate']) AND !empty($_POST['establishSearchDate'])) ? true : false;
				$itemsNumber = (isset($_POST['itemsNumber']) AND !empty($_POST['itemsNumber'])) ? $_POST['itemsNumber'] : null;
				$printSide = (isset($_POST['printSide']) AND !empty($_POST['printSide'])) ? $_POST['printSide'] : null;

				$errors = [];

				if ($idProduct == 'all' AND $establishSearchDate == true AND !isset($searchDate))
					array_push($errors, ['itemsNumber', 'Seleccione una fecha']);

				if ($idProduct != 'all' AND !isset($itemsNumber))
					array_push($errors, ['itemsNumber', 'No deje este campo vacío']);
				else if ($idProduct != 'all' AND $itemsNumber < 1)
					array_push($errors, ['itemsNumber', 'Ingrese mínimo 1 item para impresión']);
				else if ($idProduct != 'all' AND $itemsNumber > 44)
					array_push($errors, ['itemsNumber', 'Ingrese máximo 44 items para impresión']);
				else if ($idProduct != 'all' AND Security::checkIsFloat($itemsNumber) == true)
				   array_push($errors, ['itemsNumber', 'No ingrese números decimales']);

			   	if (!isset($printSide))
   					array_push($errors, ['printSide', 'No deje este campo vacío']);
   				else if ($printSide != 'A' AND $printSide != 'B')
   					array_push($errors, ['printSide', 'Dato inválido']);

				if (empty($errors))
				{
					$lstTags = '';

					if ($idProduct == 'all')
					{
						if ($createFreeList == true)
							$products = $freeList;
						else
							$products = $this->model->getAllProductsIntoAllInventories(Session::getValue('id_branch_office'), $searchDate);

						$settings = $this->model->getAllSettings();
						$logotype = json_decode($settings['business'], true)['logotype'];

						$length = 0;
						$tags = [];

						foreach ($products as $product)
						{
							$length = $length + $product['exists'];
							$price = json_decode($product['price'], true)['public_price'];

							for ($i = 0; $i < $product['exists']; $i++)
								array_push($tags, [$product['folio'], $price]);
						}

						$render = $length / 44;
						$render = is_float($render) ? intval($render) + 1 : $render;
						$cycle = 1;
						$index = 0;

						for ($a = 1; $a <= $render; $a++)
						{
							$lstTags .=
							'<div class="tags" style="width:100%;height:960px;padding:20px;border:1px solid #000;box-sizing:border-box;margin-bottom:20px;">';

							if ($cycle == $a)
							{
								for ($b = 0; $b < 44; $b++)
								{
									if (isset($tags[$index][0]) AND $tags[$index][1])
									{
										$lstTags .=
										'<div class="tag" style="width:calc(100%/4);height:80px;float:left;display:flex;align-items:center;justify-content:center;flex-direction:column;border:1px dashed #e0e0e0;box-sizing:border-box;">';

										if ($printSide == 'A')
										{
											$lstTags .=
											'<h6 style="padding:0px;margin:0px;font-family:arial;font-weight:300;font-size:11px;">' . $tags[$index][0] . '</h6>
											<h6 style="padding:0px;margin:0px;font-family:arial;font-weight:300;font-size:11px;">$ ' . number_format($tags[$index][1], 2, '.', ',') . ' MXN</h6>';
										}
										else if ($printSide == 'B')
										{
											$lstTags .=
											'<figure style="width:auto;height:60px;overflow:hidden;">
												<img style="width:auto;height:60px;" src="' . ((isset($logotype) AND !empty($logotype)) ? '/images/logotypes/' . $logotype : '/images/isotype.svg') . '" alt="" />
											</figure>';
										}

										$lstTags .=
										'</div>';
									}

									$index = $index + 1;
								}
							}

							$lstTags .=
							'    <div class="clear"></div>
				            </div>';

							$cycle = $cycle + 1;
						}
					}
					else
					{
						$product = $this->model->getProductById($idProduct);

						$price = json_decode($product['price'], true)['public_price'];

						$settings = $this->model->getAllSettings();
						$logotype = json_decode($settings['business'], true)['logotype'];

						$lstTags .=
						'<div class="tags" style="width:100%;height:960px;padding:20px;border:1px solid #000;box-sizing:border-box;margin-bottom:20px;">';

						for ($a = 0; $a < $itemsNumber; $a++)
						{
							$lstTags .=
							'<div class="tag" style="width:calc(100%/4);height:80px;float:left;display:flex;align-items:center;justify-content:center;flex-direction:column;border:1px dashed #e0e0e0;box-sizing:border-box;">';

							if ($printSide == 'A')
							{
								$lstTags .=
								'<h6 style="padding:0px;margin:0px;font-family:arial;font-weight:300;font-size:11px;">' . $product['folio'] . '</h6>
								<h6 style="padding:0px;margin:0px;font-family:arial;font-weight:300;font-size:11px;">$ ' . number_format($price, 2, '.', ',') . ' MXN</h6>';
							}
							else if ($printSide == 'B')
							{
								$lstTags .=
								'<figure style="width:auto;height:60px;overflow:hidden;">
									<img style="width:auto;height:60px;" src="' . ((isset($logotype) AND !empty($logotype)) ? '/images/logotypes/' . $logotype : '/images/isotype.svg') . '" alt="" />
								</figure>';
							}

							$lstTags .=
							'</div>';
						}

						$lstTags .=
						'    <div class="clear"></div>
			            </div>';
					}

					echo json_encode([
						'status' => 'success',
						'html' => $lstTags
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
			{
				define('_title', '{$lang.title} | Dashboard');

				$template = $this->view->render($this, 'tags');
				$template = $this->format->replaceFile($template, 'header');

				$lstTags = '';
				$frmCreateFreeList = '';
				$fdtSearchDate = '';
				$fdtItemsNumber = '';

				if ($idProduct == 'all')
				{
					$products = $this->model->getAllProductsIntoAllInventories(Session::getValue('id_branch_office'));

					$length = 0;
					$tags = [];

					foreach ($products as $product)
					{
						$length = $length + $product['exists'];
						$price = json_decode($product['price'], true)['public_price'];

						for ($i = 0; $i < $product['exists']; $i++)
							array_push($tags, [$product['folio'], $price]);
					}

					$render = $length / 44;
					$render = is_float($render) ? intval($render) + 1 : $render;
					$cycle = 1;
					$index = 0;

					for ($a = 1; $a <= $render; $a++)
					{
						$lstTags .=
						'<div class="tags">';

						if ($cycle == $a)
						{
							for ($b = 0; $b < 44; $b++)
							{
								if (isset($tags[$index][0]) AND $tags[$index][1])
								{
									$lstTags .=
									'<div class="tag">
										<h6>' . $tags[$index][0] . '</h6>
										<h6>$ ' . number_format($tags[$index][1], 2, '.', ',') . ' MXN</h6>
									</div>';
								}

								$index = $index + 1;
							}
						}

						$lstTags .=
						'    <div class="clear"></div>
			            </div>';

						$cycle = $cycle + 1;
					}

					$frmCreateFreeList .=
					'<form name="createFreeList" class="hidden">
	                    <table id="tblFreeList" class="display" data-page-length="100">
	                        <thead>
	                            <tr>
	                                <th>Lista de etiquetas</th>
	                                <th width="30px"></th>
	                                <th width="30px"></th>
	                            </tr>
	                        </thead>
	                        <tbody>

	                        </tbody>
	                    </table>
	                    <fieldset class="input-group span8 pr">
	                        <label data-important>
	                            <span>Folio</span>
								<select name="folio" class="chosen-select">
									<option value=""></option>';

					$products = $this->model->getAllProducts();

					foreach ($products as $value)
						$frmCreateFreeList .= '<option value="' . $value['folio'] . '">[' . $value['folio'] . '] ' . $value['name'] . '</option>';

					$frmCreateFreeList .=
					'			</select>
	                        </label>
	                    </fieldset>
	                    <fieldset class="input-group span4">
	                        <label data-important>
	                            <span>Numero</span>
	                            <input type="number" name="itemsNumber">
	                        </label>
	                    </fieldset>
						<div class="clear"></div>
	                    <fieldset class="input-group">
							<a data-action="addToFreeList" class="color-gray">Agregar a la lista</a>
	                    </fieldset>
	                </form>';

					$fdtSearchDate .=
					'<fieldset class="input-group">
	                    <label data-important>
	                        <span>Fecha</span>
	                        <input type="date" name="searchDate" disabled>
	                    </label>
	                    <label class="checkbox" data-important>
	                        <input type="checkbox" name="establishSearchDate">
	                        <span>Establecer fecha de búsqueda</span>
	                    </label>
	                </fieldset>';
				}
				else
				{
					$product = $this->model->getProductById($idProduct);

					$price = json_decode($product['price'], true)['public_price'];

					$lstTags .=
					'<div class="tags">';

					for ($a = 0; $a < 44; $a++)
					{
						$lstTags .=
						'<div class="tag">
							<h6>' . $product['folio'] . '</h6>
							<h6>$ ' . number_format($price, 2, '.', ',') . ' MXN</h6>
						</div>';
					}

					$lstTags .=
					'    <div class="clear"></div>
		            </div>';

					$fdtItemsNumber .=
					'<fieldset class="input-group">
	                    <label data-important>
	                        <span>Número items por impresión</span>
	                        <input type="number" name="itemsNumber" value="44" min="1" max="44">
	                    </label>
	                </fieldset>';
				}

				$replace = [
					'{$lstTags}' => $lstTags,
					'{$frmCreateFreeList}' => $frmCreateFreeList,
					'{$fdtSearchDate}' => $fdtSearchDate,
					'{$fdtItemsNumber}' => $fdtItemsNumber
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/*
	--------------------------------------------------------------------------- */
	public function createFreeList()
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$folio = (isset($_POST['folio']) AND !empty($_POST['folio'])) ? $_POST['folio'] : null;
				$itemsNumber = (isset($_POST['itemsNumber']) AND !empty($_POST['itemsNumber'])) ? $_POST['itemsNumber'] : null;

				$errors = [];

				if (!isset($folio))
					array_push($errors, ['folio', 'No deje este campo vacío']);

				if (!isset($itemsNumber))
					array_push($errors, ['itemsNumber', 'No deje este campo vacío']);
				else if ($itemsNumber < 1)
					array_push($errors, ['itemsNumber', 'Ingrese mínimo 1 item para impresión']);
				else if (Security::checkIsFloat($itemsNumber) == true)
				   array_push($errors, ['itemsNumber', 'No ingrese números decimales']);

				if (empty($errors))
				{
					$product = $this->model->getProductByFolio($folio);

					if (isset($product) AND !empty($product))
					{
						$product = [
							'folio' => $product['folio'],
							'price' => $product['price'],
							'exists' => $itemsNumber
						];

						echo json_encode([
							'status' => 'success',
							'data' => $product
						]);
					}
					else
					{
						echo json_encode([
							'status' => 'error',
							'labels' => [['folio', 'Este folio no existe']]
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

	/* Lista de categorías, nuevo y editar categoría
	--------------------------------------------------------------------------- */
	public function categories_one()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action = $_POST['action'];
				$number = $_POST['number'];
				$id = ($action == 'edit') ? $_POST['id'] : null;

				$name = (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
				$avatar = ($number == 'one' AND isset($_FILES['avatar']['name']) AND !empty($_FILES['avatar']['name'])) ? $_FILES['avatar'] : null;

				$errors = [];

				if (!isset($name))
					array_push($errors, ['name', 'No deje este campo vacío']);

				if (empty($errors))
	            {
	                $exist = $this->model->checkExistCategory($id, $name, $action, $number);

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
							$query = $this->model->newCategory($name, $number, $avatar);
						else if ($action == 'edit')
							$query = $this->model->editCategory($id, $name, $number, $avatar);

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

				$template = $this->view->render($this, 'categories_one');
				$template = $this->format->replaceFile($template, 'header');

				$categories = $this->model->getAllCategories('one');

				$lstCategories = '';

				foreach ($categories as $category)
				{
					$lstCategories .=
					'<tr>
						<td><input type="checkbox" data-check value="' . $category['id_product_category_one'] . '_one" /></td>
						<td>' . (!empty($category['avatar']) ? '<a href="{$path.images}products/categories/' . $category['avatar'] . '" class="fancybox-thumb" rel="fancybox-thumb"><img src="{$path.images}products/categories/' . $category['avatar'] . '" /></a>' : '<img src="{$path.images}empty.png" class="emptyAvatar" />') . '</td>
						<td>' . $category['name'] . '</td>
						<td><a data-action="getCategoryToEdit" data-id="' . $category['id_product_category_one'] . '" data-number="one"><i class="material-icons">edit</i><span>Detalles / Editar</span></a></td>
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

	public function categories_two()
	{
		if (Session::getValue('level') == 10)
		{
			define('_title', '{$lang.title} | Dashboard');

			$template = $this->view->render($this, 'categories_two');
			$template = $this->format->replaceFile($template, 'header');

			$categories = $this->model->getAllCategories('two');

			$lstCategories = '';

			foreach ($categories as $category)
			{
				$lstCategories .=
				'<tr>
					<td><input type="checkbox" data-check value="' . $category['id_product_category_two'] . '_two" /></td>
					<td>' . $category['name'] . '</td>
					<td><a data-action="getCategoryToEdit" data-id="' . $category['id_product_category_two'] . '" data-number="two"><i class="material-icons">edit</i><span>Detalles / Editar</span></a></td>
				</tr>';
			}

			$replace = [
				'{$lstCategories}' => $lstCategories
			];

			$template = $this->format->replace($replace, $template);

			echo $template;
		}
		else
			header('Location: /dashboard');
	}

	public function categories_tree()
	{
		if (Session::getValue('level') == 10)
		{
			define('_title', '{$lang.title} | Dashboard');

			$template = $this->view->render($this, 'categories_tree');
			$template = $this->format->replaceFile($template, 'header');

			$categories = $this->model->getAllCategories('tree');

			$lstCategories = '';

			foreach ($categories as $category)
			{
				$lstCategories .=
				'<tr>
					<td><input type="checkbox" data-check value="' . $category['id_product_category_tree'] . '_tree" /></td>
					<td>' . $category['name'] . '</td>
					<td><a data-action="getCategoryToEdit" data-id="' . $category['id_product_category_tree'] . '" data-number="tree"><i class="material-icons">edit</i><span>Detalles / Editar</span></a></td>
				</tr>';
			}

			$replace = [
				'{$lstCategories}' => $lstCategories
			];

			$template = $this->format->replace($replace, $template);

			echo $template;
		}
		else
			header('Location: /dashboard');
	}

	public function categories_four()
	{
		if (Session::getValue('level') == 10)
		{
			define('_title', '{$lang.title} | Dashboard');

			$template = $this->view->render($this, 'categories_four');
			$template = $this->format->replaceFile($template, 'header');

			$categories = $this->model->getAllCategories('four');

			$lstCategories = '';

			foreach ($categories as $category)
			{
				$lstCategories .=
				'<tr>
					<td><input type="checkbox" data-check value="' . $category['id_product_category_four'] . '_four" /></td>
					<td>' . $category['name'] . '</td>
					<td><a data-action="getCategoryToEdit" data-id="' . $category['id_product_category_four'] . '" data-number="four"><i class="material-icons">edit</i><span>Detalles / Editar</span></a></td>
				</tr>';
			}

			$replace = [
				'{$lstCategories}' => $lstCategories
			];

			$template = $this->format->replace($replace, $template);

			echo $template;
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
				$number = $_POST['number'];
				$category = $this->model->getCategoryById($id, $number);

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

	/* Lista de productos ligados, nuevo y editar producto ligado
	--------------------------------------------------------------------------- */
	public function flirts()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action = $_POST['action'];
				$id = ($action == 'edit') ? $_POST['id'] : null;

				$product_1 = (isset($_POST['product_1']) AND !empty($_POST['product_1'])) ? $_POST['product_1'] : null;
				$product_2 = (isset($_POST['product_2']) AND !empty($_POST['product_2'])) ? $_POST['product_2'] : null;
				$stock_base = (isset($_POST['stock_base']) AND !empty($_POST['stock_base'])) ? $_POST['stock_base'] : null;

				$errors = [];

				if (!isset($product_1))
					array_push($errors, ['product_1', 'No deje este campo vacío']);

				if (!isset($product_2))
					array_push($errors, ['product_2', 'No deje este campo vacío']);

				if (!isset($stock_base))
					array_push($errors, ['stock_base', 'No deje este campo vacío']);

				if (empty($errors))
	            {
	                $exist = $this->model->checkExistFlirt($id, $product_1, $product_2, $action);

	                if ($exist == true)
	                {
						array_push($errors, ['product_1', 'Este registro ya existe']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
	                }
	                else
	                {
						if ($action == 'new')
							$query = $this->model->newFlirt($product_1, $product_2, $stock_base);
						else if ($action == 'edit')
							$query = $this->model->editFlirt($id, $product_1, $product_2, $stock_base);

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

				$template = $this->view->render($this, 'flirts');
				$template = $this->format->replaceFile($template, 'header');

				$flirts = $this->model->getAllFlirts();

				$lstFlirts = '';

				foreach ($flirts as $flirt)
				{
					if (!empty($flirt['product_1']['id_product_category_one']))
					{
						$categoryOne1 = $this->model->getCategoryById($flirt['product_1']['id_product_category_one'], 'one');
						$categoryOne1 = ' ' . $categoryOne1['name'];
					}
					else
						$categoryOne1 = '';

					if (!empty($flirt['product_1']['id_product_category_two']))
					{
						$categoryTwo1 = $this->model->getCategoryById($flirt['product_1']['id_product_category_two'], 'two');
						$categoryTwo1 = ' - ' . $categoryTwo1['name'];
					}
					else
						$categoryTwo1 = '';

					if (!empty($flirt['product_1']['id_product_category_tree']))
					{
						$categoryTree1 = $this->model->getCategoryById($flirt['product_1']['id_product_category_tree'], 'tree');
						$categoryTree1 = ' - ' . $categoryTree1['name'];
					}
					else
						$categoryTree1 = '';

					if (!empty($flirt['product_1']['id_product_category_four']))
					{
						$categoryFour1 = $this->model->getCategoryById($flirt['product_1']['id_product_category_four'], 'four');
						$categoryFour1 = ' - ' . $categoryFour1['name'];
					}
					else
						$categoryFour1 = '';

					if (!empty($flirt['product_2']['id_product_category_one']))
					{
						$categoryOne2 = $this->model->getCategoryById($flirt['product_2']['id_product_category_one'], 'one');
						$categoryOne2 = ' ' . $categoryOne2['name'];
					}
					else
						$categoryOne2 = '';

					if (!empty($flirt['product_2']['id_product_category_two']))
					{
						$categoryTwo2 = $this->model->getCategoryById($flirt['product_2']['id_product_category_two'], 'two');
						$categoryTwo2 = ' - ' . $categoryTwo2['name'];
					}
					else
						$categoryTwo2 = '';

					if (!empty($flirt['product_2']['id_product_category_tree']))
					{
						$categoryTree2 = $this->model->getCategoryById($flirt['product_2']['id_product_category_tree'], 'tree');
						$categoryTree2 = ' - ' . $categoryTree2['name'];
					}
					else
						$categoryTree2 = '';

					if (!empty($flirt['product_2']['id_product_category_four']))
					{
						$categoryFour2 = $this->model->getCategoryById($flirt['product_2']['id_product_category_four'], 'four');
						$categoryFour2 = ' - ' . $categoryFour2['name'];
					}
					else
						$categoryFour2 = '';

					$lstFlirts .=
					'<tr>
						<td><input type="checkbox" data-check value="' . $flirt['id_product_flirt'] . '" /></td>
						<td>' . $flirt['product_1']['name'] . $categoryOne1 . $categoryTwo1 . $categoryTree1 . $categoryFour1 . '</td>
						<td>' . $flirt['product_2']['name'] . $categoryOne2 . $categoryTwo2 . $categoryTree2 . $categoryFour2 . '</td>
						<td>' . $flirt['stock_base'] . '</td>
						<td>' . $flirt['stock_actual'] . '</td>
						<td><a data-action="getFlirtToEdit" data-id="' . $flirt['id_product_flirt'] . '"><i class="material-icons">edit</i><span>Detalles / Editar</span></a></td>
					</tr>';
				}

				$products = $this->model->getAllProductsByType(1);

				$lstProducts = '';

				foreach ($products as $value)
					$lstProducts .= '<option value="' . $value['id_product'] . '">' . $value['name'] . '</option>';

				$replace = [
					'{$lstFlirts}' => $lstFlirts,
					'{$lstProducts}' => $lstProducts
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener producto ligado para editar
	--------------------------------------------------------------------------- */
	public function getFlirtToEdit($id)
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$flirt = $this->model->getFlirtById($id);

	            if (!empty($flirt))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $flirt
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Eliminar selección de productos ligados
	--------------------------------------------------------------------------- */
	public function deleteFlirts()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$deleteFlirts = $this->model->deleteFlirts($selection);

					if (!empty($deleteFlirts))
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
