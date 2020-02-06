<?php

defined('_EXEC') or die;

class Quotations_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Lista de ventas
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
        {
			define('_title', '{$lang.title} | Dashboard');

			$template = $this->view->render($this, 'index');
			$template = $this->format->replaceFile($template, 'header');

			if (Session::getValue('level') == 10)
				$sales = $this->model->getAllSales();
			else if (Session::getValue('level') == 7 OR Session::getValue('level') == 9)
			{
				$userLogged = $this->model->getUserLogged();
				$sales = $this->model->getAllSalesByBranchOffice($userLogged['id_branch_office']);
			}

			$lstSales =
			'<table id="salesTable" class="display" data-page-length="100">
				<thead>
					<tr>
						<th>Folio</th>
						<th>Fecha</th>
						<th>Expiración</th>
						<th>Cliente / Prospecto</th>
						<th>Total</th>
						' . ((Session::getValue('level') == 10) ? '<th>Sucursal</th>' : '') . '
						<th width="100px">Estado</th>
						<th width="35px"></th>
					</tr>
				</thead>
				<tbody>';

			foreach ($sales as $sale)
			{
				$client = $this->model->getClientById($sale['id_client']);
				$totals = json_decode($sale['totals'], true);

				if (Session::getValue('level') == 10)
					$branchOffice = $this->model->getBranchOfficeById($sale['id_branch_office']);

				if ($sale['date_expiration'] >= date('Y-m-d', time()))
					$status = '<span class="active">Activa</span>';
				else
					$status = '<span class="expired">Expirada</span>';

				$lstSales .=
				'<tr>
					<td>' . $sale['folio'] . '</td>
					<td>' . $sale['date_time'] . '</td>
					<td>' . $sale['date_expiration'] . '</td>
					<td>' . $client['name'] . '</td>
					<td>$ ' . $totals['total'] . ' ' . $totals['mainCoin'] . '</td>
					' . ((Session::getValue('level') == 10) ? '<th>' . $branchOffice['name'] . '</th>' : '') . '
					<td>' . $status . '</td>
					<td><a href="/quotations/view/' . $sale['id_quotation'] . '"><i class="material-icons">more_horiz</i><span>Detalles</span></a></td>
				</tr>';
			}

			$lstSales .=
			'    </tbody>
			</table>';

			$replace = [
				'{$lstSales}' => $lstSales
			];

			$template = $this->format->replace($replace, $template);

			echo $template;
        }
        else
            header('Location: /dashboard');
	}

	/* Obtener todas las configuraciones de venta
	--------------------------------------------------------------------------- */
	public function getAllSalesSettings()
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$settings = $this->model->getAllSettings();

                echo json_encode([
					'status' => 'success',
					'data' => json_decode($settings['sales'], true)
				]);
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Nueva venta
	--------------------------------------------------------------------------- */
	public function add()
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
        {
			$settings = $this->model->getAllSettings();

			$applyDiscounds = json_decode($settings['sales'], true)['apply_discounds'];
			$deferred_payments = json_decode($settings['sales'], true)['deferred_payments'];

			$mainCoin = json_decode($settings['sales'], true)['main_coin'];
			$ivaRate = json_decode($settings['sales'], true)['iva_rate'];
			$usdRate = json_decode($settings['sales'], true)['usd_rate'];

			$today = date('Y-m-d');

            if (Format::existAjaxRequest() == true)
            {
				$total 				= (isset($_POST['total']) AND !empty($_POST['total'])) ? $_POST['total'] : 0;
				$mxnTotal 			= (isset($_POST['mxnTotal']) AND !empty($_POST['mxnTotal'])) ? $_POST['mxnTotal'] : 0;
				$usdTotal 			= (isset($_POST['usdTotal']) AND !empty($_POST['usdTotal'])) ? $_POST['usdTotal'] : 0;
				$payment 			= (isset($_POST['payment']) AND !empty($_POST['payment'])) ? $_POST['payment'] : null;
				$sales				= (isset($_POST['sales']) AND !empty($_POST['sales'])) ? json_decode($_POST['sales'], true) : null;

				if (Session::getValue('level') >= 9)
					$user = (isset($_POST['user']) AND !empty($_POST['user'])) ? $_POST['user'] : null;
				else if (Session::getValue('level') == 7)
					$user = Session::getValue('id_user');

				$client = (isset($_POST['client']) AND !empty($_POST['client'])) ? $_POST['client'] : null;
				$expiration = (isset($_POST['expiration']) AND !empty($_POST['expiration'])) ? $_POST['expiration'] : null;

				$errors = [];

				if ($total < 0)
					array_push($errors, ['total', 'No ingrese números negativos']);

				if ($mxnTotal < 0)
					array_push($errors, ['mxnTotal', 'No ingrese números negativos']);

				if ($usdTotal < 0)
					array_push($errors, ['usdTotal', 'No ingrese números negativos']);

				if (isset($payment) AND $payment != 'other' AND isset($payment) AND $payment != 'deferred')
					array_push($errors, ['payment', 'Opción no válida']);

				if ($payment == 'deferred')
				{
					$num_deferred_payments = (isset($_POST['num_deferred_payments']) AND !empty($_POST['num_deferred_payments'])) ? $_POST['num_deferred_payments'] : null;

					if ($num_deferred_payments < 2)
						array_push($errors, ['num_deferred_payments', 'Mínimo 2 pagos diferidos']);
					else if (!is_numeric($num_deferred_payments))
						array_push($errors, ['num_deferred_payments', 'Ingrese únicamente números']);

					$deferred_payments_total = 0;

					for ($i = 1; $i <= $num_deferred_payments; $i++)
					{
						if (!isset($_POST['deferred_payment_' . $i]) AND empty($_POST['deferred_payment_' . $i]))
							array_push($errors, ['deferred_payment_' . $i, 'Ingrese el monto ' . $i]);
						else if ($_POST['deferred_payment_' . $i] == 0)
							array_push($errors, ['deferred_payment_' . $i, 'Ingrese el monto ' . $i]);
						else if ($_POST['deferred_payment_' . $i] < 0)
							array_push($errors, ['deferred_payment_' . $i, 'No ingrese números negativos']);
						else if (!is_numeric($_POST['deferred_payment_' . $i]))
							array_push($errors, ['deferred_payment_' . $i, 'Ingrese únicamente números']);
						else
							$deferred_payments_total = $deferred_payments_total + $_POST['deferred_payment_' . $i];
					}

					if ($deferred_payments_total != $total)
						array_push($errors, ['num_deferred_payments', 'Los pagos diferidos no coincide con el total']);
				}

				if (empty($sales))
					array_push($errors, ['total', 'No se han ingresado ventas']);

				if (Session::getValue('level') >= 9 AND !isset($user))
					array_push($errors, ['user', 'Seleccione una opción']);

				if (!isset($client))
					array_push($errors, ['client', 'Seleccione una opción']);

				if (!isset($expiration))
					array_push($errors, ['expiration', 'Seleccione una fecha']);
				else if ($expiration <= $today)
					array_push($errors, ['expiration', 'Dato inválido']);

				if (empty($errors))
				{
					foreach ($sales as $sale)
					{
						$branchOffice = $sale['branchOffice'];
					}

					$ticketFolio = strtoupper($this->security->randomString(6));

					$iva = ($total / 100) * $ivaRate;
					$subtotal = $total - $iva;

					$totals = json_encode([
						'subtotal' => $subtotal,
						'iva' => $iva,
						'total' => $total,
						'mxnTotal' => $mxnTotal,
						'usdTotal' => $usdTotal,
						'mainCoin' => $mainCoin
					]);

					if ($payment == 'deferred')
					{
						$deferred_payments_array = [
							'num_deferred_payments' => $num_deferred_payments,
							'deferred_payments' => []
						];

						for ($i = 1; $i <= $num_deferred_payments; $i++)
						{
							array_push($deferred_payments_array['deferred_payments'], [
								'pay' => $_POST['deferred_payment_' . $i],
								'payment_date' => ''
							]);
						}

						$deferred_payments_array = json_encode($deferred_payments_array);
					}
					else
						$deferred_payments_array = null;

					$sales = json_encode($sales);

					$ticketDate = Format::getDateHour();

					$query = $this->model->newSale($ticketFolio, $totals, $deferred_payments_array, $sales, $ticketDate, $expiration, $user, $client, $branchOffice);

					if (!empty($query))
					{
						$settings = $this->model->getAllSettings();
						$client = $this->model->getClientById($client);
						$branchOffice = $this->model->getBranchOfficeById($branchOffice);
						$user = $this->model->getUserById($user);
						$sales = json_decode($sales, true);
						$totals = json_decode($totals, true);

						$message =
						'<div style="width:800px; padding:20px; box-sizing: border-box; background-color:#f2f2f2;">
							<div style="width:100%; padding:20px; box-sizing: border-box; background-color:#fff;">
								<figure style="width:100%; height:100px; margin-bottom:40px;"><img style="width:auto; height:100%;" src="https://sofierp.com/images/logotypes/' . json_decode($settings['business'], true)['logotype'] . '" /></figure>
								<h2 style="padding:0px; margin:50px 0px; font-family:century gothic; font-weight:200; text-align:center;">COTIZACIÓN</h2>
								<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">' . json_decode($settings['business'], true)['name'] . '</h4>
								<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">Folio: ' . $ticketFolio . '</h4>
								<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">Fecha: ' . $ticketDate . '</h4>
								<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">Fecha de vencimiento: ' . $expiration . '</h4>
							 	<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">Cliente: ' . $client['name'] . '</h4>
							 	<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">Sucursal: ' . $branchOffice['name'] . '</h4>
							 	<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">Vendedor: ' . $user['name'] . '</h4>
								<table style="width: 100%; padding:0px; margin:0px; font-family:century gothic; font-weight:200; text-align:center;">
									<thead>
										<tr>
											<th style="padding:20px 0px; margin:0px; text-transform:uppercase; border-bottom: 1px solid #2196F3;">Cant.</th>
											<th style="padding:20px 0px; margin:0px; text-transform:uppercase; border-bottom: 1px solid #2196F3;">Folio</th>
											<th style="padding:20px 0px; margin:0px; text-transform:uppercase; border-bottom: 1px solid #2196F3;">Descripción</th>
											<th style="padding:20px 0px; margin:0px; text-transform:uppercase; border-bottom: 1px solid #2196F3;">Precio</th>
											<th style="padding:20px 0px; margin:0px; text-transform:uppercase; border-bottom: 1px solid #2196F3;">Descuento</th>
											<th style="padding:20px 0px; margin:0px; text-transform:uppercase; border-bottom: 1px solid #2196F3;">Total</th>
										</tr>
									</thead>
									<tbody style="">';

						foreach ($sales as $sale)
						{
							if ($sale['object']['coin'] == '1')
								$coin = 'MXN';
							else if ($sale['object']['coin'] == '2')
								$coin = 'USD';

							$message .=
							'<tr>
								<td style="padding:20px 0px; margin:0px;">' . $sale['quantity'] . '</td>
								<td style="padding:20px 0px; margin:0px;">' . $sale['object']['folio'] . '</td>
								<td style="padding:20px 0px; margin:0px;">' . $sale['object']['name'] . '</td>
								<td style="padding:20px 0px; margin:0px;">$ ' . $sale['totals']['price'] . ' ' . $coin . '</td>
								<td style="padding:20px 0px; margin:0px;">$ ' . $sale['totals']['discount'] . ' ' . $coin . '</td>
								<td style="padding:20px 0px; margin:0px;">$ ' . $sale['totals']['total'] . ' ' . $coin . '</td>
							</tr>';
						}

						$message .=
						'			</tbody>
								</table>
								<h4 style="padding:0px; margin:0px; margin-top:50px; font-family:century gothic; font-weight:900; text-transform:uppercase; text-align:right;">Totales</h4>
								<h4 style="padding:0px; margin:0px; font-family:century gothic; font-weight:200; text-align:right;">$ ' . number_format($totals['mxnTotal'], 2, '.', '') . ' MXN</h4>
								<h4 style="padding:0px; margin:0px; font-family:century gothic; font-weight:200; text-align:right;">$ ' . number_format($totals['usdTotal'], 2, '.', '') . ' USD</h4>
								<h4 style="padding:0px; margin:0px; margin-top:50px; font-family:century gothic; font-weight:900; text-transform:uppercase; text-align:right;">Gran Total</h4>
								<h4 style="padding:0px; margin:0px; margin-bottom:50px; font-family:century gothic; font-weight:200; text-align:right;">$ ' . number_format($totals['total'], 2, '.', '') . ' ' . json_decode($settings['sales'], true)['main_coin'] . '</h4>
								<h4 style="padding:0px; margin:0px; font-family:century gothic; font-weight:200; text-align:center;">' . json_decode($settings['business'], true)['website'] . '</h4>
							</div>
						</div>';

						$sendEmail = $this->model->sendEmail($client['email'], $client['name'], json_decode($settings['business'], true)['name'] . ' - Cotización', $message);

						echo json_encode([
							'status' => 'success'
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
				define('_title', '{$lang.title} | Dashboard');

                $template = $this->view->render($this, 'add');
                $template = $this->format->replaceFile($template, 'header');

				$products = $this->model->getAllProducts();
				$services = $this->model->getAllServices();
				$clients = $this->model->getAllClients();

				$html = '';
				$lstProductsAndServices = '';
				$productsAndServices = [];

				$html .=
				'<form name="searchToSell">';

				if (Session::getValue('level') == 10)
				{
					$branchOffices = $this->model->getAllBranchOffices();

					$html .=
					'<fieldset class="input-group">
                        <label data-important>
                            <span>Sucursal</span>
                            <select name="branchOffice" class="chosen-select">';

					foreach ($branchOffices as $branchOffice)
						$html .= '<option value="' . $branchOffice['id_branch_office'] . '">' . $branchOffice['name'] . '</option>';

                    $html .=
                    '        </select>
                        </label>
                    </fieldset>';
				}

				$html .=
				'<fieldset class="input-group span2 pr">
                    <label data-important>
                        <span>Cantidad</span>
                        <input type="number" name="quantity" value="1" min="1">
                    </label>
                </fieldset>
                <fieldset class="input-group span10">
                    <label data-important>
                        <span>Folio</span>
                        <input type="text" name="folio" class="uppercase" autofocus>
                    </label>';

				if ($applyDiscounds == 'true')
				{
					$html .=
					'<label class="checkbox" data-important>
						<input id="additionalDiscount" type="checkbox" name="additionalDiscount">
						<span>Aplicar descuento</span>
						<div class="clear"></div>
					</label>';
				}

				$html .=
				'		<div class="space10"></div>
                        <a data-action="searchToSell">Agregar</a>
                    </fieldset>
                    <div class="clear"></div>
                </form>
				<form name="applyAdditionalDiscount" class="hidden">
					<fieldset class="input-group span2 pr">
						<label data-important>
							<span>Cantidad</span>
							<input type="text" name="dQuantity" disabled>
						</label>
					</fieldset>
					<fieldset class="input-group span6 pr">
						<label data-important>
							<span>Descripción</span>
							<input type="text" name="dDescription" disabled>
						</label>
					</fieldset>
					<fieldset class="input-group span2 pr">
						<label data-important>
							<span>Précio</span>
							<input type="text" name="dPrice" disabled>
						</label>
					</fieldset>
					<fieldset class="input-group span2 pr">
						<label data-important>
							<span>Total</span>
							<input type="text" name="dTotal" disabled>
						</label>
					</fieldset>
					<div class="span8 pr"></div>
					<fieldset class="input-group span2 pr">
						<label data-important>
							<span>Descuento</span>
							<input type="number" name="additionalDiscountQuantity" value="">
						</label>
					</fieldset>
					<fieldset class="input-group span2 pr">
						<label data-important>
							<span>Tipo</span>
							<select name="additionalDiscountType">
								<option value="">Sin descuento</option>
								<option value="1">(%) Porcentaje</option>
								<option value="2">($) Dinero</option>
							</select>
		                </label>
					</fieldset>
					<div class="clear"></div>
					<fieldset class="input-group">
						<a data-action="applyAdditionalDiscount">Aceptar</a>
						<a data-action="cancelApplyAdditionalDiscount">Cancelar</a>
					</fieldset>
				</form>
                <div class="table-responsive-vertical">
                    <table id="addItemsSaleTable" class="display" data-page-length="100"></table>
                </div>
                <form name="makePayment">
					<div class="span4 pr">
						<fieldset class="input-group">
							<label data-important>
								<span>Fecha de vencimiento</span>
								<input type="date" name="expiration" />
							</label>
						</fieldset>';

				if ($deferred_payments == 'true')
				{
					$html .=
					'<fieldset class="input-group">
						<label data-important>
							<span>Pagos diferidos</span>
							<select name="payment">
								<option value="other">No</option>
								<option value="deferred">Si</option>
							</select>
						</label>
					</fieldset>
					<fieldset class="input-group hidden">
                        <label data-important>
                            <span>Número de pagos diferidos</span>
                            <input type="number" name="num_deferred_payments" value="2" min="2" />
                        </label>
                    </fieldset>
					<div id="deferred_payments" class="hidden"></div>
					<div class="clear"></div>';
				}

				$html .=
				'</div>
				<div class="span4">
					<fieldset class="input-group">
						<label data-important>
							<span>Cliente / Prospecto</span>
							<select name="client" class="chosen-select">
								<option value="">Sin cliente</option>';

				foreach ($clients as $client)
					$html .= '<option value="' . $client['id_client'] . '">' . $client['name'] . '</option>';

				$html .=
				'		</select>
					</label>
				</fieldset>';

				if (Session::getValue('level') >= 9)
				{
					$users = $this->model->getAllUsersByLevel(['10','9','7']);

					$html .=
					'<fieldset class="input-group">
						<label data-important>
							<span>Vendedor</span>
							<select name="user" class="chosen-select">';

					foreach ($users as $user)
						$html .= '<option value="' . $user['id_user'] . '" ' . (($user['id_user'] == Session::getValue('id_user')) ? 'selected ' : '') . '>' . (($user['id_user'] == Session::getValue('id_user')) ? '[Yo] ' : '') . $user['name'] . '</option>';

					$html .=
					'        </select>
						</label>
					</fieldset>';
				}

				$html .=
				'</div>
                 <div class="span4 pl">
					<fieldset class="input-group span6 pr">
					 <label data-important>
						 <span>Total MXN</span>
						 <input type="text" name="mxnTotal" value="$ 0.00 MXN" disabled>
					 </label>
					</fieldset>
					<fieldset class="input-group span6">
					 <label data-important>
						 <span>Total USD</span>
						 <input type="text" name="usdTotal" value="$ 0.00 USD" disabled>
					 </label>
					</fieldset>
					<div class="clear"></div>
                    <fieldset class="input-group">
                        <label data-important>
                            <span>Gran total</span>
                            <input type="text" name="total" class="uppercase" value="$ 0.00 ' . $mainCoin . '" disabled>
                        </label>
						<a data-action="makePayment">Guardar y enviar</a>
					</fieldset>
                </div>
                <div class="clear"></div>
            </form>';

				foreach ($products as $product)
					array_push($productsAndServices, $product);

				foreach ($services as $service)
					array_push($productsAndServices, $service);

				foreach ($productsAndServices as $item)
				{
					if (isset($item['id_product']))
					{
						$item['name'] = $item['name'] . ' ' . $item['category_one'] . ' ' . $item['category_two'] . ' ' . $item['category_tree'] . ' ' . $item['category_four'];
						$type = 'Producto';
					}
					else if (isset($item['id_service']))
					{
						$item['name'] = $item['name'] . ' ' . $item['category'];
						$type = 'Servicio';
					}

					$lstProductsAndServices .=
					'<tr>
						<td>[' . $item['folio'] . '] ' . $item['name'] . '</td>
						<td>' . $type . '</td>
						<td><a data-action="loadFolioToSearch" data-folio="' . $item['folio'] . '"><i class="material-icons">add</i><span>Agregar</span></a></td>
					</tr>';
				}

                $replace = [
					'{$html}' => $html,
					'{$lstProductsAndServices}' => $lstProductsAndServices
                ];

                $template = $this->format->replace($replace, $template);

                echo $template;
            }
        }
        else
            header('Location: /dashboard');
	}

	/* Buscar producto/servicio para vender
	--------------------------------------------------------------------------- */
	public function searchToSell()
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
        {
            if (Format::existAjaxRequest() == true)
            {
				$userLogged = $this->model->getUserLogged();
				$settings = $this->model->getAllSettings();
				$sync_quotations_with_inventories = json_decode($settings['sales'], true)['sync_quotations_with_inventories'];

				$folio = (isset($_POST['folio']) AND !empty($_POST['folio'])) ? $_POST['folio'] : null;
				$additionalDiscount	= (isset($_POST['additionalDiscount']) AND !empty($_POST['additionalDiscount'])) ? true : false;
				$quantity = (isset($_POST['quantity']) AND !empty($_POST['quantity'])) ? $_POST['quantity'] : null;

				if (Session::getValue('level') == 10)
					$branchOffice = (isset($_POST['branchOffice']) AND !empty($_POST['branchOffice'])) ? $_POST['branchOffice'] : null;
				else if (Session::getValue('level') == 7 OR Session::getValue('level') == 9)
					$branchOffice = $userLogged['id_branch_office'];

				$errors = [];

				if (!isset($quantity))
					array_push($errors, ['quantity', 'No deje este campo vacío']);
				else if (!is_numeric($quantity))
					array_push($errors, ['quantity', 'Ingrese únicamente números']);

				if (!isset($folio))
					array_push($errors, ['folio', 'No deje este campo vacío']);

				if (Session::getValue('level') == 10 AND !isset($branchOffice))
					array_push($errors, ['branchOffice', 'Seleccione una opción']);

				if (empty($errors))
				{
					$product = $this->model->getProductByFolio($folio);
					$service = $this->model->getServiceByFolio($folio);

					$firstPermitted = false;
					$secondPermitted = true;
					$thirdPermitted = true;
					$fourthPermitted = false;
					$fifthPermitted = false;
					$sixthPermitted = false;

					if (!empty($product) AND empty($service))
					{
						$components = [];

						if ($sync_quotations_with_inventories == 'true')
						{
							if (isset($product['components']) AND !empty($product['components']))
							{
								$components = json_decode($product['components'], true);

								foreach ($components as $component)
								{
									$inventoryToDiscount = $this->model->getInventoryToDiscount($component['product'], $branchOffice);

									if (!empty($inventoryToDiscount))
									{
										$existIntoInventory = $this->model->checkExistIntoInventory($component['quantity'], $component['product'], $inventoryToDiscount);

										if ($existIntoInventory['status'] == true)
										{
											$index = array_search($component, $components);
											unset($components[$index]);
											array_push($components,  ['quantity' => $component['quantity'], 'product' => $component['product'], 'inventory' => $inventoryToDiscount]);
										}
										else
											$thirdPermitted = false;
									}
									else
										$secondPermitted = false;
								}

								if ($secondPermitted == true AND $thirdPermitted == true)
									$firstPermitted = true;
							}
							else
								$firstPermitted = true;
						}
						else
							$firstPermitted = true;

						if ($firstPermitted == true)
						{
							if ($sync_quotations_with_inventories == 'true')
							{
								if ($product['type'] == '1')
								{
									$inventoryToDiscount = $this->model->getInventoryToDiscount($product['id_product'], $branchOffice);
									$fourthPermitted = !empty($inventoryToDiscount) ? true : false;
								}
								else
								{
									$inventoryToDiscount = '';
									$fourthPermitted = true;
								}
							}
							else
								$fourthPermitted = true;

							if ($fourthPermitted == true)
							{
								if ($sync_quotations_with_inventories == 'true')
								{
									if ($product['type'] == '1')
									{
										$existIntoInventory = $this->model->checkExistIntoInventory($quantity, $product['id_product'], $inventoryToDiscount);
										$fifthPermitted = ($existIntoInventory['status'] == true) ? true : false;
									}
									else
										$fifthPermitted = true;
								}
								else
									$fifthPermitted = true;

								if ($fifthPermitted == true)
								{
									$object = [
										'id_product' => $product['id_product'],
										'name' => $product['name'],
										'folio' => $product['folio'],
										'price' => $product['price'],
										'discount' => $product['discount'],
										'coin' => $product['coin'],
										'type' => $product['type'],
										'components' => $components
									];

									$inventory = ($sync_quotations_with_inventories == 'true') ? $inventoryToDiscount : '';
									$flag = 'product';
									$sixthPermitted = true;
								}
								else
								{
									if ($existIntoInventory['errors']['errorNotExistIntoInventory'] == true)
										array_push($errors, ['folio', 'Este producto no existe en el inventario asignado']);

									if ($existIntoInventory['errors']['errorExceed'] == true)
										array_push($errors, ['folio', 'La cantidad excede a la cantidad existente en el inventario asignado']);

									if ($existIntoInventory['errors']['errorInventoryDeactivate'] == true)
										array_push($errors, ['folio', 'El inventario asignado esta desactivado']);

									echo json_encode([
										'status' => 'error',
										'labels' => $errors
									]);
								}
							}
							else
							{
								array_push($errors, ['folio', 'Producto no disponible']);

								echo json_encode([
									'status' => 'error',
									'labels' => $errors
								]);
							}
						}
						else
						{
							array_push($errors, ['folio', 'Producto no disponible']);

							echo json_encode([
								'status' => 'error',
								'labels' => $errors
							]);
						}
					}
					else if (!empty($service) AND empty($product))
					{
						$components = [];

						if ($sync_quotations_with_inventories == 'true')
						{
							if (isset($service['components']) AND !empty($service['components']))
							{
								$components = json_decode($service['components'], true);

								foreach ($components as $component)
								{
									$inventoryToDiscount = $this->model->getInventoryToDiscount($component['product'], $branchOffice);

									if (!empty($inventoryToDiscount))
									{
										$existIntoInventory = $this->model->checkExistIntoInventory($component['quantity'], $component['product'], $inventoryToDiscount);

										if ($existIntoInventory['status'] == true)
										{
											$index = array_search($component, $components);
											unset($components[$index]);
											array_push($components,  ['quantity' => $component['quantity'], 'product' => $component['product'], 'inventory' => $inventoryToDiscount]);
										}
										else
											$thirdPermitted = false;
									}
									else
										$secondPermitted = false;
								}

								if ($secondPermitted == true AND $thirdPermitted == true)
									$firstPermitted = true;
							}
							else
								$firstPermitted = true;
						}
						else
							$firstPermitted = true;

						if ($firstPermitted = true)
						{
							$object = [
								'id_service' => $service['id_service'],
								'name' => $service['name'],
								'folio' => $service['folio'],
								'price' => $service['price'],
								'discount' => $service['discount'],
								'coin' => $service['coin'],
								'components' => $components
							];

							$inventory = '';
							$flag = 'service';
							$sixthPermitted = true;
						}
						else
						{
							array_push($errors, ['folio', 'Productos compuestos no disponibles']);

							echo json_encode([
								'status' => 'error',
								'labels' => $errors
							]);
						}
					}
					else if (!empty($product) AND !empty($service))
					{
						array_push($errors, ['folio', 'Folio repetido. Porvafor pongase en contacto con soporte técnico del sistema.']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
					}
					else if (empty($product) AND empty($service))
					{
						array_push($errors, ['folio', 'No se encontro ningun producto o servicio para vender con este folio']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
					}

					if ($sixthPermitted == true)
					{
						$data = [
							'quantity' => $quantity,
							'object' => $object,
							'totals' => [],
							'inventory' => $inventory,
							'branchOffice' => $branchOffice,
							'flag' => $flag
						];

						if ($flag == 'product')
							$price = json_decode($object['price'], true)['public_price'];
						else if ($flag == 'service')
							$price = $object['price'];

						$total = $price * $quantity;

						if (!empty($object['discount']))
						{
							$discount = json_decode($object['discount'], true);
							$discountQuantity = $discount['quantity'];
							$discountType = $discount['type'];

							if ($discountType == '1')
							{
								$percentage = (($discountQuantity / 100) * $total);
								$total = $total - $percentage;
								$discount = $discountQuantity . ' %';
							}
							else if ($discountType == '2')
							{
								if ($object['coin'] == '1')
									$coin = 'MXN';
								else if ($object['coin'] == '2')
									$coin = 'USD';

								if ($quantity > 1)
									$total = $total - ($discountQuantity * $quantity);
								else
									$total = $total - ($discountQuantity * 1);

								$discount = '$ ' . $discountQuantity . ' ' . $coin;
							}
						}
						else
						{
							$discount = '';
							$discountQuantity = 0;
							$discountType = '';
						}

						$data['totals'] = [
							'price' => $price,
							'discount' => $discount,
							'discountQuantity' => $discountQuantity,
							'discountType' => $discountType,
							'total' => $total
						];

						echo json_encode([
							'status' => 'success',
							'additionalDiscount' => $additionalDiscount,
							'data' => $data
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

	/* Aplicar descuento adicional
	--------------------------------------------------------------------------- */
	public function applyAdditionalDiscount()
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
        {
            if (Format::existAjaxRequest() == true)
            {
				$additionalDiscountQuantity	= (isset($_POST['additionalDiscountQuantity']) AND !empty($_POST['additionalDiscountQuantity'])) ? $_POST['additionalDiscountQuantity'] : null;
				$additionalDiscountType		= (isset($_POST['additionalDiscountType']) AND !empty($_POST['additionalDiscountType'])) ? $_POST['additionalDiscountType'] : null;
				$data						= (isset($_POST['data']) AND !empty($_POST['data'])) ? json_decode($_POST['data'], true) : null;

				if (isset($additionalDiscountQuantity) AND isset($additionalDiscountType))
				{
					if ($data['flag'] == 'product')
						$price = json_decode($data['object']['price'], true)['public_price'];
					else if ($data['flag'] == 'service')
						$price = $data['object']['price'];

					$total = $price * $data['quantity'];

					if ($additionalDiscountType == '1')
					{
						$percentage = (($additionalDiscountQuantity / 100) * $total);
						$total = $total - $percentage;
						$discount = $additionalDiscountQuantity . ' %';
					}
					else if ($additionalDiscountType == '2')
					{
						if ($data['object']['coin'] == '1')
							$coin = 'MXN';
						else if ($data['object']['coin'] == '2')
							$coin = 'USD';

						if ($data['quantity'] > 1)
							$total = $total - ($additionalDiscountQuantity * $data['quantity']);
						else
							$total = $total - ($additionalDiscountQuantity * 1);

						$discount = '$ ' . $additionalDiscountQuantity . ' ' . $coin;
					}

					$data['totals'] = [
						'price' => $price,
						'discount' => $discount,
						'discountQuantity' => $additionalDiscountQuantity,
						'discountType' => $additionalDiscountType,
						'total' => $total
					];

					echo json_encode([
						'status' => 'success',
						'data' => $data
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'success',
						'data' => $data
					]);
				}
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Visualizar una venta
	--------------------------------------------------------------------------- */
	public function view($id)
	{
		if (Session::getValue('level') == 7 OR Session::getValue('level') >= 9)
        {
			$sale = $this->model->getSaleById($id);

			$settings = $this->model->getAllSettings();
			$settings_sales = json_decode($settings['sales'], true);

			$items = json_decode($sale['items'], true);
			$client = $this->model->getClientById($sale['id_client']);
			$user = $this->model->getUserById($sale['id_user']);
			$branchOffice = $this->model->getBranchOfficeById($sale['id_branch_office']);
			$totals = json_decode($sale['totals'], true);

            if (Format::existAjaxRequest() == true)
            {
				$message =
				'<div style="width:800px; padding:20px; box-sizing: border-box; background-color:#f2f2f2;">
					<div style="width:100%; padding:20px; box-sizing: border-box; background-color:#fff;">
						<figure style="width:100%; height:100px; margin-bottom:40px;"><img style="width:auto; height:100%;" src="https://sofierp.com/images/logotypes/' . json_decode($settings['business'], true)['logotype'] . '" /></figure>
						<h2 style="padding:0px; margin:50px 0px; font-family:century gothic; font-weight:200; text-align:center;">COTIZACIÓN</h2>
						<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">' . json_decode($settings['business'], true)['name'] . '</h4>
						<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">Folio: ' . $sale['folio'] . '</h4>
						<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">Fecha: ' . $sale['date_time'] . '</h4>
						<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">Fecha de vencimiento: ' . $sale['date_expiration'] . '</h4>
						<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">Cliente: ' . $client['name'] . '</h4>
						<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">Sucursal: ' . $branchOffice['name'] . '</h4>
						<h4 style="padding:0px; margin:0px; margin-bottom:5px; font-family:century gothic; font-weight:200;">Vendedor: ' . $user['name'] . '</h4>
						<table style="width: 100%; padding:0px; margin:0px; font-family:century gothic; font-weight:200; text-align:center;">
							<thead>
								<tr>
									<th style="padding:20px 0px; margin:0px; text-transform:uppercase; border-bottom: 1px solid #2196F3;">Cant.</th>
									<th style="padding:20px 0px; margin:0px; text-transform:uppercase; border-bottom: 1px solid #2196F3;">Folio</th>
									<th style="padding:20px 0px; margin:0px; text-transform:uppercase; border-bottom: 1px solid #2196F3;">Descripción</th>
									<th style="padding:20px 0px; margin:0px; text-transform:uppercase; border-bottom: 1px solid #2196F3;">Precio</th>
									<th style="padding:20px 0px; margin:0px; text-transform:uppercase; border-bottom: 1px solid #2196F3;">Descuento</th>
									<th style="padding:20px 0px; margin:0px; text-transform:uppercase; border-bottom: 1px solid #2196F3;">Total</th>
								</tr>
							</thead>
							<tbody style="">';

				foreach ($items as $item)
				{
					if ($item['object']['coin'] == '1')
						$coin = 'MXN';
					else if ($item['object']['coin'] == '2')
						$coin = 'USD';

					$message .=
					'<tr>
						<td style="padding:20px 0px; margin:0px;">' . $item['quantity'] . '</td>
						<td style="padding:20px 0px; margin:0px;">' . $item['object']['folio'] . '</td>
						<td style="padding:20px 0px; margin:0px;">' . $item['object']['name'] . '</td>
						<td style="padding:20px 0px; margin:0px;">$ ' . $item['totals']['price'] . ' ' . $coin . '</td>
						<td style="padding:20px 0px; margin:0px;">$ ' . $item['totals']['discount'] . ' ' . $coin . '</td>
						<td style="padding:20px 0px; margin:0px;">$ ' . $item['totals']['total'] . ' ' . $coin . '</td>
					</tr>';
				}

				$message .=
				'			</tbody>
						</table>
						<h4 style="padding:0px; margin:0px; margin-top:50px; font-family:century gothic; font-weight:900; text-transform:uppercase; text-align:right;">Totales</h4>
						<h4 style="padding:0px; margin:0px; font-family:century gothic; font-weight:200; text-align:right;">$ ' . number_format($totals['mxnTotal'], 2, '.', '') . ' MXN</h4>
						<h4 style="padding:0px; margin:0px; font-family:century gothic; font-weight:200; text-align:right;">$ ' . number_format($totals['usdTotal'], 2, '.', '') . ' USD</h4>
						<h4 style="padding:0px; margin:0px; margin-top:50px; font-family:century gothic; font-weight:900; text-transform:uppercase; text-align:right;">Gran Total</h4>
						<h4 style="padding:0px; margin:0px; margin-bottom:50px; font-family:century gothic; font-weight:200; text-align:right;">$ ' . number_format($totals['total'], 2, '.', '') . ' ' . json_decode($settings['sales'], true)['main_coin'] . '</h4>
						<h4 style="padding:0px; margin:0px; font-family:century gothic; font-weight:200; text-align:center;">' . json_decode($settings['business'], true)['website'] . '</h4>
					</div>
				</div>';

				$sendEmail = $this->model->sendEmail($client['email'], $client['name'], json_decode($settings['business'], true)['name'] . ' - Cotización', $message);

				echo json_encode([
					'status' => 'success'
				]);
			}
            else
            {
				define('_title', '{$lang.title} | Dashboard');

                $template = $this->view->render($this, 'view');
                $template = $this->format->replaceFile($template, 'header');

				$lstItemsSale = '';

				foreach ($items as $item)
				{
					if ($item['object']['coin'] == 1)
						$coin = 'MXN';
					else if ($item['object']['coin'] == 2)
						$coin = 'USD';

					$lstItemsSale .=
					'<tr>
						<td>' . $item['quantity'] . '</td>
						<td>' . $item['object']['folio'] . '</td>
						<td>' . $item['object']['name'] . '</td>
						<td>$ ' . $item['totals']['price'] . ' ' . $coin . '</td>
						<td>$ ' . $item['totals']['discount'] . ' ' . $coin . '</td>
						<td>$ ' . $item['totals']['total'] . ' ' . $coin . '</td>
					</tr>';
				}

				if (Session::getValue('level') == 10)
				{
					$branchOffice =
					'<fieldset class="input-group">
		                <label data-important>
		                    <span>Sucursal</span>
		                    <input type="text" value="' . $branchOffice['name'] . '" disabled>
		                </label>
		            </fieldset>';
				}
				else
					$branchOffice = '';

				if ($settings_sales['deferred_payments'] == true)
				{
					$deferred_payments =
					'<fieldset class="input-group">
		                <label data-important>
		                    <span>Pagos diferidos</span>
		                    <input type="text" value="' . ((isset($sale['deferred_payments']) AND !empty($sale['deferred_payments'])) ? 'Si' : 'No') . '" disabled>';

					if (isset($sale['deferred_payments']) AND !empty($sale['deferred_payments']))
					{
						$deferred_payments_array = json_decode($sale['deferred_payments'], true);
						$deferred_cicle = 1;

						foreach ($deferred_payments_array['deferred_payments'] as $deferred_payment_array)
						{
							$deferred_payments .=
							'<span>Pago ' . $deferred_cicle . ': $ ' . $deferred_payment_array['pay'] . ' ' . $totals['mainCoin'] . '</span>';

							$deferred_cicle = $deferred_cicle + 1;
						}
					}

					$deferred_payments .=
					'    </label>
		            </fieldset>';
				}
				else
					$deferred_payments = '';

				if ($sale['date_expiration'] >= date('Y-m-d', time()))
					$status = 'Activa';
				else
					$status = 'Expirada';

                $replace = [
					'{$lstItemsSale}' => $lstItemsSale,
					'{$folio}' => $sale['folio'],
					'{$date_time}' => $sale['date_time'],
					'{$expiration}'=> $sale['date_expiration'],
					'{$client}' => $client['name'],
					'{$seller}' => $user['name'],
					'{$branchOffice}' => $branchOffice,
					'{$deferred_payments}' => $deferred_payments,
					'{$subtotal}' => $totals['subtotal'],
					'{$iva}' => $totals['iva'],
					'{$total}' => $totals['total'],
					'{$mxnTotal}' => $totals['mxnTotal'],
					'{$usdTotal}' => $totals['usdTotal'],
					'{$coin}' => $totals['mainCoin'],
					'{$status}' => $status
                ];

                $template = $this->format->replace($replace, $template);

                echo $template;
            }
        }
        else
            header('Location: /dashboard');
	}
}
