<?php

defined('_EXEC') or die;

class Products_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($params)
	{
		global $data;

		if ($params[0] == 'salemenu')
			$data['type'] = 'sale_menu';
		else if ($params[0] == 'supplies')
			$data['type'] = 'supply';
		else if ($params[0] == 'recipes')
			$data['type'] = 'recipe';
		else if ($params[0] == 'workmaterial')
			$data['type'] = 'work_material';

		if (Format::exist_ajax_request() == true)
		{
			// if ($_POST['action'] == 'filter_products')
			// {
			// 	System::temporal('set_forced', 'products', 'categories', (!empty($_POST['categories']) ? $_POST['categories'] : []));
			//
			// 	echo json_encode([
			// 		'status' => 'success',
			// 		'message' => '{$lang.operation_success}'
			// 	]);
			// }

			if ($_POST['action'] == 'create_product' OR $_POST['action'] == 'update_product')
			{
				$errors = [];

				if (Validations::empty($_POST['name']) == false)
					array_push($errors, ['name','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['token']) == false)
					array_push($errors, ['token','{$lang.dont_leave_this_field_empty}']);
				else if (Validations::string(['uppercase','lowercase','int'], $_POST['token']) == false)
					array_push($errors, ['token','{$lang.invalid_field}']);

				if ($data['type'] == 'sale_menu' AND Validations::empty($_POST['inventory']) == false)
					array_push($errors, ['inventory','{$lang.dont_leave_this_field_empty}']);

				if ((($data['type'] == 'sale_menu' AND Validations::equals($_POST['inventory'], 'yes') == true) OR $data['type'] == 'supply' OR $data['type'] == 'recipe' OR $data['type'] == 'work_material') AND Validations::empty($_POST['unity']) == false)
					array_push($errors, ['unity','{$lang.dont_leave_this_field_empty}']);

				if ($data['type'] == 'sale_menu' AND Validations::empty($_POST['price']) == false)
					array_push($errors, ['price','{$lang.dont_leave_this_field_empty}']);
				else if ($data['type'] == 'sale_menu' AND Validations::number('float', $_POST['price']) == false)
					array_push($errors, ['price','{$lang.invalid_field}']);

				if ($data['type'] == 'recipe' AND Validations::empty($_POST['portion']) == false)
					array_push($errors, ['portion','{$lang.dont_leave_this_field_empty}']);
				else if ($data['type'] == 'recipe' AND Validations::number('float', $_POST['portion'], true) == false)
					array_push($errors, ['portion','{$lang.invalid_field}']);

				if ($data['type'] == 'sale_menu' AND Validations::equals($_POST['inventory'], 'not') == true AND Validations::empty($_POST['formula_code']) == true AND Validations::empty($_POST['formula_parent']) == false)
					array_push($errors, ['formula_parent','{$lang.dont_leave_this_field_empty}']);

				if ($data['type'] == 'sale_menu' AND Validations::equals($_POST['inventory'], 'not') == true AND Validations::equals($_POST['formula_code'], 'SHG78K9H') == true AND Validations::empty($_POST['formula_quantity']) == false)
					array_push($errors, ['formula_quantity','{$lang.dont_leave_this_field_empty}']);

				if ($data['type'] == 'recipe' AND Validations::empty($_POST['supplies']) == false)
					array_push($errors, ['supplies','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					$_POST['type'] = $data['type'];

					if ($data['type'] == 'sale_menu')
						$_POST['avatar'] = $_FILES['avatar'];

					if ($data['type'] == 'sale_menu' OR $data['type'] == 'supply' OR $data['type'] == 'work_material')
					{
						if (!empty($_POST['contents']))
						{
							foreach ($_POST['contents'] as $key => $value)
							{
								unset($_POST['contents'][$key]);

								$_POST['contents'][$value] = [
									'weight' => (!empty($_POST[$value][0]) AND $_POST[$value][0] > 0 AND !empty($_POST[$value][1])) ? $_POST[$value][0] : '',
									'unity' => (!empty($_POST[$value][0]) AND $_POST[$value][0] > 0 AND !empty($_POST[$value][1])) ? $_POST[$value][1] : ''
								];
							}
						}
					}

					if ($data['type'] == 'sale_menu' OR $data['type'] == 'recipe')
					{
						if (!empty($_POST['supplies']))
						{
							foreach ($_POST['supplies'] as $key => $value)
							{
								unset($_POST['supplies'][$key]);

								if (!empty($_POST[$value][0]) AND $_POST[$value][0] > 0 AND !empty($_POST[$value][1]))
								{
									$_POST['supplies'][$value] = [
										'quantity' => $_POST[$value][0],
										'unity' => $_POST[$value][1]
									];
								}
							}
						}
					}

					if ($_POST['action'] == 'create_product')
						$query = $this->model->create_product($_POST);
					else if ($_POST['action'] == 'update_product')
						$query = $this->model->update_product($_POST);

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

			if ($_POST['action'] == 'block_product' OR $_POST['action'] == 'unblock_product' OR $_POST['action'] == 'delete_product')
			{
				if ($_POST['action'] == 'block_product')
					$query = $this->model->block_product($_POST['id']);
				else if ($_POST['action'] == 'unblock_product')
					$query = $this->model->unblock_product($_POST['id']);
				else if ($_POST['action'] == 'delete_product')
					$query = $this->model->delete_product($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.products} | {$lang.' . $params[0] . '}');

			// if (System::temporal('get_if_exists', 'products', 'categories') == false)
			// 	System::temporal('set_forced', 'products', 'categories', []);

			$data['products'] = $this->model->read_products($data['type']);
			$data['products_parents'] = $this->model->read_products($data['type'], 'parent');
			$data['products_unities'] = $this->model->read_products_unities(true);
			$data['products_contents'] = $this->model->read_products_contents(true);
			$data['products_categories'] = $this->model->read_products_categories($data['type'], true);
			$data['products_supplies'] = $this->model->read_products('supply', true);

			$template = $this->view->render($this, 'index');

			echo $template;
		}
	}

	public function categories()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_product_category' OR $_POST['action'] == 'update_product_category')
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
					if ($_POST['action'] == 'create_product_category')
						$query = $this->model->create_product_category($_POST);
					else if ($_POST['action'] == 'update_product_category')
						$query = $this->model->update_product_category($_POST);

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

			if ($_POST['action'] == 'read_product_category')
			{
				$query = $this->model->read_product_category($_POST['id']);

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

			if ($_POST['action'] == 'block_product_category' OR $_POST['action'] == 'unblock_product_category' OR $_POST['action'] == 'delete_product_category')
			{
				if ($_POST['action'] == 'block_product_category')
					$query = $this->model->block_product_category($_POST['id']);
				else if ($_POST['action'] == 'unblock_product_category')
					$query = $this->model->unblock_product_category($_POST['id']);
				else if ($_POST['action'] == 'delete_product_category')
					$query = $this->model->delete_product_category($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.products} | {$lang.categories}');

			global $data;

			$data['products_categories'] = $this->model->read_products_categories();

			$template = $this->view->render($this, 'categories');

			echo $template;
		}
	}

	public function unities()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_product_unity' OR $_POST['action'] == 'update_product_unity')
			{
				$errors = [];

				if (Validations::empty($_POST['name']) == false)
					array_push($errors, ['name','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					if ($_POST['action'] == 'create_product_unity')
						$query = $this->model->create_product_unity($_POST);
					else if ($_POST['action'] == 'update_product_unity')
						$query = $this->model->update_product_unity($_POST);

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

			if ($_POST['action'] == 'read_product_unity')
			{
				$query = $this->model->read_product_unity($_POST['id']);

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

			if ($_POST['action'] == 'block_product_unity' OR $_POST['action'] == 'unblock_product_unity' OR $_POST['action'] == 'delete_product_unity')
			{
				if ($_POST['action'] == 'block_product_unity')
					$query = $this->model->block_product_unity($_POST['id']);
				else if ($_POST['action'] == 'unblock_product_unity')
					$query = $this->model->unblock_product_unity($_POST['id']);
				else if ($_POST['action'] == 'delete_product_unity')
					$query = $this->model->delete_product_unity($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.products} | {$lang.unities}');

			global $data;

			$data['products_unities'] = $this->model->read_products_unities();

			$template = $this->view->render($this, 'unities');

			echo $template;
		}
	}

	public function contents()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_product_content' OR $_POST['action'] == 'update_product_content')
			{
				$errors = [];

				if (Validations::empty($_POST['amount']) == false)
					array_push($errors, ['amount','{$lang.dont_leave_this_field_empty}']);
				else if (Validations::string(['int'], $_POST['amount']) == false)
					array_push($errors, ['amount','{$lang.invalid_field}']);

				if (Validations::empty($_POST['unity']) == false)
					array_push($errors, ['unity','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					if ($_POST['action'] == 'create_product_content')
						$query = $this->model->create_product_content($_POST);
					else if ($_POST['action'] == 'update_product_content')
						$query = $this->model->update_product_content($_POST);

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

			if ($_POST['action'] == 'read_product_content')
			{
				$query = $this->model->read_product_content($_POST['id']);

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

			if ($_POST['action'] == 'block_product_content' OR $_POST['action'] == 'unblock_product_content' OR $_POST['action'] == 'delete_product_content')
			{
				if ($_POST['action'] == 'block_product_content')
					$query = $this->model->block_product_content($_POST['id']);
				else if ($_POST['action'] == 'unblock_product_content')
					$query = $this->model->unblock_product_content($_POST['id']);
				else if ($_POST['action'] == 'delete_product_content')
					$query = $this->model->delete_product_content($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.products} | {$lang.contents}');

			global $data;

			$data['products_contents'] = $this->model->read_products_contents();
			$data['products_unities'] = $this->model->read_products_unities(true);

			$template = $this->view->render($this, 'contents');

			echo $template;
		}
	}
}
