<?php

defined('_EXEC') or die;

class Pointsale_controller extends Controller
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
						<th>Id</th>
						<th>Folio</th>
						<th>Total</th>
						<th>Pago</th>
						<th>Fecha</th>
						<th>Vendedor</th>
						<th>Cliente</th>
						' . ((Session::getValue('level') == 10) ? '<th>Sucursal</th>' : '') . '
						<th width="100px">Estado</th>
						<th width="35px"></th>
					</tr>
				</thead>
				<tbody>';

			foreach ($sales as $sale)
			{
				$totals = json_decode($sale['totals'], true);

				if ($sale['payment'] == 'cash')
					$payment = 'Efectivo';
				else if ($sale['payment'] == 'card')
					$payment = 'Tarjeta';
				else if ($sale['payment'] == 'deferred')
					$payment = 'Diferidos';

				$user = $this->model->getUserById($sale['id_user']);

				if (!empty($sale['id_client']))
					$client = $this->model->getClientById($sale['id_client'])['name'];
				else
					$client = '-';

				$branchOffice = $this->model->getBranchOfficeById($sale['id_branch_office']);

				$lstSales .=
				'<tr>
					<td>' . $sale['id_sale'] . '</td>
					<td>' . $sale['folio'] . '</td>
					<td>$ ' . $totals['total'] . ' ' . $totals['mainCoin'] . '</td>
					<td>' . $payment . '</td>
					<td>' . date('Y-m-d h:i:s', strtotime($sale['date_time'])) . '</td>
					<td>' . $user['name'] . '</td>
					<td>' . $client . '</td>
					' . ((Session::getValue('level') == 10) ? '<td>' . $branchOffice['name'] . '</td>' : '') . '
					<td>' . (($sale['status'] == true) ? '<span class="active">Activa</span>' : '<span class="cancel">Cancelada</span>') . '</td>
					<td><a href="/pointsale/view/' . $sale['id_sale'] . '"><i class="material-icons">more_horiz</i><span>' . (($sale['payment'] == 'deferred') ? 'Detalles / Abonar pago' : 'Detalles') . '</span></a></td>
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
			$ticketPrint = json_decode($settings['sales'], true)['sale_ticket_print'];
			$ticketTotalsBreakdown = json_decode($settings['sales'], true)['sale_ticket_totals_breakdown'];

			$mainCoin = json_decode($settings['sales'], true)['main_coin'];
			$ivaRate = json_decode($settings['sales'], true)['iva_rate'];
			$usdRate = json_decode($settings['sales'], true)['usd_rate'];

            if (Format::existAjaxRequest() == true)
            {
				$total 				= (isset($_POST['total']) AND !empty($_POST['total'])) ? $_POST['total'] : 0;
				$mxnTotal 			= (isset($_POST['mxnTotal']) AND !empty($_POST['mxnTotal'])) ? $_POST['mxnTotal'] : 0;
				$usdTotal 			= (isset($_POST['usdTotal']) AND !empty($_POST['usdTotal'])) ? $_POST['usdTotal'] : 0;
				$mxnTotalReceived 	= (isset($_POST['mxnTotalReceived']) AND !empty($_POST['mxnTotalReceived'])) ? $_POST['mxnTotalReceived'] : 0;
				$usdTotalReceived	= (isset($_POST['usdTotalReceived']) AND !empty($_POST['usdTotalReceived'])) ? $_POST['usdTotalReceived'] : 0;
				$payment 			= (isset($_POST['payment']) AND !empty($_POST['payment'])) ? $_POST['payment'] : null;
				$sales				= (isset($_POST['sales']) AND !empty($_POST['sales'])) ? json_decode($_POST['sales'], true) : null;

				if (Session::getValue('level') >= 9)
					$user = (isset($_POST['user']) AND !empty($_POST['user'])) ? $_POST['user'] : null;
				else if (Session::getValue('level') == 7)
					$user = Session::getValue('id_user');

				$client = (isset($_POST['client']) AND !empty($_POST['client'])) ? $_POST['client'] : null;

				$errors = [];

				if ($total < 0)
					array_push($errors, ['total', 'No ingrese números negativos']);

				if ($mxnTotal < 0)
					array_push($errors, ['mxnTotal', 'No ingrese números negativos']);

				if ($usdTotal < 0)
					array_push($errors, ['usdTotal', 'No ingrese números negativos']);

				if (!empty($sales) AND $payment == 'cash' AND $mxnTotalReceived == 0 AND $usdTotalReceived == 0 AND $mainCoin == 'MXN')
					array_push($errors, ['mxnTotalReceived', 'Faltan $ ' . $total . ' MXN']);
				else if ($mxnTotalReceived < 0)
					array_push($errors, ['mxnTotalReceived', 'No ingrese números negativos']);

				if (!empty($sales) AND $payment == 'cash' AND $mxnTotalReceived == 0 AND $usdTotalReceived == 0 AND $mainCoin == 'USD')
					array_push($errors, ['usdTotalReceived', 'Faltan $ ' . ceil($total) . ' USD']);
				else if ($usdTotalReceived < 0)
					array_push($errors, ['usdTotalReceived', 'No ingrese números negativos']);

				if (!isset($payment))
					array_push($errors, ['payment', 'Seleccione una opción']);
				else if ($payment != 'cash' AND $payment != 'card' AND $payment != 'deferred')
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

				if (empty($errors))
				{
					$totalReceived = 0;
					$change = 0;

					$permitted = false;

					if ($payment == 'cash')
					{
						if ($mainCoin == 'MXN')
						{
							$totalReceived = ($usdTotalReceived * $usdRate) + $mxnTotalReceived;
							$missing = $total - $totalReceived;
							$change = $totalReceived - $total;
						}
						else if ($mainCoin == 'USD')
						{
							$totalReceived = ($mxnTotalReceived / $usdRate) + $usdTotalReceived;
							$missing = ceil($total - $totalReceived);
							$change = floor($totalReceived - $total);
						}

						if ($totalReceived < $total)
						{
							if (isset($mxnTotalReceived) AND !isset($usdTotalReceived))
								array_push($errors, ['mxnTotalReceived', 'Faltan $ ' . $missing . ' ' . $mainCoin]);
							else if (isset($usdTotalReceived) AND !isset($mxnTotalReceived))
								array_push($errors, ['usdTotalReceived', 'Faltan $ ' . $missing . ' ' . $mainCoin]);
							else if (isset($mxnTotalReceived) AND isset($usdTotalReceived) AND $mainCoin == 'MXN')
								array_push($errors, ['mxnTotalReceived', 'Faltan $ ' . $missing . ' MXN']);
							else if (isset($mxnTotalReceived) AND isset($usdTotalReceived) AND $mainCoin == 'USD')
								array_push($errors, ['usdTotalReceived', 'Faltan $ ' . $missing . ' USD']);

							echo json_encode([
								'status' => 'error',
								'labels' => $errors
							]);
						}
						else
							$permitted = true;
					}
					else if ($payment == 'card')
					{
						$totalReceived = $total;
						$permitted = true;
					}
					else if ($payment == 'deferred')
					{
						if ($mainCoin == 'MXN')
							$totalReceived = ($usdTotalReceived * $usdRate) + $mxnTotalReceived;
						else if ($mainCoin == 'USD')
							$totalReceived = ($mxnTotalReceived / $usdRate) + $usdTotalReceived;

						if ($totalReceived >= $total)
						{
							array_push($errors, ['mxnTotalReceived', 'No aplica pagos diferidos']);
							array_push($errors, ['usdTotalReceived', 'No aplica pagos diferidos']);

							echo json_encode([
								'status' => 'error',
								'labels' => $errors
							]);
						}
						else
							$permitted = true;
					}

					if ($permitted == true)
					{
						foreach ($sales as $sale)
						{
							if (!empty($sale['object']['components']))
							{
								foreach ($sale['object']['components'] as $component)
									$output = $this->model->newOutput($component['quantity'], $component['product'], $component['inventory']);
							}

							if ($sale['flag'] == 'product')
							{
								if ($sale['object']['type'] == '1')
									$output = $this->model->newOutput($sale['quantity'], $sale['object']['id_product'], $sale['inventory']);
							}

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
							'totalReceived' => $totalReceived,
							'mxnTotalReceived' => $mxnTotalReceived,
							'usdTotalReceived' => $usdTotalReceived,
							'change' => $change,
							'ivaRate' => $ivaRate,
							'usdRate' => $usdRate,
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

						$query = $this->model->newSale($ticketFolio, $totals, $payment, $deferred_payments_array, $sales, $ticketDate, $user, $client, $branchOffice);

						if (!empty($query))
						{
							$user = $this->model->getUserById($user);

							if (isset($client))
								$client = $this->model->getClientById($client)['name'];
							else
								$client = 'No identificado';

							$branchOffice = $this->model->getBranchOfficeById($branchOffice);
							$phoneNumber = json_decode($branchOffice['phone_number'], true);
							$phoneNumber = $phoneNumber['type'] . '. (+' . $phoneNumber['country_code'] . ') ' . $phoneNumber['number'];

							$deferred_payments_array = json_decode($deferred_payments_array, true);

							$data = [
								'folio' => $ticketFolio,
								'date' => $ticketDate,
								'subtotal' => $subtotal,
								'iva' => $iva,
								'totalReceived' => $totalReceived,
								'mxnTotalReceived' => $mxnTotalReceived,
								'usdTotalReceived' => $usdTotalReceived,
								'payment' => $payment,
								'change' => $change,
								'user' => $user['name'],
								'client' => $client,
								'deferred_payments_array' => $deferred_payments_array,
								'branchOffice' => [
									'name' => $branchOffice['name'],
									'phoneNumber' => $phoneNumber,
									'address' => $branchOffice['address'],
									'fiscalName' => $branchOffice['fiscal_name'],
									'fiscalCode' => $branchOffice['fiscal_code'],
									'fiscalRegime' => $branchOffice['fiscal_regime'],
									'fiscalAddress' => $branchOffice['fiscal_address']
								]
							];

							echo json_encode([
								'status' => 'success',
								'data' => $data
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

                $template = $this->view->render($this, 'add');
                $template = $this->format->replaceFile($template, 'header');

				$products = $this->model->getAllProducts();
				$services = $this->model->getAllServices();
				$clients = $this->model->getAllClients();

				$html = '';
				$lstProductsAndServices = '';
				$productsAndServices = [];

				if ($ticketPrint == 'true')
				{
					$html .=
					'<div class="span8 pr">';
				}

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

				$sappl = (Session::existsVar('sappl') == true) ? Session::getValue('sappl') : 'false';
				$sfoli = (Session::existsVar('sfoli') == true) ? Session::getValue('sfoli') : '';
				$squan = (Session::existsVar('squan') == true) ? Session::getValue('squan') : '';
				$sbran = (Session::existsVar('sbran') == true) ? Session::getValue('sbran') : '';
				$sdisc = (Session::existsVar('sdisc') == true) ? Session::getValue('sdisc') : '';

				$html .=
				'   <fieldset class="input-group span2 pr">
                        <label data-important>
                            <span>Cantidad</span>
                            <input type="number" name="quantity" value="1" min="1">
                        </label>
                    </fieldset>
                    <fieldset class="input-group span10">
                        <label data-important>
                            <span>Folio</span>
                            <input type="text" name="folio" class="uppercase" autofocus data-sappl="' . $sappl . '" data-sfoli="' . $sfoli . '" data-squan="' . $squan . '" data-sbran="' . $sbran . '" data-sdisc="' . $sdisc . '">
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
                        <a data-action="searchToSell">Aceptar</a>
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
                <fieldset class="input-group">
                    <label data-important>
                        <span>Total MXN</span>
                        <input type="text" name="mxnTotal" value="$ 0.00 MXN" disabled>
                    </label>
                </fieldset>
                <fieldset class="input-group">
                    <label data-important>
                        <span>Total USD</span>
                        <input type="text" name="usdTotal" value="$ 0.00 USD" disabled>
                    </label>
                </fieldset>
                <form name="makePayment">
					<div class="span4 pr">
					<fieldset class="input-group">
						<label data-important>
							<span>Cliente (Opcional)</span>
							<select name="client" class="chosen-select">
								<option value="">Sin cliente</option>';

				foreach ($clients as $client)
					$html .= '<option value="' . $client['id_client'] . '">' . $client['name'] . '</option>';

				$html .=
				'			</select>
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
				'	</div>
                    <div class="span4">
                        <fieldset class="input-group">
                            <label data-important>
                                <span>Tipo de pago</span>
                                <select name="payment">
                                    <option value="cash">Efectivo</option>
                                    <option value="card">Tarjeta de crédito</option>
                                    <option value="card">Tarjeta de débito</option>';

									if ($deferred_payments == 'true')
									{
										$html .=
										'<option value="deferred">Plan de pagos diferidos</option>';
									}
				$html .=
				'               </select>
                            </label>
                        </fieldset>';

				if ($deferred_payments == 'true')
				{
					$html .=
					'<fieldset class="input-group hidden">
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
                 <div class="span4 pl">
                    <fieldset class="input-group">
                        <label data-important>
                            <span>Total a pagar</span>
                            <input type="text" name="total" class="uppercase" value="$ 0.00 ' . $mainCoin . '" disabled>
                        </label>
                    </fieldset>
                    <fieldset class="input-group">
                        <label data-important>
                            <span>Total recibído MXN</span>
                            <input type="number" name="mxnTotalReceived">
                        </label>
                    </fieldset>
                    <fieldset class="input-group">
                        <label data-important>
                            <span>Total recibído USD</span>
                            <input type="number" name="usdTotalReceived">
                        </label>
                        <a data-action="makePayment">Pagar</a>
                    </fieldset>
                    <fieldset class="input-group">
                        <label data-important>
                            <span>Total recibído</span>
                            <input type="text" name="totalReceived" class="uppercase" value="$ 0.00 ' . $mainCoin . '" disabled>
                        </label>
                    </fieldset>
                    <fieldset class="input-group">
                        <label data-important>
                            <span>Cambio</span>
                            <input type="text" name="change" class="uppercase" value="$ 0.00 ' . $mainCoin . '" disabled>
                        </label>
                        <a href="/pointsale/add" class="hidden" data-action="newSale">Nueva venta</a>';

				if ($ticketPrint == 'true')
				{
					$html .=
					'<a class="hidden" data-action="printTicket">Imprimir ticket</a>';
				}

				$html .=
            	'        </fieldset>
                    </div>
                    <div class="clear"></div>
                </form>';

				if ($ticketPrint == 'true')
				{
					$businessSettings = json_decode($settings['business'], true);
					$salesSettings = json_decode($settings['sales'], true);

					$html .=
	            	'</div>
		             <div class="span4 pl">
		                <div id="ticket" class="ticket">
		                    <h2 style="font-family:arial;font-size:16px;text-transform:uppercase;text-align:center;padding:0px;margin:0px;margin-bottom:20px;color:#000;line-height:25px;">' . $businessSettings['name'] . '</h2>';

					if (!empty($businessSettings['logotype']))
					{
						$html .=
						'<figure style="width:100%;height:80px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
							<img style="width:auto;height:100%;" src="/images/logotypes/' . $businessSettings['logotype'] . '" alt="" />
						</figure>';
					}

					$html .=
					'<h4 id="folio" style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:center;padding:0px;margin:0px;color:#000;line-height:18px;">Folio: 000000</h4>
                    <h4 id="date" style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:center;padding:0px;margin:0px;color:#000;line-height:18px;">AAAA-MM-DD 00:00:00</h4>
                    <div style="margin-bottom:20px;"></div>

                    <div class="table-responsive-vertical">
                        <table id="salesTicketTable" class="display" data-page-length="100" style="width:100px;font-size:10px;"></table>
                    </div>
                    <div style="margin-bottom:20px;"></div>';

					if ($ticketTotalsBreakdown == 'true')
					{
						$html .=
						'<h4 id="subtotal" style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:right;padding:0px;margin:0px;color:#000;line-height:18px;">Subtotal: $ 0.00 ' . $mainCoin . '</h4>
		                 <h4 id="iva" style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:right;padding:0px;margin:0px;color:#000;line-height:18px;">IVA: (' . $ivaRate . '%) $ 0.00 ' . $mainCoin . '</h4>';
					}

					$html .=
	        		'	<h4 id="total" style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:right;padding:0px;margin:0px;color:#000;line-height:18px;">Total: $ 0.00 ' . $mainCoin . '</h4>
		                <div style="margin-bottom:20px;"></div>

		                <h4 id="totalReceived" style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:right;padding:0px;margin:0px;color:#000;line-height:18px;">Recibído: $ 0.00 ' . $mainCoin . '</h4>
		                <h4 id="mxnTotalReceived" style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:right;padding:0px;margin:0px;color:#000;line-height:18px;">($ 0.00 MXN)</h4>
		                <h4 id="usdTotalReceived" style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:right;padding:0px;margin:0px;color:#000;line-height:18px;">($ 0.00 USD)</h4>
		                <div style="margin-bottom:20px;"></div>

		                <h4 id="change" style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:right;padding:0px;margin:0px;color:#000;line-height:18px;">Cambio: $ 0.00 ' . $mainCoin . '</h4>
		                <div style="margin-bottom:20px;"></div>

		                <h4 id="payment" style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:right;padding:0px;margin:0px;color:#000;line-height:18px;">Tipo de pago: No identificado</h4>
		                <h4 style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:right;padding:0px;margin:0px;color:#000;line-height:18px;">Cambio de dollar: $ ' . $usdRate . ' MXN</h4>
		                <h4 style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:right;padding:0px;margin:0px;color:#000;line-height:18px;">Moneda de transacción: ' . $mainCoin . '</h4>
		                <h4 id="user" style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:right;padding:0px;margin:0px;color:#000;line-height:18px;">Vendedor: No identificado</h4>
		                <h4 id="client" style="font-family:arial;font-size:12px;text-transform:uppercase;text-align:right;padding:0px;margin:0px;color:#000;line-height:18px;">Cliente: No identificado</h4>
		                <div style="margin-bottom:20px;"></div>';

					if ($deferred_payments == true)
					{
						$html .=
						'<div id="deferred"></div>
						<div id="deferred_pays"></div>';
					}

					$html .=
					'	<p style="font-family:arial;font-size:10px;text-align:center;margin:0px;color:#757575;" disabled>' . $salesSettings['sale_ticket_legend'] . '</p>
						<div style="margin-bottom:20px;"></div>

						<p id="branchOfficeName" style="font-family:arial;font-size:8px;text-transform:uppercase;text-align:center;padding:0px;margin:0px;color:#757575;line-height:18px;"></p>
						<p id="branchOfficePhoneNumber" style="font-family:arial;font-size:8px;text-transform:uppercase;text-align:center;padding:0px;margin:0px;color:#757575;line-height:18px;"></p>
						<p id="branchOfficeAddress" style="font-family:arial;font-size:8px;text-transform:uppercase;text-align:center;padding:0px;margin:0px;color:#757575;line-height:18px;"></p>
						<div style="margin-bottom:20px;"></div>

						<p id="branchOfficeFiscalName" style="font-family:arial;font-size:8px;text-transform:uppercase;text-align:center;padding:0px;margin:0px;color:#757575;line-height:18px;"></p>
						<p id="branchOfficeFiscalCode" style="font-family:arial;font-size:8px;text-transform:uppercase;text-align:center;padding:0px;margin:0px;color:#757575;line-height:18px;"></p>
						<p id="branchOfficeFiscalRegime" style="font-family:arial;font-size:8px;text-transform:uppercase;text-align:center;padding:0px;margin:0px;color:#757575;line-height:18px;"></p>
						<p id="branchOfficeFiscalAddress" style="font-family:arial;font-size:8px;text-transform:uppercase;text-align:center;padding:0px;margin:0px;color:#757575;line-height:18px;"></p>
						<div style="margin-bottom:20px;"></div>

						<p style="font-family:arial;font-size:8px;text-transform:uppercase;text-align:center;padding:0px;margin:0px;color:#757575;line-height:18px;">¡Gracias por su preferencia!</p>
                    	<div style="margin-bottom:20px;"></div>

                    	<h6 style="font-family:arial;font-size:10px;text-transform:lowercase;text-align:center;padding:0px;margin:0px;color:#757575;line-height:18px;">' . $businessSettings['website'] . '</h6>
						</div>
		            </div>
		            <div class="clear"></div>';
				}

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
				$sync_point_sale_with_inventories = json_decode($settings['sales'], true)['sync_point_sale_with_inventories'];

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

						if ($sync_point_sale_with_inventories == 'true')
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
							if ($sync_point_sale_with_inventories == 'true')
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
								if ($sync_point_sale_with_inventories == 'true')
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
										'name' => $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'],
										'folio' => $product['folio'],
										'price' => $product['price'],
										'discount' => $product['discount'],
										'coin' => $product['coin'],
										'type' => $product['type'],
										'components' => $components
									];

									$inventory = ($sync_point_sale_with_inventories == 'true') ? $inventoryToDiscount : '';
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

						if ($sync_point_sale_with_inventories == 'true')
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
								'folio' => $service['folio'] . ' ' . $service['category'],
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

						Session::setValue('sappl', 'false');
						Session::setValue('sfoli', '');
						Session::setValue('squan', '');
						Session::setValue('sbran', '');
						Session::setValue('sdisc', '');

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

            if (Format::existAjaxRequest() == true AND Session::getValue('level') >= 9)
            {
				if ($_POST['action'] == 'abone')
				{
					$totals = json_decode($sale['totals'], true);
					$total = $totals['total'];
					$recibed = $totals['totalReceived'];

					$abone = (isset($_POST['abone']) AND !empty($_POST['abone'])) ? json_decode($_POST['abone'], true) : null;

					$errors = [];

					if (!isset($abone))
						array_push($errors, ['abone', 'Ingrese el monto a abonar']);
					else if (!is_numeric($abone))
						array_push($errors, ['abone', 'Ingrese únicamente números']);
					else if ($abone == 0)
						array_push($errors, ['abone', 'Ingrese el monto a abonar']);
					else if ($abone < 0)
						array_push($errors, ['abone', 'No ingrese números negativos']);
					else if (($abone + $recibed) > $total)
						array_push($errors, ['abone', 'El abono sobre pasa la cantidad a liquidar (' . ($total - $recibed) . ')']);

					if (empty($errors))
					{
						$totals['totalReceived'] = $recibed + $abone;
						$totals = json_encode($totals);

						$query = $this->model->aboneSale($id, $totals);

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
							'success' => 'error',
							'labels' => $errors
						]);
					}
				}
				else if ($_POST['action'] == 'cancel')
				{
					$query = $this->model->cancelSale($id);

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
				define('_title', '{$lang.title} | Dashboard');

                $template = $this->view->render($this, 'view');
                $template = $this->format->replaceFile($template, 'header');

				$user = $this->model->getUserById($sale['id_user']);

				$deferred_payments_ipts = '';

				if (!empty($sale['id_client']))
					$client = $this->model->getClientById($sale['id_client'])['name'];
				else
					$client = 'No identificado';

				$branchOffice = $this->model->getBranchOfficeById($sale['id_branch_office']);

				$totals = json_decode($sale['totals'], true);
				$sales = json_decode($sale['sales'], true);

				$btnCancelSale = '';
				$lstItemsSale = '';
				$iptStatus = '';
				$mdlCancelSale = '';

				$returnToInventory = false;

				if ($sale['payment'] == 'cash')
					$payment = 'Efectivo';
				else if ($sale['payment'] == 'card')
					$payment = 'Tarjeta de crédito/débito';
				else if ($sale['payment'] == 'deferred')
				{
					$deferred_payments = json_decode($sale['deferred_payments'], true);
					$payment = 'Pagos diferidos (' . $deferred_payments['num_deferred_payments'] . ' pagos)';
					$deferred_payments_ipts .= '<br>';

					$deferred_cicle = 1;

					foreach ($deferred_payments['deferred_payments'] as $deferred_payment)
					{
						$deferred_payments_ipts .= '<span>Pago ' . $deferred_cicle . ': $ ' . $deferred_payment['pay'] . ' ' . $totals['mainCoin'] . '</span>';
						$deferred_cicle = $deferred_cicle + 1;
					}
				}

				if (Session::getValue('level') >= 9 AND $sale['status'] == true)
					$btnCancelSale .= '<a data-button-modal="cancelSale"><i class="material-icons">cancel</i><span>Cancelar</span></a>';

				foreach ($sales as $item)
				{
					if ($item['object']['coin'] == '1')
						$mainCoin = 'MXN';
					else if ($item['object']['coin'] == '2')
						$mainCoin = 'USD';

					$lstItemsSale .=
					'<tr>
						<td>' . $item['quantity'] . '</td>
						<td>[' . $item['object']['folio'] . '] ' . $item['object']['name'] . '</td>
						<td>$ ' . $item['totals']['price'] . ' ' . $mainCoin . '</td>
						<td>$ ' . $item['totals']['discount'] . ' ' . $mainCoin . '</td>
						<td>$ ' . $item['totals']['total'] . ' ' . $mainCoin . '</td>
					</tr>';
				}

				$iptStatus .= '<input type="text" value="' . (($sale['status'] == true) ? 'Activa' : 'Cancelada') . '" style="' . (($sale['status'] == false) ? 'background-color:#f44336 !important;border:1px solid #f44336;' : 'background-color:#4caf50 !important;border:1px solid #4caf50;') . 'color:#fff;" disabled>';

				if (Session::getValue('level') >= 9 AND $sale['status'] == true)
				{
					$mdlCancelSale .=
					'<section class="modal alert" data-modal="cancelSale">
					    <div class="content">
					        <header>
					            <h6>Alerta</h6>
					        </header>
					        <main>
					            <p>¿Está seguro de que desea cancelar está venta?</p>';

					if ($returnToInventory == true)
					{
						$mdlCancelSale .=
						'<fieldset class="input-group">
							<label class="checkbox" data-important>
								<input type="checkbox">
								<span>Devolver producto a inventario</span>
								<div class="clear"></div>
							</label>
						</fieldset>';
					}

					$mdlCancelSale .=
					'       </main>
					        <footer>
					            <a button-close>Cancelar</a>
					            <a data-action="cancelSale">Aceptar</a>
					        </footer>
					    </div>
					</section>';
				}

				$abone = '';

				if ($sale['payment'] == 'deferred' AND $totals['totalReceived'] < $totals['total'] AND $sale['status'] == true)
				{
					$abone .=
					'<form name="abone">
		                <fieldset class="input-group">
		                    <label data-important>
		                        <span>Monto a abonar</span>
		                        <input type="number" name="abone" min="1">
		                    </label>
		                    <a href="" data-action="abone">Abonar</a>
		                </fieldset>
		            </form>';
				}

                $replace = [
					'{$folio}' => $sale['folio'],
					'{$date}' => $sale['date_time'],
					'{$user}' => $user['name'],
					'{$client}' => $client,
					'{$branchOffice}' => $branchOffice['name'],
					'{$subtotal}' => $totals['subtotal'],
					'{$iva}' => $totals['iva'],
					'{$total}' => $totals['total'],
					'{$mxnTotal}' => $totals['mxnTotal'],
					'{$usdTotal}' => $totals['usdTotal'],
					'{$totalReceived}' => $totals['totalReceived'],
					'{$mxnTotalReceived}' => $totals['mxnTotalReceived'],
					'{$usdTotalReceived}' => $totals['usdTotalReceived'],
					'{$change}' => $totals['change'],
					'{$payment}' => $payment,
					'{$ivaRate}' => $totals['ivaRate'],
					'{$usdRate}' => $totals['usdRate'],
					'{$mainCoin}' => $totals['mainCoin'],
					'{$lstItemsSale}' => $lstItemsSale,
					'{$btnCancelSale}' => $btnCancelSale,
					'{$mdlCancelSale}' => $mdlCancelSale,
					'{$iptStatus}' => $iptStatus,
					'{$abone}' => $abone,
					'{$deferred_payments_ipts}' => $deferred_payments_ipts
                ];

                $template = $this->format->replace($replace, $template);

                echo $template;
            }
        }
        else
            header('Location: /dashboard');
	}
}
