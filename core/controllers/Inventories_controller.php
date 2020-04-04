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
				$query = $this->model->read_branch($_POST['id']);

				if (!empty($query))
				{
					Functions::temporal('set_forced', 'inventories', 'branch', $query);

					echo json_encode([
						'status' => 'success',
						'path' => '/inventories/' . System::clean_string('url', $query['name'])
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

			if ($_POST['action'] == 'read_product_to_add_to_input_table')
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

			if ($_POST['action'] == 'add_product_to_input_table')
			{
				$errors = [];

				if (Validations::empty($_POST['product']) == false)
					array_push($errors, ['product','{$lang.dont_leave_this_field_empty}']);
				else
				{
					$_POST['product'] = $this->model->read_product($_POST['product']);

					if (Validations::empty($_POST['product']['input_unity']) == true AND Validations::empty($_POST['input_quantity']) == false)
						array_push($errors, ['input_quantity','{$lang.dont_leave_this_field_empty}']);

					if (Validations::empty($_POST['storage_quantity']) == false)
						array_push($errors, ['storage_quantity','{$lang.dont_leave_this_field_empty}']);

					if (Validations::empty($_POST['price']) == false)
						array_push($errors, ['price','{$lang.dont_leave_this_field_empty}']);

					if (Validations::empty($_POST['location']) == false)
						array_push($errors, ['location','{$lang.dont_leave_this_field_empty}']);
				}

				if (empty($errors))
				{
					$_POST['location'] = $this->model->read_inventory_location($_POST['location']);
					$_POST['categories'] = !empty($_POST['categories']) ? $this->model->read_inventories_categories($_POST['categories']) : [];

					$temporal = Functions::temporal('get', 'inventories', 'inputs');

					$key = $_POST['product']['id'];

					if (array_key_exists($key, $temporal))
					{
						$temporal[$key]['input_quantity'] = !empty($_POST['product']['input_unity']) ? ($temporal[$key]['input_quantity'] + $_POST['input_quantity']) : 0;
						$temporal[$key]['transform_quantity'] = !empty($_POST['product']['input_unity']) ? $_POST['storage_quantity'] : 0;
						$temporal[$key]['storage_quantity'] = !empty($_POST['product']['input_unity']) ? ($_POST['storage_quantity'] * $temporal[$key]['input_quantity']) : ($temporal[$key]['storage_quantity'] + $_POST['storage_quantity']);
						$temporal[$key]['price'] = $_POST['price'];
						$temporal[$key]['total'] = !empty($_POST['product']['input_unity']) ? ($_POST['price'] * $temporal[$key]['input_quantity']) : ($_POST['price'] * $temporal[$key]['storage_quantity']);
						$temporal[$key]['location'] = $_POST['location'];
						$temporal[$key]['categories'] = $_POST['categories'];
					}
					else
					{
						$temporal[$key] = [
							'product' => $_POST['product'],
							'input_quantity' => !empty($_POST['product']['input_unity']) ? $_POST['input_quantity'] : 0,
							'transform_quantity' => !empty($_POST['product']['input_unity']) ? $_POST['storage_quantity'] : 0,
							'storage_quantity' => !empty($_POST['product']['input_unity']) ? ($_POST['storage_quantity'] * $_POST['input_quantity']) : $_POST['storage_quantity'],
							'price' => $_POST['price'],
							'total' => !empty($_POST['product']['input_unity']) ? ($_POST['price'] * $_POST['input_quantity']) : ($_POST['price'] * $_POST['storage_quantity']),
							'location' => $_POST['location'],
							'categories' => $_POST['categories']
						];
					}

					Functions::temporal('set_forced', 'inventories', 'inputs', $temporal);

					$table = '';

					foreach ($temporal as $value)
					{
						$table .=
						'<tr>
							<td class="avatar">
								<figure>
									<img src="' . (!empty($value['product']['avatar']) ? '{$path.uploads}' . $value['product']['avatar'] : '{$path.images}empty.png') . '">
								</figure>
							</td>
							<td>
								{$lang.product} ({$lang.' . $value['product']['type'] . '}): ' . $value['product']['name'] . ' (' . $value['product']['token'] . ')
								<br>
								{$lang.storage}: ' . (!empty($value['product']['input_unity'])
									? $value['input_quantity'] . ' ' . $value['product']['input_unity'] . ' {$lang.of} ' . $value['transform_quantity'] . ' ' . $value['product']['storage_unity'] . ' {$lang.abb_each_one} (' . $value['storage_quantity'] . ' ' . $value['product']['storage_unity'] . ')'
										: $value['storage_quantity'] . ' ' . $value['product']['storage_unity']) . '
								<br>
								{$lang.unitary_price} (' . (!empty($value['product']['input_unity']) ? $value['product']['input_unity'] : $value['product']['storage_unity']) . '): ' . Currency::format($value['price'], Session::get_value('vkye_account')['currency']) . '
								<br>
								{$lang.total}: ' . Currency::format($value['total'], Session::get_value('vkye_account')['currency']) . '
								<br>
								{$lang.location}: ' . $value['location']['name'] . '
								<br>
								' . (!empty($value['categories']) ? '{$lang.categories}: ' . Functions::summation('string', $value['categories'], 'name') : '{$lang.not_categories}') . '
							</td>
							<td class="button">
								<a data-action="remove_product_to_input_table" data-id="' . $value['product']['id'] . '" class="alert"><i class="fas fa-trash"></i><span>{$lang.remove_to_table}</span></a>
							</td>
						</tr>';
					}

					$table .=
					'<tr>
						<td></td>
						<td class="message">' . Currency::format(Functions::summation('math', Functions::temporal('get', 'inventories', 'inputs'), 'total'), Session::get_value('vkye_account')['currency']) . '</td>
						<td></td>
					</tr>';

					echo json_encode([
						'status' => 'success',
						'data' => [
							'table' => $table
						]
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

			if ($_POST['action'] == 'remove_product_to_input_table')
			{
				$temporal = Functions::temporal('get', 'inventories', 'inputs');

				unset($temporal[$_POST['id']]);

				Functions::temporal('set_forced', 'inventories', 'inputs', $temporal);

				$table = '';

				if (!empty($temporal))
				{
					foreach ($temporal as $value)
					{
						$table .=
						'<tr>
							<td class="avatar">
								<figure>
									<img src="' . (!empty($value['product']['avatar']) ? '{$path.uploads}' . $value['product']['avatar'] : '{$path.images}empty.png') . '">
								</figure>
							</td>
							<td>
								{$lang.product} ({$lang.' . $value['product']['type'] . '}): ' . $value['product']['name'] . ' (' . $value['product']['token'] . ')
								<br>
								{$lang.storage}: ' . (!empty($value['product']['input_unity'])
									? $value['input_quantity'] . ' ' . $value['product']['input_unity'] . ' {$lang.of} ' . $value['transform_quantity'] . ' ' . $value['product']['storage_unity'] . ' {$lang.abb_each_one} (' . $value['storage_quantity'] . ' ' . $value['product']['storage_unity'] . ')'
										: $value['storage_quantity'] . ' ' . $value['product']['storage_unity']) . '
								<br>
								{$lang.unitary_price} (' . (!empty($value['product']['input_unity']) ? $value['product']['input_unity'] : $value['product']['storage_unity']) . '): ' . Currency::format($value['price'], Session::get_value('vkye_account')['currency']) . '
								<br>
								{$lang.total}: ' . Currency::format($value['total'], Session::get_value('vkye_account')['currency']) . '
								<br>
								{$lang.location}: ' . $value['location']['name'] . '
								<br>
								' . (!empty($value['categories']) ? '{$lang.categories}: ' . Functions::summation('string', $value['categories'], 'name') : '{$lang.not_categories}') . '
							</td>
							<td class="button">
								<a data-action="remove_product_to_input_table" data-id="' . $value['product']['id'] . '" class="alert"><i class="fas fa-trash"></i><span>{$lang.remove_to_table}</span></a>
							</td>
						</tr>';
					}

					$table .=
					'<tr>
						<td></td>
						<td class="message">' . Currency::format(Functions::summation('math', Functions::temporal('get', 'inventories', 'inputs'), 'total'), Session::get_value('vkye_account')['currency']) . '</td>
						<td></td>
					</tr>';
				}
				else
				{
					$table .=
					'<tr>
						<td class="message">{$lang.not_records_in_the_table}</td>
					</tr>';
				}

				echo json_encode([
					'status' => 'success',
					'data' => [
						'table' => $table
					]
				]);
			}

			if ($_POST['action'] == 'create_inventory_input')
			{
				$errors = [];

				if (Validations::empty($_POST['control']) == false)
					array_push($errors, ['control','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['measure']) == false)
					array_push($errors, ['measure','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['saved']) == false)
					array_push($errors, ['saved','{$lang.dont_leave_this_field_empty}']);

				if (Validations::equals($_POST['saved'], 'bill') == true AND Validations::empty($_POST['bill_token']) == false)
					array_push($errors, ['bill_token','{$lang.dont_leave_this_field_empty}']);

				if (Validations::equals($_POST['saved'], 'bill') == true AND Validations::empty($_POST['bill_payment_way']) == false)
					array_push($errors, ['bill_payment_way','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['date']) == false)
					array_push($errors, ['date','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['hour']) == false)
					array_push($errors, ['hour','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['type']) == false)
					array_push($errors, ['type','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty(Functions::temporal('get', 'inventories', 'inputs')) == false)
					array_push($errors, ['product','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					$query = $this->model->create_inventory_input($_POST);

					if (!empty($query))
					{
						Functions::temporal('set_forced', 'inventories', 'inputs', []);

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
			define('_title', Configuration::$web_page . ' | {$lang.inventories}');

			global $data;

			$data['branches'] = $this->model->read_branches();

			if (!empty($data['branches']))
			{
				Functions::temporal('set_if_not_exist', 'inventories', 'branch', $data['branches'][0]);

				if (!isset($params[0]) OR empty($params[0]))
					header('Location: /inventories/' . System::clean_string('url', Functions::temporal('get', 'inventories', 'branch')['name']));
				else
				{
					$data['inventories'] = $this->model->read_inventories();
					$data['inventories_types'] = $this->model->read_inventories_types(true, 'input');
					$data['providers'] = $this->model->read_providers();
					$data['products'] = $this->model->read_products();
					$data['inventories_locations'] = $this->model->read_inventories_locations(true);
					$data['inventories_categories'] = $this->model->read_inventories_categories(true);
				}
			}
			else if (empty($data['branches']) AND !empty($params[0]))
				header('Location: /inventories');

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
			define('_title', Configuration::$web_page . ' | {$lang.inventories} | {$lang.types}');

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
			define('_title', Configuration::$web_page . ' | {$lang.inventories} | {$lang.locations}');

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
			define('_title', Configuration::$web_page . ' | {$lang.inventories} | {$lang.categories}');

			global $data;

			$data['inventories_categories'] = $this->model->read_inventories_categories();

			$template = $this->view->render($this, 'categories');

			echo $template;
		}
	}
}
