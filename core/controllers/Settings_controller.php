<?php

defined('_EXEC') or die;

class Settings_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/*
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Session::getValue('level') == 10)
        {
			define('_title', '{$lang.title} | Dashboard');

            $template = $this->view->render($this, 'index');
            $template = $this->format->replaceFile($template, 'header');

            $replace = [

            ];

            $template = $this->format->replace($replace, $template);

            echo $template;
        }
        else
            header('Location: /dashboard');
	}

	/* Configuraciones generales
	--------------------------------------------------------------------------- */
	public function generals()
	{
		if (Session::getValue('level') == 10)
        {
			if (Format::existAjaxRequest() == true)
			{

			}
			else
			{
				define('_title', '{$lang.title} | Dashboard');

	            $template = $this->view->render($this, 'generals');
	            $template = $this->format->replaceFile($template, 'header');

	            $replace = [

	            ];

	            $template = $this->format->replace($replace, $template);

	            echo $template;
			}
        }
        else
            header('Location: /settings');
	}

	/* Configuraciones del negocio
	--------------------------------------------------------------------------- */
	public function business()
	{
		if (Session::getValue('level') == 10)
        {
			if (Format::existAjaxRequest() == true)
			{
				$name       = (isset($_POST['name']) AND !empty($_POST['name'])) ? $_POST['name'] : null;
				$website    = (isset($_POST['website']) AND !empty($_POST['website'])) ? $_POST['website'] : null;
				$logotype	= (isset($_FILES['logotype']['name']) AND !empty($_FILES['logotype']['name'])) ? $_FILES['logotype'] : null;

				$errors = [];

				if (!isset($name))
	                array_push($errors, ['name', 'No deje este campo vacío']);

				if (empty($errors))
				{
					$query = $this->model->editBusinessSettings($name, $website, $logotype);

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
				define('_title', '{$lang.title} | Dashboard');

	            $template = $this->view->render($this, 'business');
	            $template = $this->format->replaceFile($template, 'header');

				$settings = $this->model->getAllSettings();
				$settings = json_decode($settings['business'], true);

	            $replace = [
					'{$name}' => $settings['name'],
					'{$website}' => $settings['website'],
					'{$logotype}' => !empty($settings['logotype']) ? '{$path.images}logotypes/' . $settings['logotype'] : '{$path.images}empty.png',
	            ];

	            $template = $this->format->replace($replace, $template);

	            echo $template;
			}
        }
        else
            header('Location: /settings');
	}

	/* Obtener configuracion del negocio para editar
	--------------------------------------------------------------------------- */
	public function getBusinessSettingsToEdit()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$settings = $this->model->getAllSettings();
				$settings = json_decode($settings['business'], true);

	            if (!empty($settings))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $settings
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Configuraciones de venta
	--------------------------------------------------------------------------- */
	public function sales()
	{
		if (Session::getValue('level') == 10)
        {
			if (Format::existAjaxRequest() == true)
			{
				$mainCoin = (isset($_POST['mainCoin']) AND !empty($_POST['mainCoin'])) ? $_POST['mainCoin'] : null;
				$saleTicketPrint = (isset($_POST['saleTicketPrint']) AND !empty($_POST['saleTicketPrint'])) ? $_POST['saleTicketPrint'] : null;
				$saleTicketTotalsBreakdown = (isset($_POST['saleTicketTotalsBreakdown']) AND !empty($_POST['saleTicketTotalsBreakdown'])) ? $_POST['saleTicketTotalsBreakdown'] : null;
				$applyDiscounds = (isset($_POST['applyDiscounds']) AND !empty($_POST['applyDiscounds'])) ? $_POST['applyDiscounds'] : null;
				$deferred_payments = (isset($_POST['deferred_payments']) AND !empty($_POST['deferred_payments'])) ? $_POST['deferred_payments'] : null;
				$sync_point_sale_with_inventories = (isset($_POST['sync_point_sale_with_inventories']) AND !empty($_POST['sync_point_sale_with_inventories'])) ? $_POST['sync_point_sale_with_inventories'] : null;
				$sync_quotations_with_inventories = (isset($_POST['sync_quotations_with_inventories']) AND !empty($_POST['sync_quotations_with_inventories'])) ? $_POST['sync_quotations_with_inventories'] : null;
				$deferred_payments = (isset($_POST['deferred_payments']) AND !empty($_POST['deferred_payments'])) ? $_POST['deferred_payments'] : null;
				$ivaRate = (isset($_POST['ivaRate']) AND !empty($_POST['ivaRate'])) ? $_POST['ivaRate'] : null;
				$usdRate = (isset($_POST['usdRate']) AND !empty($_POST['usdRate'])) ? $_POST['usdRate'] : null;
				$saleTicketLegend = (isset($_POST['saleTicketLegend']) AND !empty($_POST['saleTicketLegend'])) ? $_POST['saleTicketLegend'] : '';

				$errors = [];

				if (!isset($mainCoin))
					array_push($errors, ['mainCoin', 'Seleccione una opción']);

				if (!isset($saleTicketPrint))
					array_push($errors, ['saleTicketPrint', 'Seleccione una opción']);

				if (!isset($saleTicketTotalsBreakdown))
					array_push($errors, ['saleTicketTotalsBreakdown', 'Seleccione una opción']);

				if (!isset($applyDiscounds))
					array_push($errors, ['applyDiscounds', 'Seleccione una opción']);

				if (!isset($deferred_payments))
					array_push($errors, ['deferred_payments', 'Seleccione una opción']);

				if (!isset($sync_point_sale_with_inventories))
					array_push($errors, ['sync_point_sale_with_inventories', 'Seleccione una opción']);

				if (!isset($sync_quotations_with_inventories))
					array_push($errors, ['sync_quotations_with_inventories', 'Seleccione una opción']);

				if (!isset($ivaRate))
					array_push($errors, ['ivaRate', 'No deje este campo vacío']);

				if (!isset($usdRate))
					array_push($errors, ['usdRate', 'No deje este campo vacío']);

				if (empty($errors))
				{
					$settings = $this->model->getAllSettings();
					$pdis = json_decode($settings['sales'], true)['pdis'];

					$settings = json_encode([
						'main_coin' => $mainCoin,
						'sale_ticket_print' => $saleTicketPrint,
						'sale_ticket_totals_breakdown' => $saleTicketTotalsBreakdown,
						'apply_discounds' => $applyDiscounds,
						'deferred_payments' => $deferred_payments,
						'sync_point_sale_with_inventories' => $sync_point_sale_with_inventories,
						'sync_quotations_with_inventories' => $sync_quotations_with_inventories,
						'iva_rate' => $ivaRate,
						'usd_rate' => $usdRate,
						'sale_ticket_legend' => $saleTicketLegend,
						'pdis' => $pdis
					]);

					$query = $this->model->editSalesSettings($settings);

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
				define('_title', '{$lang.title} | Dashboard');

	            $template = $this->view->render($this, 'sales');
	            $template = $this->format->replaceFile($template, 'header');

				$settings = $this->model->getAllSettings();
				$settings = json_decode($settings['sales'], true);

				$htmlSalesSettings = '';
				$tblPdisSettings = '';
				$mdlEditSalesSettings = '';

				$htmlSalesSettings .=
				'<div class="span6 pr">
	                <fieldset class="input-group">
	                    <label data-important>
	                        <span>Moneda principal</span>
	                        <input type="text" value="' . $settings['main_coin'] . '" disabled>
	                    </label>
	                </fieldset>
	                <fieldset class="input-group">
	                    <label data-important>
	                        <span>Impresión de ticket de venta</span>
	                        <input type="text" value="' . (($settings['sale_ticket_print'] == 'true') ? 'Activado' : 'Desactivado') . '" disabled>
	                    </label>
	                </fieldset>
	                <fieldset class="input-group">
	                    <label data-important>
	                        <span>Desglose de totales en ticket de venta</span>
	                        <input type="text" value="' . (($settings['sale_ticket_totals_breakdown'] == 'true') ? 'Activado' : 'Desactivado') . '" disabled>
	                    </label>
	                </fieldset>
	                <fieldset class="input-group">
	                    <label data-important>
	                        <span>Aplicar descuentos en ventas</span>
	                        <input type="text" value="' . (($settings['apply_discounds'] == 'true') ? 'Activado' : 'Desactivado') . '" disabled>
	                    </label>
	                </fieldset>
	                <fieldset class="input-group">
	                    <label data-important>
	                        <span>Permitir pagos diferidos</span>
	                        <input type="text" value="' . (($settings['deferred_payments'] == 'true') ? 'Activado' : 'Desactivado') . '" disabled>
	                    </label>
	                </fieldset>
					<fieldset class="input-group">
	                    <label data-important>
	                        <span>Sincronizar Punto de venta con Inventarios</span>
	                        <input type="text" value="' . (($settings['sync_point_sale_with_inventories'] == 'true') ? 'Activado' : 'Desactivado') . '" disabled>
	                    </label>
	                </fieldset>
					<fieldset class="input-group">
	                    <label data-important>
	                        <span>Sincronizar Cotizaciones con Inventarios</span>
	                        <input type="text" value="' . (($settings['sync_quotations_with_inventories'] == 'true') ? 'Activado' : 'Desactivado') . '" disabled>
	                    </label>
	                </fieldset>
					<fieldset class="input-group">
	                    <label data-important>
	                        <span>Tarífa de IVA</span>
	                        <input type="text" value="' . $settings['iva_rate'] . ' %" disabled>
	                    </label>
	                </fieldset>
	                <fieldset class="input-group">
	                    <label data-important>
	                        <span>Tarífa de cambio de USD</span>
	                        <input type="text" value="$ ' . $settings['usd_rate'] . ' MXN" disabled>
	                    </label>
	                </fieldset>
	                <fieldset class="input-group">
	                    <label data-important>
	                        <span>Leyenda de ticket de venta</span>
	                        <textarea disabled>' . $settings['sale_ticket_legend'] . '</textarea>
	                    </label>
	                </fieldset>
					<fieldset class="input-group">
						<a data-action="getSalesSettingsToEdit">Editar</a>
	                </fieldset>
	            </div>
				<div class="span6 pl">';

				$tblPdisSettings .=
				'<table id="tblPdisSettings" class="display" data-page-length="20">
					<thead>
						<tr>
							<th>Producto</th>
							<th>Inventario</th>
							<th>Sucursal</th>
						</tr>
					</thead>
					<tbody>';

				foreach ($settings['pdis'] as $pdis)
				{
					$product = $this->model->getProductById($pdis[0]);
					$inventory = $this->model->getInventoryById($pdis[1]);

					$branchOffice = $this->model->getBranchOfficeById($pdis[2]);

					if ($product['type'] == '1')
						$productType = 'Venta';
					else if ($product['type'] == '3')
						$productType = 'Producción';

					if ($inventory['type'] == '1')
						$inventoryType = 'Venta';
					else if ($inventory['type'] == '2')
						$inventoryType = 'Producción';

					$tblPdisSettings .=
					'<tr>
						<td>[' . $product['folio'] . '][' . $productType . '] ' . $product['name'] . '</td>
						<td>[' . $inventoryType . '] ' . $inventory['name'] . '</td>
						<th>' . $branchOffice['name'] . '</th>
					</tr>';
				}

				$tblPdisSettings .=
				'	</tbody>
				</table>
				<fieldset class="input-group" style="margin-top:20px;">
					<a data-button-modal="updatePdisSettings">Actualizar</a>
				</fieldset>';

				$tblPdisSettings .=
				'</div>
				<div class="clear"></div>';

				$mdlEditSalesSettings .=
				'<section class="modal" data-modal="editSalesSettings">
				    <div class="content">
				        <header>
				            <h6>Editar</h6>
				        </header>
				        <main>
				            <form name="editSalesSettings">
								<fieldset class="input-group">
				                    <p class="required-fields"><span class="required-field">*</span> Campos obligatorios</p>
				                </fieldset>
				                <fieldset class="input-group">
				                    <label data-important>
				                        <span><span class="required-field">*</span>Moneda principal</span>
				                        <select name="mainCoin">
				                            <option value="MXN">MXN</option>
				                            <option value="USD">USD</option>
				                        </select>
				                    </label>
				                </fieldset>
				                <fieldset class="input-group">
				                    <label data-important>
				                        <span><span class="required-field">*</span>Impresión de ticket de venta</span>
				                        <select name="saleTicketPrint">
				                            <option value="true">Activado</option>
				                            <option value="false">Desactivado</option>
				                        </select>
				                    </label>
				                </fieldset>
				                <fieldset class="input-group">
				                    <label data-important>
				                        <span><span class="required-field">*</span>Desglose de totales en ticket de venta</span>
				                        <select name="saleTicketTotalsBreakdown">
				                            <option value="true">Activado</option>
				                            <option value="false">Desactivado</option>
				                        </select>
				                    </label>
				                </fieldset>
				                <fieldset class="input-group">
				                    <label data-important>
				                        <span><span class="required-field">*</span>Aplicar descuentos en ventas</span>
				                        <select name="applyDiscounds">
				                            <option value="true">Activado</option>
				                            <option value="false">Desactivado</option>
				                        </select>
				                    </label>
				                </fieldset>
				                <fieldset class="input-group">
				                    <label data-important>
				                        <span><span class="required-field">*</span>Permitir pagos diferidos</span>
				                        <select name="deferred_payments">
				                            <option value="true">Activado</option>
				                            <option value="false">Desactivado</option>
				                        </select>
				                    </label>
				                </fieldset>
				                <fieldset class="input-group">
				                    <label data-important>
				                        <span><span class="required-field">*</span>Sincronizar Punto de venta con Invetarios</span>
				                        <select name="sync_point_sale_with_inventories">
				                            <option value="true">Activado</option>
				                            <option value="false">Desactivado</option>
				                        </select>
				                    </label>
				                </fieldset>
				                <fieldset class="input-group">
				                    <label data-important>
				                        <span><span class="required-field">*</span>Sincronizar Cotizaciones con Invetarios</span>
				                        <select name="sync_quotations_with_inventories">
				                            <option value="true">Activado</option>
				                            <option value="false">Desactivado</option>
				                        </select>
				                    </label>
				                </fieldset>
				                <fieldset class="input-group">
				                    <label data-important>
				                        <span><span class="required-field">*</span>Tarífa de IVA</span>
				                        <input type="number" name="ivaRate">
				                    </label>
				                </fieldset>
				                <fieldset class="input-group">
				                    <label data-important>
				                        <span><span class="required-field">*</span>Tarífa de cambio de USD</span>
				                        <input type="number" name="usdRate">
				                    </label>
				                </fieldset>
								<fieldset class="input-group">
				                    <label data-important>
				                        <span>Leyenda de ticket de venta</span>
				                        <textarea name="saleTicketLegend"></textarea>
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
					'{$htmlSalesSettings}' => $htmlSalesSettings,
					'{$tblPdisSettings}' => $tblPdisSettings,
					'{$mdlEditSalesSettings}' => $mdlEditSalesSettings
	            ];

	            $template = $this->format->replace($replace, $template);

	            echo $template;
			}
        }
        else
            header('Location: /settings');
	}

	/* Obtener configuracion de ventas para editar
	--------------------------------------------------------------------------- */
	public function getSalesSettingsToEdit()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$settings = $this->model->getAllSettings();
				$settings = json_decode($settings['sales'], true);

	            if (!empty($settings))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $settings
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Actualizar configuraciones PDIS
	--------------------------------------------------------------------------- */
	public function updatePdisSettings()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				set_time_limit(300);

				$settings = $this->model->getAllSettings();
				$settings = json_decode($settings['sales'], true);
				$inputs = $this->model->getAllInputs();

				$pdis = [];

				foreach ($inputs as $input)
				{
					$inventory = $this->model->getInventoryById($input['id_inventory']);
					$repeat = false;

					foreach ($pdis as $key)
					{
						if ($input['id_product'] == $key[0] AND $input['id_inventory'] == $key[1] AND $inventory['id_branch_office'] == $key[2])
							$repeat = true;
					}

					if ($repeat == false)
						array_push($pdis, [$input['id_product'], $input['id_inventory'], $inventory['id_branch_office']]);
				}

				if ($pdis == $settings['pdis'])
				{
					echo json_encode([
						'status' => 'success_same'
					]);
				}
				else
				{
					$settings = json_encode([
						'main_coin' => $settings['main_coin'],
						'sale_ticket_print' => $settings['sale_ticket_print'],
						'sale_ticket_totals_breakdown' => $settings['sale_ticket_totals_breakdown'],
						'apply_discounds' => $settings['apply_discounds'],
						'deferred_payments' => $settings['deferred_payments'],
						'sync_point_sale_with_inventories' => $settings['sync_point_sale_with_inventories'],
						'sync_quotations_with_inventories' => $settings['sync_quotations_with_inventories'],
						'iva_rate' => $settings['iva_rate'],
						'usd_rate' => $settings['usd_rate'],
						'sale_ticket_legend' => $settings['sale_ticket_legend'],
						'pdis' => $pdis
					]);

					$query = $this->model->editSalesSettings($settings);

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
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}
}
