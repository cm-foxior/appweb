<?php

defined('_EXEC') or die;

class Search_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/*
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Format::existAjaxRequest() == true)
		{
			$action = $_POST['action'];

			$settings = $this->model->getSettings();
			$applyDiscounds = json_decode($settings['sales'], true)['apply_discounds'];

			if ($action == 'search')
			{
				$idProduct = (isset($_POST['idProduct']) AND !empty($_POST['idProduct'])) ? $_POST['idProduct'] : null;

				$product = $this->model->getProductById($idProduct);
				$existence = $this->model->getExistence($idProduct);

				$price = json_decode($product['price'], true);

				if ($product['coin'] == '1')
					$coin = 'MXN';
				else if ($product['coin'] == '2')
					$coin = 'USD';

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

				if (!empty($product['category_one']))
					$categoryOne = $product['category_one'];
				else
					$categoryOne = '';

				if (!empty($product['category_two']))
					$categoryTwo = $product['category_two'];
				else
					$categoryTwo = '';

				if (!empty($product['category_tree']))
					$categoryTree = $product['category_tree'];
				else
					$categoryTree = '';

				if (!empty($product['category_four']))
					$categoryFour = $product['category_four'];
				else
					$categoryFour = '';

				if (!empty($product['discount']))
				{
					$discount = json_decode($product['discount'], true);
					$discount = (($discount['type'] == '1') ? '$ ' : '') . $discount['quantity'] . (($discount['type'] == '1') ? ' ' . $coin : ' %');
				}
				else
					$discount = 'Sin descuento';

				if (!empty($product['quantity']) AND !empty($product['time_frame']))
					$warranty = $product['quantity'] . ' ' . $product['time_frame'];
				else
					$warranty = 'Sin garantía';

				$html =
				'<div class="searchp">
					<div class="data">
						<strong>Producto: </strong> ' . $product['name'] . ' (' . $categoryOne . ' ' . $categoryTwo . ' ' . $categoryTree . ' ' . $categoryFour . ')
						<br><strong>Folio: </strong> ' . $product['folio'] . '
						<br><strong>Précio preferencial: </strong>' . ((!empty($price['pref_price'])) ? '$ ' . $price['pref_price'] . ' ' . $coin : 'No disponible') . '
						<br><strong>Précio de venta: </strong>$ ' . $price['public_price'] . ' ' . $coin . '
						<br><strong>Descuento: </strong> ' . $discount . '
						<br><strong>Garantía: </strong> ' . $warranty . '
						<br><strong>Observaciones: </strong> ' . (!empty($product['observations']) ? $product['observations'] : 'Sin observaciones') . '
					</div>
					<div class="buttons">
						<form>
							<input type="number" name="quan" value="1" min="1" />';

				if (Session::getValue('level') == 10)
				{
					$html .= '<select name="bran">';

					$branchs = $this->model->getBranchOffices();

					foreach ($branchs as $value)
						$html .= '<option value="' . $value['id_branch_office'] . '">' . $value['name'] . '</option>';

					$html .= '</select>';
				}

				if ($applyDiscounds == 'true')
				{
					$html .=
					'<select name="disc">
						<option value="false">Sin descuento</option>
						<option value="true">Con descuento</option>
					</select>';
				}

				$html .=
				'			<a data-action="tosell" data-id="' . $product['folio'] . '">Nueva venta</a>
						</form>
						<form>
							<a>Nuevo prestamo</a>
						</form>
						<form>
							<a>Transferir</a>
						</form>
						<form>
							<a>Inventariar</a>
						</form>
					</div>
				</div>
				<table class="searchp">
					<thead>
						<tr>
							<th>Sucursal</th>
							<th>Inventario</th>
							<th width="100px">Disponible</th>
						</tr>
					</thead>
					<tbody>';

				foreach ($existence as $value)
				{
					$html .=
					'<tr>
						<td>' . $value['inventory']['branch_office'] . '</td>
						<td>' . $value['inventory']['name'] . '</td>
						<td>' . $value['available'] . ' ' . $unity . '</td>
					</tr>';
				}

				$html .=
					'</tbody>
				</table>';

				echo json_encode([
					'status' => 'success',
					'html' => $html
				]);
			}

			if ($action == 'tosell')
			{
				$errors = [];

				if (!isset($_POST['quan']) OR empty($_POST['quan']))
					array_push($errors, ['quan']);
				else if ($_POST['quan'] < 1)
					array_push($errors, ['quan']);

				if (Session::getValue('level') == 10)
				{
					if (!isset($_POST['bran']) OR empty($_POST['bran']))
						array_push($errors, ['bran']);
				}

				if (!isset($_POST['disc']) OR empty($_POST['disc']))
					array_push($errors, ['disc']);
				else if ($_POST['disc'] != 'true' AND $_POST['disc'] != 'false')
					array_push($errors, ['disc']);

				if (empty($errors))
				{
					Session::setValue('sappl', 'true');
					Session::setValue('sfoli', $_POST['foli']);
					Session::setValue('squan', $_POST['quan']);

					if (Session::getValue('level') == 10)
						Session::setValue('sbran', $_POST['bran']);
					else
					{
						$logged = $this->model->getUserLogged();
						Session::setValue('sbran', $logged['id_branch_office']);
					}

					if ($applyDiscounds == 'true')
						Session::setValue('sdisc', $_POST['disc']);
					else
						Session::setValue('sdisc', 'false');

					echo json_encode([
						'status' => 'success'
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'errors' => $errors
					]);
				}
			}
		}
		else
		{
			define('_title', '{$lang.title} | Dashboard');

			$template = $this->view->render($this, 'index');
			$template = $this->format->replaceFile($template, 'header');

			$products = $this->model->getProducts();

			$lstProducts = '';

			foreach ($products as $product)
			{
				$lstProducts .=
				'<option value="' . $product['id_product'] . '">[' . $product['folio'] . '] ' . $product['name'] . ' ' . $product['category_one'] . ' ' . $product['category_two'] . ' ' . $product['category_tree'] . ' ' . $product['category_four'] . '</option>';
			}

			$replace = [
				'{$lstProducts}' => $lstProducts
			];

			$template = $this->format->replace($replace, $template);

			echo $template;
		}
	}
}
