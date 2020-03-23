<?php

defined('_EXEC') or die;

class Inventories_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($params)
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'switch_branch')
			{
				$query = $this->model->read_branch($_POST['token']);

				if (!empty($query))
				{
					Functions::temporal_session('set', 'branch', $query);

					echo json_encode([
						'status' => 'success'
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'message' => '{$lang.operation_error}'
					]);
				}
			}

			if ($_POST['action'] == 'read_product')
			{
				$query = $this->model->read_product($_POST['id']);

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'data' => $query
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'message' => '{$lang.operation_error}'
					]);
				}
			}

			if ($_POST['action'] == 'add_product_to_table')
			{
				$errors = [];

				if (Validations::empty($_POST['product']) == false)
					array_push($errors, ['product','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['quantity']) == false)
					array_push($errors, ['quantity','{$lang.dont_leave_this_field_empty}']);

				if (Validations::equals($_POST['saved'], ['bill','remission']) == true AND Validations::empty($_POST['price']) == false)
					array_push($errors, ['price','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					$query = $this->model->read_product($_POST['product']);

					if (!empty($query))
					{
						$tmp = Functions::temporal_session('get', 'products');

						if (array_key_exists($query['id'], $tmp))
						{
							$tmp[$query['id']]['location'] = (Validations::empty($_POST['location']) == true) ? $this->model->read_inventory_location($_POST['location']) : [];
							$tmp[$query['id']]['categories'][0] = (Validations::empty($_POST['categories']) == true) ? $_POST['categories'] : [];
							$tmp[$query['id']]['categories'][1] = (Validations::empty($_POST['categories']) == true) ? $this->model->read_inventories_categories($_POST['categories']) : '';
							$tmp[$query['id']]['quantity'] += $_POST['quantity'];
							$tmp[$query['id']]['price'] = $_POST['price'];
							$tmp[$query['id']]['total'] = ($tmp[$query['id']]['quantity'] * $_POST['price']);
						}
						else
						{
							$tmp[$query['id']] = [
								'location' => (Validations::empty($_POST['location']) == true) ? $this->model->read_inventory_location($_POST['location']) : [],
								'categories' => [
									[0] => (Validations::empty($_POST['categories']) == true) ? $_POST['categories'] : [],
									[1] => (Validations::empty($_POST['categories']) == true) ? $this->model->read_inventories_categories($_POST['categories']) : ''
								],
								'product' => $query,
								'quantity' => $_POST['quantity'],
								'price' => $_POST['price'],
								'total' => ($_POST['quantity'] * $_POST['price'])
							];
						}

						Functions::temporal_session('set', 'products', $tmp);

						$table = '';

						foreach ($tmp as $value)
						{
							$table .=
							'<tr>
								<td class="avatar">
									<figure>
										<img src="' . (!empty($value['product']['avatar']) ? '{$path.uploads}' . $value['product']['avatar'] : '{$path.images}empty.png') . '">
									</figure>
								</td>
								<td>
									' . $value['product']['token'] . ' | ' . $value['product']['name'] . ' | {$lang.' . $value['product']['type'] . '}
									<br>
									' . $value['quantity'] . ' ' . $value['product']['unity'] .
									' (' . Currency::format($value['price'], Session::get_value('vkye_account')['currency']) . ')' .
									' = ' . Currency::format($value['total'], Session::get_value('vkye_account')['currency']) . '
									<br>
									' . (!empty($value['location']) ? $value['location']['name'] ; '{$lang.not_location}') . '
									<br>
									' . (!empty($value['categories'][1]) ? $value['categories'][1] ; '{$lang.not_categories}') . '
								</td>
								<td class="button">
									<a data-action="remove_product_to_table" data-id="' . $value['product']['id'] . '" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
								</td>
							</tr>';
						}

						echo json_encode([
							'status' => 'success',
							'data' => [
								'total' => Currency::format(System::summation(Functions::temporal_session('get', 'products'), 'total'), Session::get_value('vkye_account')['currency']),
								'table' => $table
							]
						]);
					}
					else
					{
						echo json_encode([
							'status' => 'error',
							'message' => '{$lang.operation_error}'
						]);
					}
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'errors' => $errors
					]);
				}
			}

			if ($_POST['action'] == 'remove_product_to_table')
			{
				$tmp = Functions::temporal_session('get', 'products');

				unset($tmp[$_POST['id']]);

				Functions::temporal_session('set', 'products', $tmp);

				$table = '';

				foreach ($tmp as $value)
				{
					$table .=
					'<tr>
						<td class="avatar">
							<figure>
								<img src="' . (!empty($value['product']['avatar']) ? '{$path.uploads}' . $value['product']['avatar'] : '{$path.images}empty.png') . '">
							</figure>
						</td>
						<td>
							' . $value['product']['token'] . ' | ' . $value['product']['name'] . ' | {$lang.' . $value['product']['type'] . '}
							<br>
							' . $value['quantity'] . ' ' . $value['product']['unity'] .
							' (' . Currency::format($value['price'], Session::get_value('vkye_account')['currency']) . ')' .
							' = ' . Currency::format($value['total'], Session::get_value('vkye_account')['currency']) . '
							<br>
							' . (!empty($value['location']) ? $value['location']['name'] ; '{$lang.not_location}') . '
							<br>
							' . (!empty($value['categories'][1]) ? $value['categories'][1] ; '{$lang.not_categories}') . '
						</td>
						<td class="button">
							<a data-action="remove_product_to_table" data-id="' . $value['product']['id'] . '" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
						</td>
					</tr>';
				}

				echo json_encode([
					'status' => 'success',
					'data' => [
						'total' => Currency::format(System::summation(Functions::temporal_session('get', 'products'), 'total'), Session::get_value('vkye_account')['currency']),
						'table' => $table
					]
				]);
			}

			if ($_POST['action'] == 'create_inventory_input')
			{
				$errors = [];

				if (Validations::empty($_POST['input']) == false)
					array_push($errors, ['input','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['control']) == false)
					array_push($errors, ['control','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['saved']) == false)
					array_push($errors, ['saved','{$lang.dont_leave_this_field_empty}']);

				if (Validations::equals($_POST['saved'], 'bill') == true AND Validations::empty($_POST['bill']) == false)
					array_push($errors, ['bill','{$lang.dont_leave_this_field_empty}']);

				if (Validations::equals($_POST['saved'], 'remission') == true AND Validations::empty($_POST['remission']) == false)
					array_push($errors, ['remission','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['type']) == false)
					array_push($errors, ['type','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['date']) == false)
					array_push($errors, ['date','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['hour']) == false)
					array_push($errors, ['hour','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty(Functions::temporal_session('get', 'products')) == false)
					array_push($errors, ['product','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					$query = $this->model->create_inventory_input($_POST);

					if (!empty($query))
					{
						echo json_encode([
							'status' => 'success',
							'message' => '{$lang.operation_success}'
						]);
					}
					else
					{
						echo json_encode([
							'status' => 'error',
							'message' => '{$lang.operation_error}'
						]);
					}
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
			define('_title', Configuration::$web_page . ' | {$lang.' . $GLOBALS['_vkye_module'] . '}');

			global $data;

			$data['branches'] = $this->model->read_branches();

			if (!empty($data['branches']))
			{
				Functions::temporal_session('set', 'branch', $data['branches'][0]);

				$data['inventories'] = $this->model->read_inventories(Functions::temporal_session('get', 'branch')['id']);
				$data['products'] = $this->model->read_products();
				$data['inventories_locations'] = $this->model->read_inventories_locations(true);
				$data['inventories_types'] = $this->model->read_inventories_types(true, 'input');
				$data['inventories_categories'] = $this->model->read_inventories_categories(true);
			}

			$template = $this->view->render($this, 'index');

			echo $template;
		}
	}

	public function types()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_inventory_type' OR $_POST['action'] == 'update_inventory_type')
			{
				$errors = [];

				if (Validations::empty($_POST['name']) == false)
					array_push($errors, ['name','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['movement']) == false)
					array_push($errors, ['movement','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					if ($_POST['action'] == 'create_inventory_type')
						$query = $this->model->create_inventory_type($_POST);
					else if ($_POST['action'] == 'update_inventory_type')
						$query = $this->model->update_inventory_type($_POST);

					if (!empty($query))
					{
						echo json_encode([
							'status' => 'success',
							'message' => '{$lang.operation_success}'
						]);
					}
					else
					{
						echo json_encode([
							'status' => 'error',
							'message' => '{$lang.operation_error}'
						]);
					}
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'errors' => $errors
					]);
				}
			}

			if ($_POST['action'] == 'read_inventory_type')
			{
				$query = $this->model->read_inventory_type($_POST['id']);

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'data' => $query
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'message' => '{$lang.operation_error}'
					]);
				}
			}

			if ($_POST['action'] == 'block_inventory_type' OR $_POST['action'] == 'unblock_inventory_type' OR $_POST['action'] == 'delete_inventory_type')
			{
				if ($_POST['action'] == 'block_inventory_type')
					$query = $this->model->block_inventory_type($_POST['id']);
				else if ($_POST['action'] == 'unblock_inventory_type')
					$query = $this->model->unblock_inventory_type($_POST['id']);
				else if ($_POST['action'] == 'delete_inventory_type')
					$query = $this->model->delete_inventory_type($_POST['id']);

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'message' => '{$lang.operation_success}'
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'message' => '{$lang.operation_error}'
					]);
				}
			}
		}
		else
		{
			define('_title', Configuration::$web_page . ' | {$lang.' . $GLOBALS['_vkye_module'] . '} | {$lang.types}');

			global $data;

			$data['inventories_types'] = $this->model->read_inventories_types();

			$template = $this->view->render($this, 'types');

			echo $template;
		}
	}

	public function locations()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_inventory_location' OR $_POST['action'] == 'update_inventory_location')
			{
				$errors = [];

				if (Validations::empty($_POST['name']) == false)
					array_push($errors, ['name','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					if ($_POST['action'] == 'create_inventory_location')
						$query = $this->model->create_inventory_location($_POST);
					else if ($_POST['action'] == 'update_inventory_location')
						$query = $this->model->update_inventory_location($_POST);

					if (!empty($query))
					{
						echo json_encode([
							'status' => 'success',
							'message' => '{$lang.operation_success}'
						]);
					}
					else
					{
						echo json_encode([
							'status' => 'error',
							'message' => '{$lang.operation_error}'
						]);
					}
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'errors' => $errors
					]);
				}
			}

			if ($_POST['action'] == 'read_inventory_location')
			{
				$query = $this->model->read_inventory_location($_POST['id']);

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'data' => $query
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'message' => '{$lang.operation_error}'
					]);
				}
			}

			if ($_POST['action'] == 'block_inventory_location' OR $_POST['action'] == 'unblock_inventory_location' OR $_POST['action'] == 'delete_inventory_location')
			{
				if ($_POST['action'] == 'block_inventory_location')
					$query = $this->model->block_inventory_location($_POST['id']);
				else if ($_POST['action'] == 'unblock_inventory_location')
					$query = $this->model->unblock_inventory_location($_POST['id']);
				else if ($_POST['action'] == 'delete_inventory_location')
					$query = $this->model->delete_inventory_location($_POST['id']);

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'message' => '{$lang.operation_success}'
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'message' => '{$lang.operation_error}'
					]);
				}
			}
		}
		else
		{
			define('_title', Configuration::$web_page . ' | {$lang.' . $GLOBALS['_vkye_module'] . '} | {$lang.locations}');

			global $data;

			$data['inventories_locations'] = $this->model->read_inventories_locations();

			$template = $this->view->render($this, 'locations');

			echo $template;
		}
	}

	public function categories()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_inventory_category' OR $_POST['action'] == 'update_inventory_category')
			{
				$errors = [];

				if (Validations::empty($_POST['name']) == false)
					array_push($errors, ['name','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['level']) == false)
					array_push($errors, ['level','{$lang.dont_leave_this_field_empty}']);
				else if (Validations::number('int', $_POST['level']) == false)
					array_push($errors, ['level','{$lang.invalid_field}']);

				if (empty($errors))
				{
					if ($_POST['action'] == 'create_inventory_category')
						$query = $this->model->create_inventory_category($_POST);
					else if ($_POST['action'] == 'update_inventory_category')
						$query = $this->model->update_inventory_category($_POST);

					if (!empty($query))
					{
						echo json_encode([
							'status' => 'success',
							'message' => '{$lang.operation_success}'
						]);
					}
					else
					{
						echo json_encode([
							'status' => 'error',
							'message' => '{$lang.operation_error}'
						]);
					}
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'errors' => $errors
					]);
				}
			}

			if ($_POST['action'] == 'read_inventory_category')
			{
				$query = $this->model->read_inventory_category($_POST['id']);

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'data' => $query
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'message' => '{$lang.operation_error}'
					]);
				}
			}

			if ($_POST['action'] == 'block_inventory_category' OR $_POST['action'] == 'unblock_inventory_category' OR $_POST['action'] == 'delete_inventory_category')
			{
				if ($_POST['action'] == 'block_inventory_category')
					$query = $this->model->block_inventory_category($_POST['id']);
				else if ($_POST['action'] == 'unblock_inventory_category')
					$query = $this->model->unblock_inventory_category($_POST['id']);
				else if ($_POST['action'] == 'delete_inventory_category')
					$query = $this->model->delete_inventory_category($_POST['id']);

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'message' => '{$lang.operation_success}'
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'message' => '{$lang.operation_error}'
					]);
				}
			}
		}
		else
		{
			define('_title', Configuration::$web_page . ' | {$lang.' . $GLOBALS['_vkye_module'] . '} | {$lang.categories}');

			global $data;

			$data['inventories_categories'] = $this->model->read_inventories_categories();

			$template = $this->view->render($this, 'categories');

			echo $template;
		}
	}
}
