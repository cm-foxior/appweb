<?php

defined('_EXEC') or die;

class Reports_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/*
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Session::getValue('level') >= 9)
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

	/* Reportes de inventario
	--------------------------------------------------------------------------- */
	public function inventories($type)
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				if ($type == 'existence')
					$query = $this->model->getExistence($_POST['inventory'], $_POST['category_one'], $_POST['category_two'], $_POST['category_tree'], $_POST['category_four'], $_POST['date_start'] . ' 00:00:00', $_POST['date_end'] . ' 11:59:59');
				else if ($type == 'historical')
				{
					if ($_POST['action'] == 'report')
						$query = $this->model->getHistorical($_POST['date1'] . ' 00:00:00', $_POST['date2'] . ' 11:59:59', $_POST['inventory']);
					else if ($_POST['action'] == 'inventories')
						$query = $this->model->getInventories($_POST['branch']);
				}

				echo json_encode([
					'status' => 'success',
					'data' => $query
				]);
			}
			else
			{
				define('_title', '{$lang.title} | Dashboard');

				$template = $this->view->render($this, 'inventories');
				$template = $this->format->replaceFile($template, 'header');

				$html =
				'<fieldset class="input-group span2">
	                <label data-important>
	                    <span>Tipo de reporte</span>
	                    <select id="etyp" name="type">
	                        <option value="existence" ' . (($type == 'existence') ? 'selected' : '') . '>Existencia</option>
	        				<option value="historical" ' . (($type == 'historical') ? 'selected' : '') . '>Historico de Entradas y salidas</option>
	                    </select>
	                </label>
	            </fieldset>
	            <div class="clear"></div>';

				if ($type == 'existence')
				{
					$html .=
					'<form name="existence" class="row">
						<fieldset class="input-group span4 pr">
			                <label data-important>
			                    <span>Inventario</span>
								<select id="einv" name="inventory" class="chosen-select">';

					if (Session::getValue('level') == 10)
						$inventories = $this->model->getInventories();
					else if (Session::getValue('level') == 9)
						$inventories = $this->model->getInventories(Session::getValue('id_branch_office'));

					foreach ($inventories as $value)
						$html .= '<option value="' . $value['id_inventory'] . '">' . $value['name'] . ' (' . $value['type'] . ') Suc. ' . $value['branch'] . '</option>';

					$html .=
				    '			</select>
			                </label>
			            </fieldset>
						<fieldset class="input-group span2 pr">
			                <label data-important>
			                    <span>Categoría 1</span>
								<select name="category_one" class="chosen-select">
									<option value="">Todo</option>';

					foreach ($this->model->getAllCategories('one') as $value)
						$html .= '<option value="' . $value['id_product_category_one'] . '">' . $value['name'] . '</option>';

					$html .=
				    '			</select>
			                </label>
			            </fieldset>
						<fieldset class="input-group span2 pr">
			                <label data-important>
			                    <span>Categoría 2</span>
								<select name="category_two" class="chosen-select">
									<option value="">Todo</option>';

					foreach ($this->model->getAllCategories('two') as $value)
						$html .= '<option value="' . $value['id_product_category_two'] . '">' . $value['name'] . '</option>';

					$html .=
				    '			</select>
			                </label>
			            </fieldset>
						<fieldset class="input-group span2 pr">
			                <label data-important>
			                    <span>Categoría 3</span>
								<select name="category_tree" class="chosen-select">
									<option value="">Todo</option>';

					foreach ($this->model->getAllCategories('tree') as $value)
						$html .= '<option value="' . $value['id_product_category_tree'] . '">' . $value['name'] . '</option>';

					$html .=
				    '			</select>
			                </label>
			            </fieldset>
						<fieldset class="input-group span2">
			                <label data-important>
			                    <span>Categoría 4</span>
								<select name="category_four" class="chosen-select">
									<option value="">Todo</option>';

					foreach ($this->model->getAllCategories('four') as $value)
						$html .= '<option value="' . $value['id_product_category_four'] . '">' . $value['name'] . '</option>';

					$html .=
				    '			</select>
			                </label>
			            </fieldset>
						<fieldset class="input-group span2 pr">
			                <label data-important>
			                    <span>Fecha 1</span>
								<input type="date" name="date_start" value="' . date('Y-m-d', strtotime(date('Y-m-d') . ' - 7 days')) . '">
			                </label>
			            </fieldset>
						<fieldset class="input-group span2 pr">
			                <label data-important>
			                    <span>Fecha 2</span>
								<input type="date" name="date_end" value="' . date('Y-m-d') . '">
			                </label>
			            </fieldset>
					<form>
		            <table id="existence" class="display" data-page-length="500">
						<tbody>';

					$existence = $this->model->getExistence($inventories[0]['id_inventory'], '', '', '', '', date('Y-m-d', strtotime(date('Y-m-d') . ' - 7 days')) . ' 00:00:00', date('Y-m-d') . ' 11:59:59');

					foreach ($existence as $value)
					{
						$html .=
						'<tr>
							<td>' . $value['product'] . '</td>
							<td>' . $value['inputs'] . '</td>
							<td>' . $value['outputs'] . '</td>
							<td>' . $value['flirts'] . '</td>
							<td>' . $value['existence'] . '</td>
							<td>' . $value['min'] . '</td>
							<td>' . $value['max'] . '</td>
							<td>' . $value['status'] . '</td>
						</tr>';
					}

					$html .=
					'	</tbody>
					</table>';
				}
				else if ($type == 'historical')
				{
					$html .=
					'<form name="historical">
						<fieldset class="input-group ' . ((Session::getValue('level') == 10) ? 'span2' : 'span3') . ' pr">
			                <label data-important>
			                    <span>Fecha 1</span>
								<input id="hda1" type="date" name="date1" value="' . Format::getDate() . '">
			                </label>
			            </fieldset>
						<fieldset class="input-group ' . ((Session::getValue('level') == 10) ? 'span2' : 'span3') . ' pr">
			                <label data-important>
			                    <span>Fecha 2</span>
								<input id="hda2" type="date" name="date2" value="' . Format::getDate() . '">
			                </label>
			            </fieldset>';

					if (Session::getValue('level') == 10)
					{
						$branchs = $this->model->getBranchs();

						$html .=
						'<fieldset class="input-group span4 pr">
			                <label data-important>
			                    <span>Sucursal</span>
								<select id="hbra" name="branch" class="chosen-select">';

						foreach ($branchs as $value)
							$html .= '<option value="' . $value['id_branch_office'] . '">' . $value['name'] . '</option>';

				        $html .=
						'        </select>
			                </label>
			            </fieldset>';
					}

					$html .=
					'<fieldset class="input-group ' . ((Session::getValue('level') == 10) ? 'span4' : 'span6') . '">
						<label data-important>
							<span>Inventario</span>
							<select id="hinv" name="inventory" class="chosen-select">';

					if (Session::getValue('level') == 10)
						$inventories = $this->model->getInventories($branchs[0]['id_branch_office']);
					else if (Session::getValue('level') == 9)
						$inventories = $this->model->getInventories(Session::getValue('id_branch_office'));

					foreach ($inventories as $value)
						$html .= '<option value="' . $value['id_inventory'] . '">' . $value['name'] . ' (' . $value['type'] . ') Suc. ' . $value['branch'] . '</option>';

					$html .=
					'			</select>
							</label>
						</fieldset>
						<div class="clear"></div>
					</form>
		            <table id="historical" class="display" data-page-length="500">
						<tbody>';

					$historical = $this->model->getHistorical(Format::getDate() . ' 00:00:00', Format::getDate() . ' 11:59:59', $inventories[0]['id_inventory']);

					foreach ($historical as $value)
					{
						$html .=
						'<tr>
							<td>' . $value['product'] . '</td>
							<td>' . $value['quantify'] . '</td>
							<td>' . $value['date'] . '</td>
							<td>' . $value['provider'] . '</td>
							<td>' . $value['type'] . '</td>
							<td>' . $value['movement'] . '</td>
						</tr>';
					}

					$html .=
					'	</tbody>
					</table>';
				}

				$replace = [
					'{$html}' => $html
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
            header('Location: /dashboard');
	}

	/* Reportes de ventas
	--------------------------------------------------------------------------- */
	public function sales()
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				if ($_POST['action'] == 'report')
				{
					if (Session::getValue('level') == 10)
						$query = $this->model->getSales($_POST['date1'] . ' 00:00:00', $_POST['date2'] . ' 11:59:59', $_POST['branch'], $_POST['seller']);
					else if (Session::getValue('level') == 9)
						$query = $this->model->getSales($_POST['date1'] . ' 00:00:00', $_POST['date2'] . ' 11:59:59', Session::getValue('id_branch_office'), $_POST['seller']);
				}
				else if ($_POST['action'] == 'sellers')
					$query = $this->model->getSellers($_POST['branch']);

				echo json_encode([
					'status' => 'success',
					'data' => $query
				]);
			}
			else
			{
				define('_title', '{$lang.title} | Dashboard');

				$template = $this->view->render($this, 'sales');
				$template = $this->format->replaceFile($template, 'header');

				$html =
				'<form name="sales">
		            <fieldset class="input-group span3 pr">
		                <label data-important>
		                    <span>Fecha 1</span>
		                    <input id="sda1" type="date" name="date1" value="' . Format::getDate() . '">
		                </label>
		            </fieldset>
		            <fieldset class="input-group span3 pr">
		                <label data-important>
		                    <span>Fecha 2</span>
		                    <input id="sda2" type="date" name="date2" value="' . Format::getDate() . '">
		                </label>
		            </fieldset>';

				if (Session::getValue('level') == 10)
				{
					$branchs = $this->model->getBranchs();

					$html .=
					'<fieldset class="input-group span3 pr">
						<label data-important>
							<span>Sucursal</span>
							<select id="sbra" name="branch" class="chosen-select">';

					foreach ($branchs as $value)
						$html .= '<option value="' . $value['id_branch_office'] . '">' . $value['name'] . '</option>';

					$html .=
					'        </select>
						</label>
					</fieldset>';
				}

				$html .=
				'<fieldset class="input-group ' . ((Session::getValue('level') == 10) ? 'span3' : 'span6') . '">
					<label data-important>
						<span>Vendedor</span>
						<select id="ssel" name="seller" class="chosen-select">
							<option value="all">Todos</option>';

				if (Session::getValue('level') == 10)
					$sellers = $this->model->getSellers($branchs[0]['id_branch_office']);
				else if (Session::getValue('level') == 9)
					$sellers = $this->model->getSellers(Session::getValue('id_branch_office'));

				foreach ($sellers as $value)
					$html .= '<option value="' . $value['id_user'] . '">' . $value['name'] . '</option>';

				$html .=
				'			</select>
						</label>
					</fieldset>
					<div class="clear"></div>
				</form>
				<table id="sales" class="display" data-page-length="500">
					<tbody>';

				if (Session::getValue('level') == 10)
					$sales = $this->model->getSales(Format::getDate() . ' 00:00:00', Format::getDate() . ' 11:59:59', $branchs[0]['id_branch_office'], 'all');
				else if (Session::getValue('level') == 9)
					$sales = $this->model->getSales(Format::getDate() . ' 00:00:00', Format::getDate() . ' 11:59:59', Session::getValue('id_branch_office'), 'all');

				foreach ($sales[0] as $value)
				{
					$html .=
					'<tr>
						<td>' . $value['folio'] . '</td>
						<td>' . $value['total'] . '</td>
						<td>' . $value['payment'] . '</td>
						<td>' . $value['date'] . '</td>
						<td>' . $value['seller'] . '</td>
						<td>' . $value['sales'] . '</td>
						<td>' . $value['status'] . '</td>
					</tr>';
				}

				$html .=
				'		<tr>
							<td></td>
							<td><strong>$ ' . $sales[1] . ' MXN</strong></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>';

				$replace = [
					'{$html}' => $html
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/* gráficas y estadisticas
	--------------------------------------------------------------------------- */
	public function graphs()
	{
		if (Session::getValue('level') >= 9)
        {
			define('_title', '{$lang.title} | Dashboard');

            $template = $this->view->render($this, 'graphs');
            $template = $this->format->replaceFile($template, 'header');

			$replace = [

			];

            $template = $this->format->replace($replace, $template);

            echo $template;
        }
        else
            header('Location: /dashboard');
	}
}
